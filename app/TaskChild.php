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
        return $this->hasOne('App\Task', 'id', 'task_id');
    }

    public function status() {
        return $this->hasOne('App\TaskStatus', 'id', 'status');
    }

    public function type() {
        return $this->hasOne('App\TaskType', 'id', 'type');
    }

    public function sources() {
        return $this->hasMany('App\TaskChildSource', 'child_id', 'id');
    }

}
