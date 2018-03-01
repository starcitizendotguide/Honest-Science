<?php

namespace App\Console\Commands;

use App\LogEntry;
use App\TaskChild;
use App\TaskStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateStatusesValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statuses:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the values of all statuses to deliver them in constant time.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $_count = 0;
        $statuses = TaskStatus::all();

        foreach($statuses as $status) {

            $count = TaskChild::count();

            if($count === 0) {
                $status->value = 0;
                $status->save();
                continue;
            }

            $childCount = DB::select('SELECT COUNT(task_children.id) AS `childCount`
                FROM task_children
                  LEFT JOIN tasks ON tasks.id = task_children.task_id
                WHERE tasks.count_progress_as_one = FALSE  
                  AND (task_children.post_launch = FALSE OR tasks.post_launch = FALSE)
                  AND task_children.status = ' . $status->id)[0]->childCount;
            $childTotal = DB::select('SELECT COUNT(task_children.id) AS `childTotal`
                FROM task_children
                  LEFT JOIN tasks ON tasks.id = task_children.task_id
                WHERE tasks.count_progress_as_one = FALSE 
                  AND (task_children.post_launch = FALSE OR tasks.post_launch = FALSE)')[0]->childTotal;

            $taskCount = DB::select('SELECT COUNT(tasks.id) AS `taskCount` 
                      FROM tasks
                    WHERE tasks.verified = TRUE AND tasks.visibility > -1 
                      AND (tasks.standalone = TRUE OR (tasks.count_progress_as_one = TRUE AND tasks.standalone = false))
                      AND tasks.post_launch = FALSE
                      AND tasks.status = ' . $status->id)[0]->taskCount;
            $taskTotal = DB::select('SELECT COUNT(tasks.id) AS `taskTotal`
                  FROM tasks
                WHERE (tasks.standalone = TRUE OR (tasks.count_progress_as_one = TRUE AND tasks.standalone = false))
                 AND tasks.visibility > -1 AND tasks.verified = TRUE
                 AND tasks.post_launch = FALSE;')[0]->taskTotal;

            $status->value = (($childCount + $taskCount) / ($childTotal + $taskTotal));
            $status->save();

            $_count++;
        }

        //--- Log
        $logEntry = new LogEntry;
        $logEntry->entry    = 'update_statues_value';
        $logEntry->name     = 'Update Statues\' Values';
        $logEntry->message  = sprintf('Updated %d statuses\' values.', $_count);
        $logEntry->logged   = Carbon::now();
        $logEntry->save();

        //@TODO Hack - Delete old log entries
        LogEntry::where([
            ['entry', '=', 'update_statues_value'],
            ['logged', '<=', Carbon::now()->subMinutes(10)]
        ])->delete();

    }
}
