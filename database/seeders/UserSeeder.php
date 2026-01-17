<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name'          => 'System Admin',
                'email'         => 'admin@menu.com',
                'password'      => '11223344Aa!', // Change this later
                'phone'         => '0887199572',
                'role_id'       => Role::ADMIN,
                'is_active'     => true,
                'restaurant_id' => null, // Admin does not belong to any restaurant
                'owner_id'      => null,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
