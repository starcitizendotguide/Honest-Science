<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

    public function __construct() {
        $this->middleware('permission:read-user');
    }

    public function index()
    {
        $all = User::all();

        $data = [];

        foreach($all as $user) {

            $data[] = [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'roles'     => $user->roles,
                'created_at'=> $user->created_at,
                'updated_at'=> $user->updated_at,
            ];

        }

        return $data;

    }

}
