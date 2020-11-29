<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

         $this->call(BgUsersTableSeeder::class);
         $this->call(CleanTableSeeder::class);
         $this->call(TenantTableSeeder::class);
         $this->call(HouseTableSeeder::class);

    }
}
