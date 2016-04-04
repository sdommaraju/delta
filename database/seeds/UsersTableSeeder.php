<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Srinivas',
            'last_name' => 'Srinu',
            'email' => 'sdommaraju@innominds.com',
            'password' => bcrypt('innominds'),
            'role_id' => 1
            
        ]);
    }
}
