<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = SubscriptionPlan::query();

        if ($q) {
            $query->where('name', 'like', "%{$q}%")->orWhere('id', $q);
        }

        $plans = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.subscription-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscription-plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'menu_limit' => 'nullable|integer|min:0',
            'staff_limit' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name','price','duration_days','menu_limit','staff_limit']);
        // parse features_text if provided
        $featuresText = $request->input('features_text');
        if ($featuresText !== null) {
            $data['features'] = array_values(array_filter(array_map('trim', explode("\n", $featuresText))));
        } else {
            $data['features'] = $request->input('features', []);
        }

        $plan = SubscriptionPlan::create($data);

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Subscription plan created');
    }

    public function show($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        return view('admin.subscription-plans.show', compact('plan'));
    }

    public function edit($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        return view('admin.subscription-plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'menu_limit' => 'nullable|integer|min:0',
            'staff_limit' => 'nullable|integer|min:0',
        ]);

        $plan->name = $request->name;
        $plan->price = $request->price;
        $plan->duration_days = $request->duration_days;
        $plan->menu_limit = $request->menu_limit;
        $plan->staff_limit = $request->staff_limit;
        $featuresText = $request->input('features_text');
        if ($featuresText !== null) {
            $plan->features = array_values(array_filter(array_map('trim', explode("\n", $featuresText))));
        } else {
            $plan->features = $request->input('features', []);
        }
        $plan->save();

        return redirect()->route('admin.subscription-plans.index')->with('success', 'Subscription plan updated');
    }

    public function destroy($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->delete();
        return redirect()->route('admin.subscription-plans.index')->with('success', 'Subscription plan deleted');
    }
}
