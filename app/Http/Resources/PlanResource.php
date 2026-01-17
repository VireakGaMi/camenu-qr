<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "name"=> $this->name,
            "price"=> $this->price,
            "duration_days"=> $this->duration_days,
            "features"=> $this->features,
            "menu_limit"=> $this->menu_limit,
            "staff_limit"=> $this->staff_limit,
        ];
    }
}
