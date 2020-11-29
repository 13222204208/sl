<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Clean;
use Faker\Generator as Faker;

$factory->define(Clean::class, function (Faker $faker) {
    return [
       // 'houses_name'=>$faker->randomElement(['天通苑', '中关村', '宋庄']),
        'houses_info'=>$faker->randomElement(['天一区401', '天二区601', '天三区602']),
       // 'houses_num'=>$faker->randomElement(['1010', '1020', '1030']),
        'tenant_name'=>$faker->name('female'),
        'is_we_company'=>$faker->randomElement(['是', '否']),
        'company_type'=>$faker->catchPhrase,
        'tenant_user'=> $faker->name('female'),
        'start_time'=>$faker->dateTimeInInterval('-5 days', '-1 days', 'PRC'),
        'stop_time'=>$faker->dateTimeInInterval('+3 days', '+1 years', 'PRC'),
        'pay_type'=>$faker->randomElement(['月付', '季付','半年付','年付']),
        'pay_time'=>$faker->dateTimeInInterval('+20 days', '+100 days', 'PRC'),
        'tenant_need'=> $faker->text(10),
        'remark'=> $faker->text(40),
        'broker_name'=> $faker->name('female'),
        'broker_phone'=>$faker->phoneNumber,
        'position'=> $faker->text(20),
        'enclosure'=> $faker->text(20),
        'created_at'=>$faker->dateTimeInInterval('-5 days', 'now', 'PRC'),
        'updated_at'=>$faker->dateTimeInInterval('-5 days', 'now', 'PRC')
    ];
});
