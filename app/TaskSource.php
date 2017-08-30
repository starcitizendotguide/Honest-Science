<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskSource extends Model
{

    protected $table = 'task_sources';

    protected $fillable = [
        'child_id',
        'parent_id',
        'link',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function parent() {
        if($this->child_id === null) {
            return $this->hasOne('App\TaskChild', 'id', 'parent_id');
        } else if($this->parent_id === null) {
            return $this->hasOne('App\TaskChild', 'id', 'child_id');
        } else {
            dd("ALERT IN TaskSource.php#parent()");
        }
    }

}
