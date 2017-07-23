<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'name',
        'description',
        'status_id',
        'progress',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function children() {
        return $this->hasMany('App\TaskChild');
    }

}
