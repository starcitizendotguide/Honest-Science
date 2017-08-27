<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\TaskStatus;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('home', [
            'list'      => [
                //Read: https://cloudimperiumgames.com/news/19-CIG-Opens-New-Game-Development-Office-In-Santa-Monica
                'first-office'  => Carbon::createFromTimeStamp(strtotime('23 April 2013'))->diffForHumans(),
            ],
        ]);
    }
}
