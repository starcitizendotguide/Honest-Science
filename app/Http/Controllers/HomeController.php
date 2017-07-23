<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //@TODO integrating auth
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', [
            'list'      => [
                //Read: https://cloudimperiumgames.com/news/19-CIG-Opens-New-Game-Development-Office-In-Santa-Monica
                'first-office' => Carbon::createFromTimeStamp(strtotime('23 April 2013'))->diffForHumans()
            ],
            'chart'     => []
        ]);
    }
}
