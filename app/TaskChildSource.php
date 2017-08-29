<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskChildSource extends Model
{
    protected $fillable = [
        'child_id',
        'link',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function parent() {
        return $this->hasOne('App\TaskChild', 'id', 'child_id');
    }

}
