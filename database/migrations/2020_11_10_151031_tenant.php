<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tenant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant', function (Blueprint $table) {//租户表
            $table->id('id');
            $table->string('tenant_name',50)->unique()->comment('租户名称');
            $table->string('tenant_type',20)->default('')->comment('租户类型');
            $table->string('tenant_user',320)->default('')->comment('联系人，手机号，微信号');
            $table->string('company_type',30)->default('')->comment('公司类型');
            $table->string('start_time',30)->default('')->comment('合同开始时间');
            $table->string('stop_time',30)->default('')->comment('合同结束时间,设置定时任务触发到期提醒');
            $table->string('pay_type',30)->default('')->comment('付款方式');
            $table->string('pay_time',30)->default('')->comment('付款时间,设置定时任务触发到期提醒');
            $table->string('tenant_need',130)->default('')->comment('租户需求');
            $table->string('remark',130)->default('')->comment('备注');
            $table->integer('uid')->unsigned()->comment('经纪人id');
            $table->timestamps();

           // $table->foreign('uid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
