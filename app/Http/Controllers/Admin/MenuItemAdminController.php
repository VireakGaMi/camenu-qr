<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemAdminController extends Controller
{
    public function index(Request $request)
    {
        $menuId = $request->query('menu_id');
        $query = MenuItem::with('category', 'menu');
        if ($menuId) {
            $query->where('menu_id', $menuId);
        }
        $menuItems = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        return view('admin.menu-items.index', compact('menuItems', 'menuId'));
    }

    public function create(Request $request)
    {
        $menuId = $request->query('menu_id');
        $restaurantId = $request->query('restaurant_id');

        $menu = Menu::with('restaurant')
            ->where('id', $menuId)
            ->where('restaurant_id', $restaurantId)
            ->firstOrFail();

        // Owner security
        if (auth()->user()->role_id === Role::OWNER) {
            abort_unless(
                $menu->restaurant->owner_id === auth()->id(),
                403
            );
        }

        $categories = Category::where('restaurant_id', $menu->restaurant_id)->orderBy('name')->get();

        return view('admin.menu-items.create', [
            'menu' => $menu,
            'restaurant' => $menu->restaurant,
            'categories' => $categories,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'menu_id'     => 'required|exists:menus,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $menu = Menu::with('restaurant.subscriptions.plan')->findOrFail($request->menu_id);
        $restaurant = $menu->restaurant;

        /** ================= ACTIVE SUBSCRIPTION ================= */
        $subscription = $restaurant->subscriptions()
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$subscription) {
            return back()->withErrors([
                'subscription' => 'This restaurant does not have an active subscription.',
            ]);
        }

        $plan = $subscription->plan ?? $subscription->subscriptionPlan;

        if (!$plan) {
            return back()->withErrors([
                'plan' => 'Subscription plan is missing or invalid.',
            ]);
        }

        /** ================= MENU LIMIT ================= */
        $menuLimit = $plan->menu_limit;

        // ❌ Plan allows ZERO items
        if ($menuLimit === 0) {
            return back()->withErrors([
                'menu_limit' => 'Your current plan does not allow adding menu items.',
            ]);
        }

        // 🔢 Count existing items (per restaurant)
        $currentItemCount = MenuItem::whereHas('menu', function ($q) use ($restaurant) {
            $q->where('restaurant_id', $restaurant->id);
        })->count();

        // ❌ Reached limit
        if ($menuLimit !== null && $currentItemCount >= $menuLimit) {
            return back()->withErrors([
                'menu_limit' => "Menu item limit reached ({$menuLimit}). Please upgrade your plan.",
            ]);
        }

        /** ================= CREATE ITEM ================= */
        $menuItem = MenuItem::create([
            'menu_id'     => $menu->id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'category_id' => $request->category_id,
        ]);

        /** ================= IMAGE ================= */
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $menuItem->image = basename($path);
            $menuItem->save();
        }

        return redirect()
            ->route('admin.restaurants.show', $restaurant->id)
            ->with('success', 'Menu item created successfully.');
    }


    public function show($id)
    {
        $menuItem = MenuItem::with('category', 'menu')->findOrFail($id);
        return view('admin.menu-items.show', compact('menuItem'));
    }

    public function edit($id)
    {
        $user = auth()->user();

        $menuItem = MenuItem::with('menu.restaurant')->findOrFail($id);

        // 🔐 Owner authorization
        if ($user->role?->name === 'owner') {
            abort_unless(
                $menuItem->menu->restaurant->owner_id === $user->id,
                403
            );
        }

        $categories = Category::where(
            'restaurant_id',
            $menuItem->menu->restaurant_id
        )->orderBy('name')->get();

        return view('admin.menu-items.edit', compact('menuItem', 'categories'));
    }


    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $menuItem = MenuItem::with('menu.restaurant')->findOrFail($id);

        // 🔐 Owner authorization (MUST)
        if ($user->role?->name === 'owner') {
            abort_unless(
                $menuItem->menu->restaurant->owner_id === $user->id,
                403
            );
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $menuItem->update(
            collect($validated)->except('image')->toArray()
        );

        if ($request->hasFile('image')) {
            if ($menuItem->image) {
                Storage::disk('public')->delete('images/' . $menuItem->image);
            }

            $path = $request->file('image')->store('images', 'public');
            $menuItem->update([
                'image' => basename($path),
            ]);
        }

        return redirect()->route('admin.restaurants.show', $menuItem->menu->restaurant_id)->with('success', 'Menu item updated');
    }

    public function destroy($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        if ($menuItem->image) {
            Storage::disk('public')->delete('images/' . $menuItem->image);
        }
        $menuItem->delete();
        return redirect()->back()->with('success', 'Menu item deleted');
    }
}
