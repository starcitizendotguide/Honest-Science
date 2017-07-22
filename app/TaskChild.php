<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskChild extends Model
{

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'status_id',
        'progress',
        'type'
    ];

    public function parent() {
        return $this->hasOne('App\Task');
    }

}
