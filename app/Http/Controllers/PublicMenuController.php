<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PublicMenuController extends Controller
{
    public function show(string $slug, Request $request)
    {
        $categoryId = $request->query('category');

        $restaurant = Restaurant::with([
            'menus.menuItems.category',
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        // Collect unique categories across all menus
        $categories = $restaurant->categories();

        return view('public.menu', compact(
            'restaurant',
            'categories',
            'categoryId'
        ));
    }

    public function index()
    {
        app()->setLocale('en');
        session()->forget('locale');
        $plans = SubscriptionPlan::query()
            ->select(
                'id',
                'name',
                'price',
                'duration_days',
                'features',
                'menu_limit',
                'staff_limit'
            )
            ->orderBy('price')
            ->get();

        return view('home', compact('plans'));
    }
}
