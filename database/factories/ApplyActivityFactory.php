<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\ApplyActivity;
use Faker\Generator as Faker;

$factory->define(ApplyActivity::class, function (Faker $faker) {
    return [
        'username'=>$faker->phoneNumber,
        'activity_title'=>$faker->randomElement(['优惠活动', '满800送100', '连续登陆活动']),   
        'apply_time'=>$faker->dateTimeThisYear('+10 days', 'PRC'),
        'award_num'=>$faker->randomNumber(3, true),
    ];
});
