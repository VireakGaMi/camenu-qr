<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            // BASIC
            [
                'name' => 'Basic Monthly',
                'price' => 5.00,
                'duration_days' => 30,
                'features' => ['menu'],
                'menu_limit' => 20,
                'staff_limit' => 0
            ],
            [
                'name' => 'Basic 6 Months',
                'price' => 25.00,
                'duration_days' => 180,
                'features' => ['menu'],
                'menu_limit' => 20,
                'staff_limit' => 0
            ],
            [
                'name' => 'Basic Yearly',
                'price' => 45.00,
                'duration_days' => 365,
                'features' => ['menu'],
                'menu_limit' => 20,
                'staff_limit' => 0
            ],

            // PREMIUM
            [
                'name' => 'Premium Monthly',
                'price' => 10.00,
                'duration_days' => 30,
                'features' => ['menu', 'categories'],
                'menu_limit' => 100,
                'staff_limit' => 3
            ],
            [
                'name' => 'Premium 6 Months',
                'price' => 55.00,
                'duration_days' => 180,
                'features' => ['menu', 'categories'],
                'menu_limit' => 100,
                'staff_limit' => 3
            ],
            [
                'name' => 'Premium Yearly',
                'price' => 100.00,
                'duration_days' => 365,
                'features' => ['menu', 'categories'],
                'menu_limit' => 100,
                'staff_limit' => 3
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
