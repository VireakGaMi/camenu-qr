<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size', 10);
        $page = $request->query('page', 1);
        $search = $request->query('search', '');

        $categories = new Category();
        if ($search) {
            $categories = $categories->where('name', 'LIKE', "%$search%");
        }

        $categories = $categories->orderBy('created_at', 'desc')->paginate($size, ['*'], 'page', $page);

        return response()->json([
            "status" => true,
            'message' => 'Categories retrieved successfully',
            'paginate'    => $categories,
            'data' => CategoryResource::collection($categories),
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'restaurant_id' => $request->restaurant_id,
        ]);

        return response()->json([
            "status" => true,
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return response()->json([
            "status" => true,
            "message" => "Category updated successfully",
            "data" => new CategoryResource($category),
        ], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                "status" => false,
                "message" => "Category not found",
            ], 404);
        }

        $category->delete();

        return response()->json([
            "status" => true,
            "message" => "Category deleted successfully",
        ], 200);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                "status" => false,
                "message" => "Category not found",
            ], 404);
        }
        return response()->json([
            "status" => true,
            "message" => "Category retrieved successfully",
            "data" => new CategoryResource($category),
        ]);
    }
}
