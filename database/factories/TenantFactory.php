<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Tenant;
use Faker\Generator as Faker;

$factory->define(Tenant::class, function (Faker $faker) {
    return [
        'tenant_name'=>$faker->name('female'),
        'is_we_company'=>$faker->randomElement(['我司租户', '外面租户']),
        'tenant_user'=> $faker->name('female'),
     
        'company_type'=>$faker->randomElement(['教育', '娱乐', 'IT']),
        'tenant_address'=>$faker->randomElement(['天通苑三区一号楼501', '天通苑一区一号楼401', '天通苑二区3号楼601']),//租房地址
        'start_time'=>$faker->dateTimeInInterval('-5 days', '-1 days', 'PRC'),
        'stop_time'=>$faker->dateTimeInInterval('+3 days', '+1 years', 'PRC'),
        'pay_type'=>$faker->randomElement(['月付', '季付','半年付','年付']),
        'pay_time'=>$faker->dateTimeInInterval('+20 days', '+100 days', 'PRC'),
        'tenant_need'=> $faker->text(10),
        'remark'=> $faker->text(40),
        'broker_name'=> $faker->name('female'),
        'broker_phone'=>$faker->phoneNumber,
        'state'=>$faker->randomElement(['是', '否']),
        'created_at'=>$faker->dateTimeInInterval('-5 days', 'now', 'PRC'),
        'updated_at'=>$faker->dateTimeInInterval('-5 days', 'now', 'PRC')
    ];
});
