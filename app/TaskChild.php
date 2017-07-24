<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskChild extends Model
{

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'status',
        'progress',
        'type'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function parent() {
        return $this->hasOne('App\Task', 'id', 'task_id')->first();
    }

    public function status() {
        return $this->hasOne('App\TaskStatus', 'id', 'status')->first();
    }

}
