<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Activity;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'activity_type'=>$faker->randomElement(['比赛', '充值', '连续登陆']),
        'application_mode'=>$faker->randomElement(['线上', '线下', '第三方']),
        'activity_title'=>$faker->catchPhrase,
        'activity_url'=> $faker->freeEmailDomain,
        'activity_sort'=>$faker->randomNumber(1, true),
        'activity_img'=>$faker->imageUrl(),
        'start_time'=>$faker->dateTimeThisYear,
        'stop_time'=>$faker->dateTimeThisMonth,
        'activity_term'=>$faker->word,
        'term_num'=>$faker->randomNumber(2, true),
        'award_num'=>$faker->randomNumber(3, true),
        'activity_describe'=> $faker->text(200)
    ];
});
