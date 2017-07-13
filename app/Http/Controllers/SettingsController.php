<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        //--- Current Uer
        $user = Auth::user();

        return view('settings', [
            'settings' => [
                'rsi-handle'    => $user->name,
                'email'         => $user->email,
                'updated_at'    => $user->updated_at,
                'created_at'    => $user->created_at
            ]
        ]);
    }

}
