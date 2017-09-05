<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{

    public function entries($entry, $limit) {

        if(!\Laratrust::can('read-log')) {
            return [];
        }

        return \App\LogEntry::where('entry', $entry)->orderByDesc('logged')->limit(intval($limit))->get();

    }

}
