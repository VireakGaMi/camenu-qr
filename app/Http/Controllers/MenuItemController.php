<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuItemResource;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\RestaurantSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // 1️⃣ Get menu + restaurant
        $menu = Menu::with('restaurant')->findOrFail($request->menu_id);
        $restaurant = $menu->restaurant;

        // 2️⃣ Get active subscription
        $subscription = RestaurantSubscription::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->with('plan')
            ->first();

        if (!$subscription || !$subscription->plan) {
            return back()->withErrors([
                'subscription' => 'This restaurant has no active subscription.'
            ]);
        }

        $plan = $subscription->plan;

        // 3️⃣ Enforce menu item limit
        if (!is_null($plan->menu_limit)) {

            $currentItemCount = MenuItem::whereHas('menu', function ($q) use ($restaurant) {
                $q->where('restaurant_id', $restaurant->id);
            })->count();

            if ($currentItemCount >= $plan->menu_limit) {
                return back()->withErrors([
                    'limit' => "Your {$plan->name} plan allows only {$plan->menu_limit} menu items."
                ]);
            }
        }

        // 4️⃣ Create menu item
        $menuItem = MenuItem::create([
            'menu_id' => $menu->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
        ]);

        // 5️⃣ Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $menuItem->update([
                'image' => basename($path),
            ]);
        }

        return redirect()
            ->route('admin.restaurants.show', $restaurant->id)
            ->with('success', 'Menu item created successfully.');
    }


    public function update(Request $request, $id)
    {
        $menuItem = MenuItem::find($id);
        if (!$menuItem) {
            return response()->json([
                "status" => false,
                'message' => 'Menu item not found',
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $menuItem->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('image')) {
            if ($menuItem->image && $menuItem->image != MenuItem::DEFAULT_IMAGE) {
                // Delete old image
                Storage::disk('public')->delete('images/' . $menuItem->image);
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $menuItem->image = basename($imagePath);
            $menuItem->save();
        }

        return response()->json([
            "status" => true,
            "message" => "Menu item updated successfully",
            "data" => new MenuItemResource($menuItem)
        ], 200);
    }

    public function destroy($id)
    {
        $menuItem = MenuItem::find($id);
        if (!$menuItem) {
            return response()->json([
                "status" => false,
                'message' => 'Menu item not found',
            ], 404);
        }

        if ($menuItem->image && $menuItem->image != MenuItem::DEFAULT_IMAGE) {
            // Delete image
            Storage::disk('public')->delete('images/' . $menuItem->image);
        }

        $menuItem->delete();

        return response()->json([
            "status" => true,
            'message' => 'Menu item deleted successfully',
        ], 200);
    }

    public function show($id)
    {
        $menuItem = MenuItem::find($id);
        if (!$menuItem) {
            return response()->json([
                "status" => false,
                'message' => 'Menu item not found',
            ], 404);
        }

        return response()->json([
            "status" => true,
            'message' => 'Menu item retrieved successfully',
            'data' => new MenuItemResource($menuItem),
        ], 200);
    }
}
