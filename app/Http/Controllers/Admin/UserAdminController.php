<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user() ?? auth()->user();
        $q = $request->query('q');
        $users = User::with('role');
        if ($user->role_id == Role::OWNER) {
            $users = $users->where('role_id', '!=', Role::ADMIN)->where('role_id', '!=', Role::OWNER);
            $users = $users->whereHas('restaurant',                     fn($q) =>
            $q->where('owner_id', $user->id));
        }

        $users = $users->when($q, fn($qb) => $qb->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }


    public function create()
    {
        $authUser = auth()->user();

        if ($authUser->role_id === Role::OWNER) {
            $roles = Role::whereNotIn('id', [Role::ADMIN, Role::OWNER])
                ->orderBy('id')
                ->get();

            // ✅ only restaurants owned by this owner
            $restaurants = Restaurant::where('owner_id', $authUser->id)->get();
        } else {
            $roles = Role::orderBy('id')->get();
            $restaurants = collect(); // empty collection
        }

        return view('admin.users.create', compact('roles', 'restaurants'));
    }


    public function store(Request $request)
    {
        $authUser = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'restaurant_id' => 'nullable|exists:restaurants,id',
        ]);

        /** ================= APPLY LIMIT ONLY FOR OWNER ================= */
        if ($authUser->role_id === Role::OWNER) {

            // if create staff user need restaurant_id
            $request->validate([
                'restaurant_id' => 'required|exists:restaurants,id',
            ]);

            $subscription = RestaurantSubscription::where('restaurant_id', $authUser->restaurant_id)
                ->where('is_active', true)
                ->first();

            if (!$subscription) {
                return back()->withErrors([
                    'subscription' => 'Your restaurant does not have an active subscription.'
                ]);
            }

            $plan = $subscription->plan ?? $subscription->subscriptionPlan;
            $staffLimit = $plan->staff_limit ?? 0;

            if ($staffLimit > 0) {
                $currentStaffCount = User::where('restaurant_id', $authUser->restaurant_id)->count();

                if ($currentStaffCount >= $staffLimit) {
                    return back()->withErrors([
                        'limit' => "Staff limit reached ({$staffLimit}). Please upgrade your plan."
                    ]);
                }
            }
        }

        /** ================= CREATE USER ================= */
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'restaurant_id' => $request->restaurant_id ?? null,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully');
    }


    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }


    public function edit($id)
    {
        $authUser = auth()->user();
        $user = User::findOrFail($id);

        if ($authUser->id == $user->id) {
            $roles = Role::where('id', $authUser->role_id)->get();
        } elseif ($authUser->role_id === Role::OWNER) {
            $roles = Role::whereNotIn('id', [Role::ADMIN, Role::OWNER])->orderBy('id')->get();
        } else {
            $roles = Role::orderBy('id')->get();
        }

        // ✅ restaurants for OWNER only
        $restaurants = $authUser->role_id === Role::OWNER
            ? Restaurant::where('owner_id', $authUser->id)->get()
            : collect();

        return view('admin.users.edit', compact('user', 'roles', 'restaurants'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $authUser = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'restaurant_id' => 'nullable|exists:restaurants,id',
        ]);


        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        if ($authUser->role_id === Role::OWNER) {

            // must belong to owner
            $ownsRestaurant = Restaurant::where('id', $request->restaurant_id)
                ->where('owner_id', $authUser->id)
                ->exists();

            if (!$ownsRestaurant) {
                abort(403, 'Unauthorized restaurant selection');
            }

            $user->restaurant_id = $request->restaurant_id;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (auth()->user()->id == $user->id) {
            return redirect()->route('admin.users.index')->withErrors(['error' => 'You cannot delete yourself.']);
        }
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted');
    }
}
