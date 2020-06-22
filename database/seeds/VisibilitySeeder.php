<?php

use Illuminate\Database\Seeder;

class VisibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $visibilities = [
            [
                'id'    => 0,
                'name'  => 'Show',
            ],
            [
                'id'    => -1,
                'name'  => 'Hidden',
            ],
        ];

        foreach ($visibilities as $visibility) {

            if(\App\Visibility::find($visibility['id']) !== null) {
                continue;
            }

            $tmp = new App\Visibility;
            $tmp->id = $visibility['id'];
            $tmp->name = $visibility['name'];
            $tmp->save();
        }
    }
}
