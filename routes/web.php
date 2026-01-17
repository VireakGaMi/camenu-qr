<?php

use App\Http\Controllers\PublicMenuController;
use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'km'])) {
        abort(400);
    }

    session(['locale' => $locale]);
    app()->setLocale($locale);

    return back();
})->name('lang.switch');


Route::get('/', [PublicMenuController::class, 'index'])->name('home');

// Admin menu management dashboard
Route::prefix('admin')->group(function () {
    // Admin authentication
    Route::get('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('admin.logout');

    // Protected admin routes
    Route::middleware(\App\Http\Middleware\EnsureAdminAuthenticated::class)->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        // Admin Menus (use Admin controller resource to avoid name collisions)
        Route::resource('menus', \App\Http\Controllers\Admin\MenuAdminController::class, [
            'as' => 'admin'
        ]);

        // Admin Dashboard
        Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        // Routes for Categories (admin web UI)
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryAdminController::class, [
            'as' => 'admin'
        ]);

        // Admin resources (name routes under 'admin' to match views)
        Route::resource('menu-items', \App\Http\Controllers\Admin\MenuItemAdminController::class, [
            'as' => 'admin'
        ]);

        Route::resource('restaurants', \App\Http\Controllers\Admin\RestaurantAdminController::class, [
            'as' => 'admin'
        ]);

        Route::post('restaurant-subscriptions/{id}/toggle', [\App\Http\Controllers\Admin\RestaurantSubscriptionAdminController::class, 'toggle'])->name('admin.restaurant-subscriptions.toggle');
        Route::resource('restaurant-subscriptions', \App\Http\Controllers\Admin\RestaurantSubscriptionAdminController::class, [
            'as' => 'admin'
        ]);

        Route::resource('roles', \App\Http\Controllers\RoleController::class, [
            'as' => 'admin'
        ]);

        Route::resource('subscription-plans', \App\Http\Controllers\Admin\SubscriptionPlanAdminController::class, [
            'as' => 'admin'
        ]);

        // Routes for Users (admin web UI)
        // Admin profile (current user)
        Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('admin.profile.show');
        Route::get('profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::patch('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');

        Route::resource('users', \App\Http\Controllers\Admin\UserAdminController::class, [
            'as' => 'admin'
        ]);
    });
});

Route::get('/menu/{slug}', [PublicMenuController::class, 'show'])
    ->name('public.menu.show');


Route::post('/register/order', [\App\Http\Controllers\Admin\Auth\RegisterController::class, 'store'])
    ->name('register.order');
