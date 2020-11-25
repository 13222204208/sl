<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Tenant;
use Faker\Generator as Faker;

$factory->define(Tenant::class, function (Faker $faker) {
    return [
        'tenant_name'=>$faker->name('female'),
        'is_we_company'=>$faker->randomElement(['公司', '个人']),
        'tenant_user'=> $faker->freeEmailDomain,
     
        'company_type'=>$faker->randomElement(['教育', '娱乐', 'IT']),
        'start_time'=>$faker->dateTimeThisYear,
        'stop_time'=>$faker->dateTimeThisYear,
        'pay_type'=>$faker->randomElement(['支付宝', '微信']),
        'pay_time'=>$faker->dateTimeThisYear,
        'tenant_need'=> $faker->text(10),
        'remark'=> $faker->text(40),
        'uid'=>$faker->randomNumber(2, true),
        'created_at'=>$faker->dateTimeThisYear,
        'updated_at'=>$faker->dateTimeThisYear
    ];
});
