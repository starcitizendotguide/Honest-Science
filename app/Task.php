<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'name',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function children() {
        return $this->hasMany('App\TaskChild');
    }

    public function status() {
        //@TODO The status is based on the completion of all sub tasks
        return TaskStatus::byId(1)->first();
    }

}
