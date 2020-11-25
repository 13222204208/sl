<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Clean;
use Faker\Generator as Faker;

$factory->define(Clean::class, function (Faker $faker) {
    return [
       // 'houses_name'=>$faker->randomElement(['天通苑', '中关村', '宋庄']),
        'houses_info'=>$faker->randomElement(['一区', '二区', '三区']),
       // 'houses_num'=>$faker->randomElement(['1010', '1020', '1030']),
        'tenant_name'=>$faker->name('female'),
        'is_we_company'=>$faker->randomElement(['是', '否']),
        'company_type'=>$faker->catchPhrase,
        'tenant_user'=> $faker->freeEmailDomain,
        'start_time'=>$faker->dateTimeThisYear,
        'stop_time'=>$faker->dateTimeThisYear,
        'pay_type'=>$faker->randomElement(['支付宝', '微信', '银行卡']),
        'pay_time'=>$faker->dateTimeThisYear,
        'tenant_need'=> $faker->text(10),
        'remark'=> $faker->text(40),
        'broker_name'=> $faker->name('female'),
        'broker_phone'=>$faker->randomElement(['13222222222']),
        'position'=> $faker->text(20),
        'enclosure'=> $faker->text(20),
        'created_at'=>$faker->dateTimeThisMonth,
        'updated_at'=>$faker->dateTimeThisMonth
    ];
});
