<?php

namespace App\Http\Controllers;

use App\Http\Resources\RestaurantPlanResource;
use App\Models\RestaurantSubscription;
use Illuminate\Http\Request;

class RestaurantSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        // Implementation for listing restaurant subscriptions
        $size = $request->query('size', 10);
        $page = $request->query('page', 1);
        $search = $request->query('search', ''); // search name restaurants or subscription plan

        $restaurantSubscriptions = RestaurantSubscription::with(['restaurant', 'plan']);

        if( $search ) {
            $restaurantSubscriptions = $restaurantSubscriptions->whereHas('restaurant', function($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })->orWhereHas('subscriptionPlan', function($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            });
        }

        $restaurantSubscriptions = $restaurantSubscriptions->orderBy("created_at","desc")->paginate($size, ['*'], 'page', $page);

        return response()->json([
            "status" => true,
            "message" => "Restaurant subscriptions retrieved successfully",
            "paginate" => $restaurantSubscriptions,
            "data" => RestaurantPlanResource::collection($restaurantSubscriptions),
        ], 200);
    }

    public function triggerSubscriptionIsActive(Request $request) {
        $restaurantSubscription = RestaurantSubscription::where("id", $request->id)->first();
        if( $restaurantSubscription ) {
            // true or false
            $restaurantSubscription->is_active = !$restaurantSubscription->is_active;
            $restaurantSubscription->save();
        }

        return response()->json([
            "status" => true,
            "message" => "Restaurant subscription status updated successfully",
            "data" => new RestaurantPlanResource($restaurantSubscription),
        ], 200);
    }
 }
