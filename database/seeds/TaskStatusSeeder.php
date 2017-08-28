<?php

use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'id'    => 0,
                'name'  => 'Released',
                'rating'=> 5,
                'css'   => [
                            'icon'  => 'fa fa-battery-4'
                        ]
            ],
            [
                'id'    => 1,
                'name'  => 'Partially Released',
                'rating'=> 4,
                'css'   => [
                            'icon'  => 'fa fa-battery-3'
                        ]
            ],
            [
                'id'    => 2,
                'name'  => 'In-Progress',
                'rating'=> 3,
                'css'   => [
                            'icon'  => 'fa fa-battery-2'
                        ]
            ],
            [
                'id'    => 3,
                'name'  => 'Stagnant/Unknown',
                'rating'=> 2,
                'css'   => [
                            'icon'  => 'fa fa-battery-1'
                        ]
            ],
            [
                'id'    => 4,
                'name'  => 'Cut/Broken',
                'rating'=> 1,
                'css'   => [
                            'icon'  => 'fa fa-chain-broken'
                        ]
            ]
        ];

        foreach ($statuses as $status) {
            $tmp = new App\TaskStatus;

            if(App\TaskStatus::byId($status['id'])->exists()) {
                continue;
            }

            $tmp->id = $status['id'];
            $tmp->name = $status['name'];
            $tmp->rating = $status['rating'];
            $tmp->css_icon = $status['css']['icon'];
            $tmp->save();
        }
    }
}
