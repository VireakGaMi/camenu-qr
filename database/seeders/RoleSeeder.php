<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'   => Role::ADMIN,
                'name' => 'admin'
            ],
            [
                'id'   => Role::OWNER,
                'name' => 'owner'
            ],
            [
                'id'   => Role::STAFF,
                'name' => 'staff'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}

