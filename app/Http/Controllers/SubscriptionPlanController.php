<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanResource;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request)
    {
        // Implementation for listing restaurant subscriptions
        $size = $request->query('size', 10);
        $page = $request->query('page', 1);
        $search = $request->query('search', ''); // search name ir id
        $sCol = $request->query('s_col', 'id');
        $sDir = $request->query('s_dir', 'desc');

        $plans = new SubscriptionPlan();

        if ($search) {
            $plans = $plans->where('name', 'LIKE', "%$search%")
                ->orWhere('id', $search);
        }

        $plans = $plans->orderBy($sCol, $sDir)->paginate($size, ['*'], 'page', $page);

        return response()->json([
            "status" => true,
            "message" => "Subscription plans retrieved successfully",
            "paginate" => $plans,
            "data" => PlanResource::collection($plans),
        ], 200);
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

        $plan = SubscriptionPlan::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'features' => $request->features,
            'menu_limit' => $request->menu_limit,
            'staff_limit' => $request->staff_limit,
        ]);

        return response()->json([
            "status" => true,
            "message" => "Subscription plan created successfully",
            "data" => new PlanResource($plan),
        ], 201);
    }

    public function show($id)
    {
        $plan = SubscriptionPlan::find($id);
        if (!$plan) {
            return response()->json([
                "status" => false,
                "message" => "Subscription plan not found",
            ], 404);
        }
        return response()->json([
            "status" => true,
            "message" => "Subscription plan retrieved successfully",
            "data" => new PlanResource($plan),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'duration_days' => 'sometimes|required|integer|min:1',
            'features' => 'sometimes|nullable|array',
            'menu_limit' => 'sometimes|nullable|integer|min:0',
            'staff_limit' => 'sometimes|nullable|integer|min:0',
        ]);
        $plan = SubscriptionPlan::find($id);
        if (!$plan) {
            return response()->json([
                "status" => false,
                "message" => "Subscription plan not found",
            ], 404);
        }

        if ($request->has('name')) {
            $plan->name = $request->name;
        }
        if ($request->has('price')) {
            $plan->price = $request->price;
        }
        if ($request->has('duration_days')) {
            $plan->duration_days = $request->duration_days;
        }
        if ($request->has('features')) {
            $plan->features = $request->features;
        }
        if ($request->has('menu_limit')) {
            $plan->menu_limit = $request->menu_limit;
        }
        if ($request->has('staff_limit')) {
            $plan->staff_limit = $request->staff_limit;
        }

        $plan->save();

        return response()->json([
            "status" => true,
            "message" => "Subscription plan updated successfully",
            "data" => new PlanResource($plan),
        ]);
    }
    public function destroy($id)
    {
        $plan = SubscriptionPlan::find($id);
        if (!$plan) {
            return response()->json([
                "status" => false,
                "message" => "Subscription plan not found",
            ], 404);
        }
        $plan->delete();
        return response()->json([
            "status" => true,
            "message" => "Subscription plan deleted successfully",
        ]);
    }
}
