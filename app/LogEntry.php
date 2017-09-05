<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{

    protected $table = 'log_entries';

    public $timestamps = false;

}
