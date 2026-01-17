<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size', 10);
        $page = $request->query('page', 1);
        $search = $request->query('search', '');
        $restaurantId = $request->query('restaurant_id', null);
        $categoryId = $request->query('category_id', null);

        $menus = new Menu();
        if ($restaurantId) {
            $menus = $menus->where('restaurant_id', $restaurantId);
        }
        if ($search) {
            $menus = $menus->whereHas('menuItems', function($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            });
        }

        if ($categoryId) {
            $menus = $menus->whereHas('menuItems', function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }

        $menus = $menus->with('menuItems')->orderBy('created_at', 'desc')->paginate($size, ['*'], 'page', $page);

        return response()->json([
            "status" => true,
            'message' => 'Menus retrieved successfully',
            'paginate'    => $menus,
            'data' => MenuResource::collection($menus),
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $menu = Menu::create([
            'restaurant_id' => $request->restaurant_id,
        ]);

        return response()->json([
            "status" => true,
            'message' => 'Menu created successfully',
            'data' => new MenuResource($menu),
        ], 201);
    }

    public function destroy($id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return response()->json([
                "status" => false,
                'message' => 'Menu not found',
            ], 404);
        }

        $menu->delete();

        return response()->json([
            "status" => true,
            'message' => 'Menu deleted successfully',
        ], 200);
    }

    public function show($id)
    {
        $menu = Menu::with('menuItems')->find($id);
        if (!$menu) {
            return response()->json([
                "status" => false,
                'message' => 'Menu not found',
            ], 404);
        }

        return response()->json([
            "status" => true,
            'message' => 'Menu retrieved successfully',
            'data' => new MenuResource($menu),
        ], 200);
    }

}
