<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Role;
use Illuminate\Http\Request;

class CategoryAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $q = $request->query('q');

        $query = Category::with('restaurant')
            ->orderByDesc('created_at');

        // 🔐 Owner: only categories of own restaurants
        if ($user && $user->role_id === Role::OWNER) {
            $restaurantIds = Restaurant::where('owner_id', $user->id)->pluck('id');

            $query->whereIn('restaurant_id', $restaurantIds);
        }

        // 🔍 Search
        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }

        $categories = $query
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }


    public function create(Request $request)
    {
        $user = $request->user() ?: auth()->user();
        if ($user->role_id == Role::OWNER) {
            $restaurants = Restaurant::where('owner_id', $user->id)->get();
        } else {
            $restaurants = Restaurant::orderBy('name')->get();
        }

        return view('admin.categories.create', compact('restaurants'));
    }


    public function store(Request $request)
    {
        $user = $request->user() ?: auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        // Get restaurant
        $restaurant = Restaurant::with('activeSubscription')
            ->findOrFail($request->restaurant_id);

        // Owner check
        if ($user->role_id == Role::OWNER && $restaurant->owner_id !== $user->id) {
            return back()->withErrors([
                'restaurant_id' => 'Invalid restaurant selection'
            ])->withInput();
        }

        // Check active subscription
        $subscription = $restaurant->activeSubscription;

        if (!$subscription) {
            return back()->withErrors([
                'subscription' => 'No active subscription found'
            ])->withInput();
        }

        // Check feature permission
        $features = $subscription->plan->features ?? [];

        if (!in_array('categories', $features)) {
            return back()->withErrors([
                'subscription' => 'Your subscription plan does not allow categories'
            ])->withInput();
        }

        // Create category
        Category::create([
            'name' => $request->name,
            'restaurant_id' => $restaurant->id,
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }


    public function edit(Request $request, $id)
    {
        $user = $request->user() ?: auth()->user();
        $category = Category::findOrFail($id);

        if ($user->role_id == Role::OWNER) {
            if ($category->restaurant_id && $category->restaurant->owner_id != $user->id) {
                abort(403);
            }
            $restaurants = Restaurant::where('owner_id', $user->id)->get();
        } else {
            $restaurants = Restaurant::orderBy('name')->get();
        }

        return view('admin.categories.edit', compact('category', 'restaurants'));
    }

    public function update(Request $request, $id)
    {
        $user = $request->user() ?: auth()->user();
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        if ($user->role_id == Role::OWNER) {
            $owns = Restaurant::where('id', $request->restaurant_id)->where('owner_id', $user->id)->exists();
            if (!$owns) {
                return redirect()->back()->withErrors(['restaurant_id' => 'Invalid restaurant selection'])->withInput();
            }
        }

        $category->update($request->only(['name', 'restaurant_id']));

        return redirect()->route('admin.categories.index')->with('success', 'Category updated');
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user() ?: auth()->user();
        $category = Category::findOrFail($id);

        if ($user->role_id == Role::OWNER) {
            if ($category->restaurant_id && $category->restaurant->owner_id != $user->id) {
                abort(403);
            }
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted');
    }

    public function show(Request $request, $id)
    {
        $user = $request->user() ?: auth()->user();
        $category = Category::with('restaurant')->findOrFail($id);

        if ($user->role_id == Role::OWNER) {
            if ($category->restaurant_id && $category->restaurant->owner_id != $user->id) {
                abort(403);
            }
        }

        return view('admin.categories.show', compact('category'));
    }
}
