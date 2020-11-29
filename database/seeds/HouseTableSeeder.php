<?php

use App\Model\House;
use Illuminate\Database\Seeder;

class HouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        House::truncate();
        factory(House::class, 3)->create();
    }
}
