<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'System Admin',
            'status' => 1
        ]);
        DB::table('roles')->insert([
            'name' => 'Agency Admin',
            'status' => 1
        ]);
        DB::table('roles')->insert([
            'name' => 'Tech Support',
            'status' => 1
        ]);
        DB::table('roles')->insert([
            'name' => 'Team Lead',
            'status' => 1
        ]);
        DB::table('roles')->insert([
            'name' => 'Recruiter',
            'status' => 1
        ]);
    }
}
