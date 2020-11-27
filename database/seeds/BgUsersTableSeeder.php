<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BgUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'account'=>'myadmin',
            'name' => '超级管理员',
            'password' => bcrypt('12345678'),
            'created_at' => '2020-11-17 16:19:51',
            'updated_at' => '2020-11-17 18:19:51'
        ]);
    }
}
