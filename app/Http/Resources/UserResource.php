<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $data =  [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role'  => $this->role->name,

            // STAFF → return only their restaurant
            'restaurant' => $this->when(
                $this->role_id === \App\Models\Role::STAFF,
                new RestaurantResource($this->restaurant)
            ),

            // OWNER → return all restaurants
            'restaurants' => $this->when(
                $this->role_id === \App\Models\Role::OWNER,
                RestaurantResource::collection($this->restaurants)
            ),

            // ADMIN → nothing special
        ];

        if (isset($this->token)) {
            $data['token'] = $this->token;
        }

        return $data;
    }
}
