<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Alipay;
use Faker\Generator as Faker;

$factory->define(Alipay::class, function (Faker $faker) {
    return [
        'alipay_name'=>$faker->name('female'),
        'alipay_url'=>$faker->imageUrl(),
        'min_money'=>$faker->randomNumber(4, true),
        'max_money'=>$faker->randomNumber(4, true),
        'day_max_money'=>$faker->randomNumber(4, true),
        'day_money'=>$faker->randomNumber(3, true),
    ];
});
