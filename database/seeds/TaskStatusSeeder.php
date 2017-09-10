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
                    'icon'  => 'fa fa-battery-4',
                    'color' => '#B6E2A5',
                ]
            ],
            [
                'id'    => 1,
                'name'  => 'Partially Released',
                'rating'=> 4,
                'css'   => [
                    'icon'  => 'fa fa-battery-3',
                    'color' => '#D8E2A5'
                ]
            ],
            [
                'id'    => 2,
                'name'  => 'In-Progress',
                'rating'=> 3,
                'css'   => [
                    'icon'  => 'fa fa-battery-2',
                    'color' => '#E2CFA5'
                ]
            ],
            [
                'id'    => 3,
                'name'  => 'Stagnant/Unknown',
                'rating'=> 2,
                'css'   => [
                    'icon'  => 'fa fa-battery-1',
                    'color' => '#848383'
                ]
            ],
            [
                'id'    => 4,
                'name'  => 'Cut/Broken',
                'rating'=> 1,
                'css'   => [
                    'icon'  => 'fa fa-chain-broken',
                    'color' => '#E2ACA5'
                ]
            ]
        ];

        foreach ($statuses as $status) {

            if(\App\TaskStatus::find($status['id']) !== null) {
                continue;
            }

            $tmp = new App\TaskStatus;

            $tmp->id = $status['id'];
            $tmp->name = $status['name'];
            $tmp->rating = $status['rating'];
            $tmp->css_icon = $status['css']['icon'];
            //$tmp->color = $status['css']['color'];
            $tmp->save();
        }
    }
}
