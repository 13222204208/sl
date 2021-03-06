<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Clean extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clean', function (Blueprint $table) {//vip表
            $table->increments('id');
            $table->string('houses_name',50)->default('')->comment('楼盘名称');
            $table->string('houses_info',120)->default('')->comment('楼盘信息');
            $table->string('houses_num',30)->default('')->comment('房间号');
            $table->string('tenant_name',50)->comment('租户名称');
            $table->string('is_we_company')->comment('是否我司租户,1代表是，0代表否');
            $table->string('property_type',50)->default('')->comment('物业类型');
            $table->string('company_type',30)->default('')->comment('公司类型');
            $table->string('tenant_user',320)->default('')->comment('联系人，手机号，微信号');
            $table->string('start_time',30)->default('')->comment('合同开始时间');
            $table->string('contract_period',30)->default('')->comment('合同期限多少个月');
            $table->string('stop_time',30)->default('')->comment('合同结束时间,设置定时任务触发到期提醒');
            $table->string('pay_type')->default('')->comment('付款方式');
            $table->string('pay_time',30)->default('')->comment('付款时间,设置定时任务触发到期提醒');
            $table->string('tenant_need',130)->default('')->comment('租户需求');
            $table->string('remark',130)->default('')->comment('备注');
            $table->string('city',30)->default('')->comment('所属区县');
            $table->string('business_area',50)->default('')->comment('所属商圈');
            $table->string('broker_name',30)->default('')->comment('经纪人姓名');
            $table->string('broker_phone',11)->comment('经纪人手机号');
            $table->string('position',320)->default('')->comment('当前提交位置');
            $table->string('enclosure',300)->default('')->comment('附件');
            $table->string('permission',50)->default('')->comment('可以查看的权限范围，比如只能看某一个店');
            $table->string('attribute_branch')->comment('所属的部门');
            $table->integer('uid')->unsigned()->comment('用户id');
            $table->timestamps();
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
