<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        if ($user->role->name === 'admin') {
            $stats = [
                'restaurants' => \App\Models\Restaurant::count(),
                'categories'  => \App\Models\Category::count(),
                'users'       => \App\Models\User::count(),
                'subscriptions' => \App\Models\RestaurantSubscription::count(),
            ];
        } else {
            $stats = [
                'restaurants' => \App\Models\Restaurant::where('owner_id', $user->id)->count(),
                'categories'  => \App\Models\Category::whereHas(
                    'restaurant',
                    fn($q) =>
                    $q->where('owner_id', $user->id)
                )->count(),
                'users' => \App\Models\User::whereHas('restaurant',                     fn($q) =>
                $q->where('owner_id', $user->id))->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'role'));
    }
}
