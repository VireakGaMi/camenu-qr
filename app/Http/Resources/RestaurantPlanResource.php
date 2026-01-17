<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'restaurant_id'=> $this->restaurant_id,
            'subscription_plan_id'=> $this->subscription_plan_id,
            'starts_at'=> $this->starts_at,
            'ends_at'=> $this->ends_at,
            'is_active'=> $this->is_active,
            'restaurant'=> new RestaurantResource( $this->whenLoaded('restaurant') ),
            'subscription_plan'=> new PlanResource( $this->whenLoaded('subscriptionPlan') ),
        ];
    }
}
