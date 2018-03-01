<?php

namespace App\Console\Commands;

use App\LogEntry;
use App\Task;
use App\TaskStatus;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class UpdateTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates all tasks.';

    /**
     * Create a new command instance.
     *
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

        //--- All non-standalone tasks since standalone tasks have a status value assigned
        // by hand to them.
        $tasks = Task::where('standalone', '=', false)->get();
        $statuses = TaskStatus::all();

        $DEFAULT_STATUS = $statuses->get(3);

        $_count = 0;

        foreach ($tasks as $task) {

            //--- No children is or should be invalid, so we return the default status
            $children = null;

            if(!($children = $task->children())->exists()) {
                DB::table('tasks')->where('id', $task->id)->update(['status' => $DEFAULT_STATUS->id]);
                $_count++;
                continue;
            }

            //--- Score
            $children = $children->get();
            $table = [];
            $_average = 0;

            $children->each(function($item, $key) use (&$table, &$_average, $statuses) {
                $status = $statuses->get($item->status);
                $_average += $item->progress;
                if(array_key_exists($status->id, $table)) {
                    $table[$status->id] += ($item->progress * $status->rating);
                } else {
                    $table[$status->id] = ($item->progress * $status->rating);
                }
            });
            $table = collect($table);
            $table->each(function($item, int $key) use (&$table, $statuses) {
                $table->put($key, $item / $statuses->get($key)->rating);
            });

            $table = $table->sort();

            $_average /= $children->count();

            $statusId = $table->keys()->first();

            DB::table('tasks')->where('id', $task->id)->update(['status' => $statusId, 'progress' => $_average]);
            $_count++;
            //---

        }

        //--- Log
        $logEntry = new LogEntry;
        $logEntry->entry    = 'update_tasks';
        $logEntry->name     = 'Update Tasks';
        $logEntry->message  = sprintf('Updated %d tasks.', $_count);
        $logEntry->logged   = Carbon::now();
        $logEntry->save();

        //@TODO Hack - Delete old log entries
        LogEntry::where([
            ['entry', '=', 'update_tasks'],
            ['logged', '<=', Carbon::now()->subMinutes(10 * 10)]
        ])->delete();

    }

}
