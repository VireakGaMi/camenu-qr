<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // get all roles
    public function index() {
        $roles = Role::get();
        return response()->json([
            "status" => true,
            'message' => 'Roles retrieved successfully',
            'data'    => $roles,
        ], 200);
    }
}
