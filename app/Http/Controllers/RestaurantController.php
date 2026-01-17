<?php

namespace App\Http\Controllers;

use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use App\Models\Role;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('sanctum');
        $size = $request->query('size', 10);
        $page = $request->query('page', 1);
        $search = $request->query('search', '');
        $userRole = $user->role_id;

        $restaurants = new Restaurant();

        if ($userRole == Role::OWNER) {
            $restaurants = $restaurants->where('owner_id', $user->id);
        }

        if ($userRole == Role::STAFF) {
            $restaurants = $restaurants->where('id', $user->restaurant_id);
        }

        if (!empty($search)) {
            $restaurants = $restaurants->where('name', 'LIKE', "%$search%");
        }

        $restaurants = $restaurants->paginate($size, ['*'], 'page', $page);

        return response()->json([
            "status" => true,
            'message' => 'Restaurants retrieved successfully',
            'paginate'    => $restaurants,
            'data' => RestaurantResource::collection($restaurants),
        ], 200);
    }

    public function store(Request $request)
    {
        $user = $request->user('sanctum');

        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
            'address' => 'nullable|string|max:500',
            'phone' => 'required|string|max:20',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $restaurant = new Restaurant();
        $restaurant->name = $request->name;
        if ($request->hasFile('logo')) {
            $restaurant->logo = $request->file('logo')->store('logos', 'public');
        } else {
            $restaurant->logo = Restaurant::DEFAULT_LOGO;
        }
        $restaurant->address = $request->address;
        $restaurant->phone = $request->phone;
        $restaurant->owner_id = $user->id;
        $restaurant->slug = Str::slug($request->name) . '-' . uniqid();
        $restaurant->save();

        $plan = SubscriptionPlan::where('id', $request->subscription_plan_id)->first();

        // save restaurant subscription
        $restaurantSubscription = new RestaurantSubscription();
        $restaurantSubscription->restaurant_id = $restaurant->id;
        $restaurantSubscription->subscription_plan_id = $plan->id;
        $restaurantSubscription->starts_at = now();
        $restaurantSubscription->ends_at = now()->addDays($plan->duration_days);
        $restaurantSubscription->is_active = true;
        $restaurantSubscription->save();

        return response()->json([
            "status" => true,
            "message" => "Restaurant created successfully",
            "data" => new RestaurantResource($restaurant),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // To be implemented
        $request->validate([
            'name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = $request->user('sanctum');

        $restaurant = Restaurant::where('id', $id)->where('owner_id', $user->id)->first();
        if (!$restaurant) {
            return response()->json([
                "status" => false,
                "message" => "Restaurant not found",
            ], 404);
        }

        if ($request->filled('name')) {
            $restaurant->name = $request->name;
        }

        if ($request->filled('address')) {
            $restaurant->address = $request->address;
        }

        if ($request->filled('phone')) {
            $restaurant->phone = $request->phone;
        }

        if ($request->hasFile('logo')) {

            if ($restaurant->logo && $restaurant->logo !== Restaurant::DEFAULT_LOGO) {
                Storage::disk('public')->delete('logos/' . $restaurant->logo);
            }

            // store the file
            $path = $request->file('logo')->store('logos', 'public');

            // extract ONLY the filename
            $filename = basename($path);

            // save filename only
            $restaurant->logo = $filename;
        }

        $restaurant->save();

        return response()->json([
            "status" => true,
            "message" => "Restaurant updated successfully",
            "data" => new RestaurantResource($restaurant),
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user('sanctum');
        $restaurant = Restaurant::where("id", $id)->where("owner_id", $user->id)->first();

        if (!$restaurant) {
            return response()->json([
                "status" => false,
                "message" => "Restaurant not found",
            ], 404);
        }

        if ($restaurant->logo) {
            Storage::disk('public')->delete($restaurant->logo);
        }

        $restaurant->delete();

        return response()->json([
            "status" => true,
            "message" => "Restaurant deleted successfully",
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user('sanctum');

        $restaurant = Restaurant::where('id', $id)->where('owner_id', $user->id)->whereHas('subscriptions', fn($q) => $q->where('restaurant_subscriptions.is_active', true))->first();
        if (!$restaurant) {
            return response()->json([
                "status" => false,
                "message" => "Restaurant not found",
            ], 404);
        }

        return response()->json([
            "status" => true,
            "message" => "Restaurant retrieved successfully",
            "data" => new RestaurantResource($restaurant),
        ], 200);
    }
}
