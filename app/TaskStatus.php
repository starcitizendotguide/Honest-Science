<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\TaskChild;

class TaskStatus extends Model
{

    protected $fillable = [
        'id',
        'name',
        'rating',
        'css_class',
        'css_icon'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function countRelative() {

        $count = TaskChild::count();

        if($count === 0) {
            return 0;
        }

        return (TaskChild::all()->where('status', '=', $this->id)->count() / TaskChild::count());
    }

    public function tasks() {
        return $this->hasMany('App\Task', 'status', 'id');
    }

    public function scopeById($query, $id) {
        return $query->where('id', '=', $id);
    }

}
