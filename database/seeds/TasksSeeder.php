<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Alpha',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis, tenetur.',
                'children' => [
                    [
                        'name' => 'Beta',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis repellendus consequuntur, quam nulla eum animi impedit odit ratione soluta necessitatibus.',
                        'status' => 2,
                        'type' => 0,
                        'progress' => 0.3178
                    ],
                    [
                        'name' => 'Gamma',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis repellendus consequuntur, quam nulla eum animi impedit odit ratione soluta necessitatibus.',
                        'status' => 0,
                        'type' => 0,
                        'progress' => 0.0433
                    ],
                ]
            ],
            [
                'name' => 'Delta',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis, tenetur.',
                'children' => [
                    [
                        'name' => 'Epsilon',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis repellendus consequuntur, quam nulla eum animi impedit odit ratione soluta necessitatibus.',
                        'status' => 3,
                        'type' => 0,
                        'progress' => 0.3178
                    ],
                ]
            ],
            [
                'name' => 'Zeta',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officiis, tenetur.',
                'children' => [
                    [
                        'name' => 'Eta',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis repellendus consequuntur, quam nulla eum animi impedit odit ratione soluta necessitatibus.',
                        'status' => 0,
                        'type' => 0,
                        'progress' => 0.3178
                    ],
                    [
                        'name' => 'Theta',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis repellendus consequuntur, quam nulla eum animi impedit odit ratione soluta necessitatibus.',
                        'status' => 3,
                        'type' => 0,
                        'progress' => 0.1457
                    ],
                    [
                        'name' => 'Iota',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis repellendus consequuntur, quam nulla eum animi impedit odit ratione soluta necessitatibus.',
                        'status' => 2,
                        'type' => 0,
                        'progress' => 0.0362
                    ]
                ]
            ],
        ];

        foreach($data as $parent) {


            $task = new App\Task;
            $task->name = $parent['name'];
            $task->description = $parent['description'];
            $task->save();

            foreach ($parent['children'] as $child) {

                $tmp = new App\TaskChild;
                $tmp->task_id = $task->id;
                $tmp->name = $child['name'];
                $tmp->description = $child['description'];
                $tmp->status = $child['status'];
                $tmp->progress = $child['progress'];
                $tmp->type = $child['type'];
                $tmp->save();
            }

        }
    }
}
