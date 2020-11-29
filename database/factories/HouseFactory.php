<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\House;
use Faker\Generator as Faker;

$factory->define(House::class, function (Faker $faker) {
    return [
        'houses_num'=>$faker->randomElement(['天通苑三区一号楼501', '天通苑一区一号楼401', '天通苑二区3号楼601']),//租房地址
        'map'=>$faker->randomElement(['天通苑三区一号楼501', '天通苑一区一号楼401', '天通苑二区3号楼601'])//租房地址
    ];
});
