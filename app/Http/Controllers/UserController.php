<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $size = $request->query('size', 10);
        $page = $request->query('page', 1);
        $search = $request->query('search', ''); // search name or id
        $role_id = $request->query('role_id', '');

        $users = new User();

        if($search) {
            $users = $users->where('name', 'LIKE', "%$search%")
                           ->orWhere('id', $search);
        }

        if($role_id) {
            $users = $users->where('role_id', $role_id);
        }

        $users = $users->paginate($size, ['*'], 'page', $page);

        return response()->json([
            "status" => true,
            "message" => "Users retrieved successfully",
            "paginate" => $users,
            "data" => UserResource::collection($users),
        ], 200);
    }
}
