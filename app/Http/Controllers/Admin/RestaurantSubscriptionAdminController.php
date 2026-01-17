<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantSubscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RestaurantSubscriptionAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $q = $request->query('q');

        $query = RestaurantSubscription::with(['restaurant', 'plan'])
            ->orderByDesc('created_at');

        // 🔐 Owner: only own restaurant subscriptions
        if ($user && $user->role?->name === 'owner') {
            $query->whereHas('restaurant', function ($qb) use ($user) {
                $qb->where('owner_id', $user->id);
            });
        }

        // 🔍 Search
        if ($q) {
            $query->where(function ($qb) use ($q) {
                $qb->whereHas(
                    'restaurant',
                    fn($r) =>
                    $r->where('name', 'like', "%{$q}%")
                )
                    ->orWhereHas(
                        'plan',
                        fn($p) =>
                        $p->where('name', 'like', "%{$q}%")
                    );
            });
        }


        $subscriptions = $query
            ->paginate(15)
            ->withQueryString();

        // dd($subscriptions);

        return view('admin.restaurant-subscriptions.index', compact('subscriptions'));
    }


    public function show($id)
    {
        $subscription = RestaurantSubscription::with(['restaurant', 'plan'])->findOrFail($id);
        return view('admin.restaurant-subscriptions.show', compact('subscription'));
    }

    public function toggle(Request $request, $id)
    {
        $subscription = RestaurantSubscription::findOrFail($id);
        $subscription->is_active = !$subscription->is_active;
        $subscription->save();
        return redirect()->back()->with('success', 'Subscription status updated');
    }

    public function edit($id)
    {
        $subscription = RestaurantSubscription::findOrFail($id);
        $plans = SubscriptionPlan::orderBy('name')->get();
        return view('admin.restaurant-subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(Request $request, $id)
    {
        $subscription = RestaurantSubscription::findOrFail($id);

        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

        // 🔹 Get base date:
        // If still active → extend from current end
        // If expired → extend from today
        $baseDate = $subscription->ends_at
            ? Carbon::parse($subscription->ends_at)
            : now();

        // 🔹 Extend by plan duration
        $subscription->subscription_plan_id = $plan->id;
        $subscription->ends_at = $baseDate->addDays($plan->duration_days);
        $subscription->is_active = true;

        // Optional: set starts_at if empty
        if (!$subscription->starts_at) {
            $subscription->starts_at = now();
        }

        $subscription->save();

        return redirect()
            ->route('admin.restaurant-subscriptions.index')
            ->with('success', "Subscription extended by {$plan->duration_days} days.");
    }
}
