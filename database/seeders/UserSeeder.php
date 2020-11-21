<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'email' => 'awaludinr830@gmail.com',
            'password' => app('hash')->make('!!!!098xpass#'),
            'name' => 'awaludin'
        ]);
    }
}
