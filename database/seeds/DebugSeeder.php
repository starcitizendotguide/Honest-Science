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
        $general = new User;
        $general->name = 'General';
        $general->email = 'general@example.com';
        $general->password = Hash::make('general');
        $general->save();
        $general->attachRole('general');

        //--- Dev User
        $contributor = new User;
        $contributor->name = 'Contributor';
        $contributor->email = 'contributor@example.com';
        $contributor->password = Hash::make('contributor');
        $contributor->save();
        $contributor->attachRole('contributor');

        //--- Dev User
        $recruit = new User;
        $recruit->name = 'recruit';
        $recruit->email = 'recruit@example.com';
        $recruit->password = Hash::make('recruit');
        $recruit->save();
        $recruit->attachRole('recruit');


    }

}
