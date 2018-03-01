<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Task;
use App\TaskChild;
use Illuminate\Support\Facades\DB;

class TaskStatus extends Model
{

    protected $fillable = [
        'id',
        'name',
        'rating',
        'css_class',
        'css_icon',
        'value_min',
        'value_max'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * Returns the relative amount of tasks in the specific TaskStatus, but
     * it excludes tasks which are e.g. not visible to user!
     *
     * @return float|int
     */
    public function countRelative() {

        //@TODO Move to Command so it can be scheduled and wont cause 4 queries every time its called (happens often).
        return $this->value;
    }

}
