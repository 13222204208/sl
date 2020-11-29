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
            'created_at' => date('Y-m-d H:i:s',time()),
            'updated_at' => date('Y-m-d H:i:s',time())
        ]);
    }
}
