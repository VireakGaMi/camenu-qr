<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use App\Models\Role;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RestaurantAdminController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = $request->user() ?: auth()->user();

    //     $query = Restaurant::query()->with('owner');

    //     // Admin can see all
    //     if ($user->role_id == Role::OWNER) {
    //         $query->where('owner_id', $user->id);
    //     } elseif ($user->role_id == Role::STAFF) {
    //         if ($user->restaurant_id) {
    //             $query->where('id', $user->restaurant_id);
    //         } else {
    //             $query->whereRaw('1 = 0');
    //         }
    //     }

    //     if ($q = $request->query('q')) {
    //         $query->where(fn($qb) => $qb->where('name', 'like', "%{$q}%")->orWhere('address', 'like', "%{$q}%"));
    //     }

    //     $restaurants = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

    //     return view('admin.restaurants.index', compact('restaurants'));
    // }

    public function index(Request $request)
    {
        $user = $request->user() ?: auth()->user();

        $query = Restaurant::query()->with('owner');

        // =========================
        // ROLE FILTERING
        // =========================
        if ($user->role_id == Role::OWNER) {
            $query->where('owner_id', $user->id);
        } elseif ($user->role_id == Role::STAFF) {
            if ($user->restaurant_id) {
                $query->where('id', $user->restaurant_id);
            } else {
                $query->whereRaw('1 = 0'); // no access
            }
        }

        // =========================
        // SEARCH
        // =========================
        if ($q = $request->query('q')) {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            });
        }

        // =========================
        // OWNER AUTO-REDIRECT
        // =========================
        if ($user->role_id == Role::OWNER && !$request->has('q')) {
            $count = (clone $query)->count();

            if ($count === 1) {
                $restaurant = (clone $query)->first();

                return redirect()->route(
                    'admin.restaurants.show',
                    $restaurant->id
                );
            }
        }

        // =========================
        // LIST VIEW
        // =========================
        $restaurants = $query
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.restaurants.index', compact('restaurants'));
    }


    public function create()
    {
        $plans = SubscriptionPlan::get();
        $owners = User::where('role_id', Role::OWNER)->get();
        return view('admin.restaurants.create', compact('plans', 'owners'));
    }

    public function store(Request $request)
    {
        $user = $request->user() ?: auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|max:2048',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        /** ================= OWNER FLOW ================= */
        if ($user->role_id === Role::OWNER) {

            // 1️⃣ Create restaurant as PENDING
            $restaurant = new Restaurant();
            $restaurant->name = $request->name;
            $restaurant->address = $request->address;
            $restaurant->phone = $request->phone;
            $restaurant->status = false; // ⛔ pending
            $restaurant->slug = Str::slug($request->name) . '-' . uniqid();
            $restaurant->owner_id = $user->id;

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('logos', 'public');
                $restaurant->logo = basename($path);
            }

            $restaurant->save();

            // 2️⃣ Notify ADMIN (Telegram + Email)
            // $this->notifyAdminNewRestaurant($restaurant);

            return redirect()
                ->route('admin.restaurants.index')
                ->with('success', 'Restaurant submitted for approval. Admin will review it.');
        }

        /** ================= ADMIN FLOW (IMMEDIATE CREATE) ================= */
        $restaurant = new Restaurant();
        $restaurant->name = $request->name;
        $restaurant->address = $request->address;
        $restaurant->phone = $request->phone;
        $restaurant->status = true;
        $restaurant->slug = Str::slug($request->name) . '-' . uniqid();
        $restaurant->owner_id = $request->owner_id ?? $user->id;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $restaurant->logo = basename($path);
        }

        $restaurant->save();

        /** Subscription */
        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);
        $startsAt = now();

        $endsAt = $plan->duration_days
            ? $startsAt->copy()->addDays($plan->duration_days)
            : ($plan->duration_months
                ? $startsAt->copy()->addMonths($plan->duration_months)
                : null);

        RestaurantSubscription::create([
            'restaurant_id' => $restaurant->id,
            'subscription_plan_id' => $plan->id,
            'is_active' => true,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);

        Menu::create(['restaurant_id' => $restaurant->id]);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Restaurant created successfully.');
    }


    public function show(Request $request, $id)
    {
        $user = $request->user();

        $query = Restaurant::with([
            'menus.menuItems.category'
        ])->where('id', $id);

        if ($user->role_id === Role::OWNER) {
            $query->where('owner_id', $user->id);
        } elseif ($user->role_id === Role::STAFF) {
            $query->where('id', $user->restaurant_id);
        }

        $restaurant = $query->firstOrFail();

        return view('admin.restaurants.show', compact('restaurant'));
    }


    public function edit(Request $request, $id)
    {
        $user = $request->user() ?: auth()->user();
        $query = Restaurant::where('id', $id);

        if ($user->role_id == Role::OWNER) {
            $query->where('owner_id',   $user->id);
        } elseif ($user->role_id == Role::STAFF) {
            $query->where('id', $user->restaurant_id);
        }

        $restaurant = $query->firstOrFail();
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, $id)
    {
        $user = $request->user() ?: auth()->user();

        $query = Restaurant::where('id', $id);

        if ($user->role_id == Role::OWNER) {
            $query->where('owner_id', $user->id);
        } elseif ($user->role_id == Role::STAFF) {
            $query->where('id', $user->restaurant_id);
        }

        $restaurant = $query->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|max:2048',
        ]);

        $restaurant->update($request->only(['name', 'address', 'phone']));

        // 🔹 LOGO replace
        if ($request->hasFile('logo')) {
            if ($restaurant->logo) {
                Storage::disk('public')->delete('logos/' . $restaurant->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $restaurant->logo = basename($path);
            $restaurant->save();
        }

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant updated');
    }


    public function destroy(Request $request, $id)
    {
        $user = $request->user() ?: auth()->user();
        $query = Restaurant::where('id', $id);

        if ($user->role_id == Role::OWNER) {
            $query->where('owner_id', $user->id);
        } elseif ($user->role_id == Role::STAFF) {
            $query->where('id', $user->restaurant_id);
        }

        $restaurant = $query->firstOrFail();
        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant deleted');
    }
}
