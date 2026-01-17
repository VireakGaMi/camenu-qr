<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data =  [
            'id'    => $this->id,
            'name'  => $this->name,
            'logo'  => $this->logo ? asset('storage/logos/' . $this->logo) : null,
            'slug'  => $this->slug,
            'phone' => $this->phone,
            'address' => $this->address,
        ];

        if( $this->activeSubscription ) {
            $data['subscription'] = [
                'id'=> $this->activeSubscription->id,
                'plan_id' => $this->activeSubscription->subscription_plan_id,
                'name' => $this->activeSubscription->plan->name,
                'features' => $this->activeSubscription->plan->features,
                'starts_at' => $this->activeSubscription->starts_at,
                'ends_at' => $this->activeSubscription->ends_at,
            ];
        }

        return $data;
    }
}
