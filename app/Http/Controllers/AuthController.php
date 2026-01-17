<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login user and return Sanctum token
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // Find user by email
        $user = User::where('email', $credentials['email'])->first();

        // User not found
        if (!$user) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        // Check password
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        // Check if active
        if (!$user->is_active) {
            return response()->json(['message' => 'Your account is disabled'], 403);
        }

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->token = $token;

        // Response
        return response()->json([
            "status" => true,
            'message' => 'Login successful',
            'data'    => new UserResource($user),
        ], 200);
    }

    public function me(Request $request)
    {
        $user = $request->user('sanctum');
        return response()->json([
            "status" => true,
            'message' => 'get me successful',
            'data' => new UserResource($user)
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user('sanctum')->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function AdminRegisterForOwner(Request $request)
    {
        // Registration logic for admin users to create owner accounts
        $user = $request->user('sanctum');

        if (!$user || $user->role->name !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
        ]);
        $owner = new User();
        $owner->name = $validatedData['name'];
        $owner->email = $validatedData['email'];
        $owner->password = $validatedData['password'];
        $owner->phone = $validatedData['phone'];
        $owner->is_active = true;
        $owner->role_id = Role::OWNER;
        $owner->save();

        return response()->json(['message' => 'Create owner successfully!'], 200);
    }

    public function ownerRegisterForStaff(Request $request)
    {
        // Auth user
        $owner = $request->user('sanctum');

        // Only owner can create staff
        if (!$owner || $owner->role_id !== Role::OWNER) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate request
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8|confirmed',
            'phone'         => 'required|string|max:20',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        // Check if restaurant belongs to this owner
        $restaurant = $owner->restaurants()->where('id', $validatedData['restaurant_id'])->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Unauthorized: This restaurant is not yours'], 403);
        }

        // Get active subscription for this restaurant
        $subscription = $restaurant->subscriptions()
            ->where('is_active', true)
            ->where('ends_at', '>=', now())
            ->latest()
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription'], 403);
        }

        // Check staff limit from subscription plan
        $plan = $subscription->plan;

        $currentStaffCount = User::where('restaurant_id', $restaurant->id)
            ->where('role_id', Role::STAFF)
            ->count();

        if ($currentStaffCount >= $plan->staff_limit) {
            return response()->json([
                'message' => 'Staff limit reached for this subscription plan.'
            ], 403);
        }

        // Create staff user
        $staff = User::create([
            'name'          => $validatedData['name'],
            'email'         => $validatedData['email'],
            'password'      => $validatedData['password'],
            'phone'         => $validatedData['phone'],
            'role_id'       => Role::STAFF,
            'restaurant_id' => $restaurant->id,
            'is_active'     => true,
        ]);

        return response()->json([
            'message' => 'Staff created successfully!',
            'staff'   => $staff
        ], 200);
    }
}
