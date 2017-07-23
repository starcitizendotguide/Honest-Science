<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\User;

class DebugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //--- Dev User
        $yonas = new User;
        $yonas->name = 'Yonas';
        $yonas->email = 'jan@krueger-jan.de';
        $yonas->password = Hash::make('testing');
        $yonas->save();

        $yonas->attachRole('general');

    }

}
