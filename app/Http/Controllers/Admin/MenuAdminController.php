<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Restaurant;

class MenuAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Menu::with(['menuItems', 'restaurant'])
            ->orderByDesc('created_at');

        if ($user && $user->role?->name === 'owner') {
            $restaurantIds = Restaurant::where('owner_id', $user->id)->pluck('id');

            $query->whereIn('restaurant_id', $restaurantIds);
        }

        $menus = $query
            ->paginate(15)
            ->withQueryString();

        return view('admin.menus.index', compact('menus'));
    }


    public function create()
    {
        $restaurants = Restaurant::orderBy('name')->get();
        return view('admin.menus.create', compact('restaurants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        Menu::create([
            'restaurant_id' => $request->restaurant_id,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully');
    }

    public function edit($id)
    {
        $menu = Menu::with('menuItems')->findOrFail($id);
        $restaurants = Restaurant::orderBy('name')->get();
        return view('admin.menus.edit', compact('menu', 'restaurants'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $menu->update([
            'restaurant_id' => $request->restaurant_id,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully');
    }

    public function destroy($id)
    {
        $menu = Menu::find($id);
        if ($menu) {
            $menu->delete();
        }

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully');
    }

    public function show($id)
    {
        $menu = Menu::with(['menuItems.category', 'restaurant'])->findOrFail($id);
        return view('admin.menus.show', compact('menu'));
    }
}
