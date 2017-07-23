<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{

    protected $fillable = [
        'id',
        'name',
        'css_class',
        'css_icon'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function tasks() {
        return $this->hasMany('App\Task', 'status', 'id');
    }

    public function scopeById($query, $id) {
        return $query->where('id', '=', $id);
    }

}
