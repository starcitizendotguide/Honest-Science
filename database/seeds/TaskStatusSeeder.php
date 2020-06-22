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
                'value_min' => 1,
                'value_max' => 1,
                'css'   => [
                    'icon'  => 'fa fa-battery-4',
                ]
            ],
            [
                'id'    => 1,
                'name'  => 'Partially Released',
                'rating'=> 4,
                'value_min' => 0.5,
                'value_max' => (1 - 0.01),
                'css'   => [
                    'icon'  => 'fa fa-battery-3',
                ]
            ],
            [
                'id'    => 2,
                'name'  => 'In-Progress',
                'rating'=> 3,
                'value_min' => 0.2,
                'value_max' => (0.5 - 0.01),
                'css'   => [
                    'icon'  => 'fa fa-battery-2',
                ]
            ],
            [
                'id'    => 3,
                'name'  => 'Stagnant/Unknown',
                'rating'=> 2,
                'value_min' => 0,
                'value_max' => (0.2 - 0.01),
                'css'   => [
                    'icon'  => 'fa fa-battery-1',
                ]
            ],
            [
                'id'    => 4,
                'name'  => 'Cut/Broken',
                'rating'=> 1,
                'value_min' => 0,
                'value_max' => (0.2 - 0.01),
                'css'   => [
                    'icon'  => 'fa fa-chain-broken',
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
            $tmp->value_min = $status['value_min'];
            $tmp->value_max = $status['value_max'];
            $tmp->save();
        }
    }
}
