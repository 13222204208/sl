<?php

use App\Model\Clean;
use Illuminate\Database\Seeder;

class CleanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Clean::truncate();
        factory(Clean::class, 30)->create();
    }
}
