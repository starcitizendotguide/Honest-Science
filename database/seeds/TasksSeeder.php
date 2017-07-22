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
                        'status' => 1,
                        'progress' => 0.1806, //TODO Computed... just demo data here
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
                        'status' => 2,
                        'progress' => 0.3178,
                        'collapsed' => false,
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
                        'status' => 3,
                        'progress' => 0.1666,
                        'collapsed' => false,
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

        //@Note: Hack... to avoid fetching the incremental ID
        $id = 1;
        foreach($data as $parent) {

            DB::table('tasks')->insert([
                'name'          => $parent['name'],
                'description'   => $parent['description'],
                'status_id'     => $parent['status'],
                'progress'      => $parent['progress'],

                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            foreach ($parent['children'] as $child) {
                DB::table('task_children')->insert([
                    'task_id'       => $id,
                    'name'          => $child['name'],
                    'description'   => $child['description'],
                    'status_id'     => $child['status'],
                    'progress'      => $child['progress'],
                    'type'          => $child['type'],

                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }

            $id++;

        }
    }
}
