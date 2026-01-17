<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantSubscriptionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubscriptionPlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::post("/login",[AuthController::class, 'login'])->name("auth.login");
    Route::post("/logout",[AuthController::class, 'logout'])->name("auth.logout")->middleware('is.login');
    Route::post("/create/owners",[AuthController::class, 'AdminRegisterForOwner'])->name("auth.create.owner")->middleware(['is.login', 'check.guard:admin']);
    Route::post('/create/staffs',[AuthController::class,'ownerRegisterForStaff'])->name("auth.create.staff")->middleware(['is.login', 'check.guard:owner']);

    Route::get('/me',[AuthController::class,'me'])->name("auth.me")->middleware('is.login');
});

Route::get('/roles',[RoleController::class,'index'])->name("auth.roles")->middleware('is.login');

Route::prefix("restaurants")->group(function () {
    Route::get("/",[RestaurantController::class, 'index'])->name("restaurant.index")->middleware(['is.login', 'check.guard:admin,owner,staff']);
    Route::get('/{id}',[RestaurantController::class, 'show'])->name("restaurant.show")->middleware(['is.login', 'check.guard:admin,owner,staff']);
    Route::post("/",[RestaurantController::class, 'store'])->name("restaurant.create")->middleware(['is.login', 'check.guard:admin,owner']);
    Route::post("/{id}", [RestaurantController::class, 'update'])->name("restaurant.update")->middleware(['is.login', 'check.guard:admin,owner']);
    Route::delete('/{id}',[RestaurantController::class, 'destroy'])->name("restaurant.delete")->middleware(['is.login', 'check.guard:admin,owner']);
});

Route::prefix('subscripted/restaurants')->group(function () {
    Route::get('/',[RestaurantSubscriptionController::class,'index'])->name('restaurant.subscription.index')->middleware(['is.login', 'check.guard:admin']);
    Route::post('/trigger',[RestaurantSubscriptionController::class,'triggerSubscriptionIsActive'])->name('restaurant.subscription.trigger')->middleware(['is.login', 'check.guard:admin']);
});

Route::prefix('subscriptions')->group(function () {
    Route::get('/',[SubscriptionPlanController::class,'index'])->name('subscription.plan.index')->middleware(['is.login', 'check.guard:admin']);
    Route::get('/{id}',[SubscriptionPlanController::class,'show'])->name('subscription.plan.show')->middleware(['is.login', 'check.guard:admin']);
    Route::post('/',[SubscriptionPlanController::class, 'store'])->name('subscription.plan.store')->middleware(['is.login', 'check.guard:admin']);
    Route::post('/{id}',[SubscriptionPlanController::class, 'update'])->name('subscription.plan.update')->middleware(['is.login', 'check.guard:admin']);
    Route::delete('/{id}',[SubscriptionPlanController::class, 'destroy'])->name('subscription.plan.destroy')->middleware(['is.login', 'check.guard:admin']);
});

Route::prefix('menus')->group(function () {
    Route::get('/', [\App\Http\Controllers\MenuController::class, 'index'])->name('menu.index')->middleware(['is.login']);
    Route::get('/{id}', [\App\Http\Controllers\MenuController::class, 'show'])->name('menu.show')->middleware(['is.login']);
    Route::post('/', [\App\Http\Controllers\MenuController::class, 'store'])->name('menu.store')->middleware(['is.login', 'check.guard:admin,owner,staff']);
    Route::post('/{id}', [\App\Http\Controllers\MenuController::class, 'update'])->name('menu.update')->middleware(['is.login', 'check.guard:admin,owner,staff']);
    Route::delete('/{id}', [\App\Http\Controllers\MenuController::class, 'destroy'])->name('menu.destroy')->middleware(['is.login', 'check.guard:admin,owner,staff']);
});

Route::prefix('menu-items')->group(function () {
    Route::get('/', [\App\Http\Controllers\MenuItemController::class, 'index'])->name('menu.item.index')->middleware(['is.login']);
    Route::get('/{id}', [\App\Http\Controllers\MenuItemController::class, 'show'])->name('menu.item.show')->middleware(['is.login']);
    Route::post('/', [\App\Http\Controllers\MenuItemController::class, 'store'])->name('menu.item.store')->middleware(['is.login', 'check.guard:admin,owner,staff']);
    Route::post('/{id}', [\App\Http\Controllers\MenuItemController::class, 'update'])->name('menu.item.update')->middleware(['is.login', 'check.guard:admin,owner,staff']);
    Route::delete('/{id}', [\App\Http\Controllers\MenuItemController::class, 'destroy'])->name('menu.item.destroy')->middleware(['is.login', 'check.guard:admin,owner,staff']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [\App\Http\Controllers\CategoryController::class, 'index'])->name('category.index')->middleware(['is.login']);
    Route::get('/{id}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('category.show')->middleware(['is.login']);
    Route::post('/', [\App\Http\Controllers\CategoryController::class, 'store'])->name('category.store')->middleware(['is.login', 'check.guard:admin,owner,staff']);
    Route::post('/{id}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('category.update')->middleware(['is.login', 'check.guard:admin,owner,staff']);
    Route::delete('/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('category.destroy')->middleware(['is.login', 'check.guard:admin,owner,staff']);

});







