<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SubscriptionPlan as Plan;
use App\Models\Restaurant;
use App\Models\RestaurantSubscription as Order;
use App\Models\User;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'plan_id' => 'required|exists:plans,id',
        ]);

        // 1️⃣ Create owner
        $user = User::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name]
        );

        // 2️⃣ Get plan
        $plan = Plan::findOrFail($request->plan_id);

        // 3️⃣ Create restaurant
        $restaurant = Restaurant::create([
            'name' => $request->restaurant_name,
            'slug' => Str::slug($request->restaurant_name),
            'owner_id' => $user->id,
            'status' => 'pending',
            'expires_at' => null, // activate after payment
        ]);

        // 4️⃣ Create order (for ABA PayWay)
        $order = Order::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'plan_id' => $plan->id,
            'amount' => $plan->price,
            'status' => 'pending',
            'transaction_id' => Str::uuid(),
        ]);

        // 5️⃣ Redirect to ABA PayWay
        return redirect()->route('payment.aba', $order->id);
    }
}
