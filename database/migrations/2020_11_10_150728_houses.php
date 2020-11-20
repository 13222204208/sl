<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Houses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {//楼盘表
            $table->increments('id');
            $table->string('houses_name',50)->unique()->comment('楼盘名称');
            $table->string('houses_address',150)->unique()->comment('楼盘地址');
            $table->string('map',20)->default('')->comment('地图位置坐标');
            $table->string('city',30)->default('')->comment('所属区县');
            $table->string('business_area',50)->default('')->comment('所属商圈');
            $table->string('houses_info',30)->default('')->comment('楼盘层级架构，多级');
            $table->string('houses_num',30)->default('')->comment('房间号或房间名称');
            $table->string('houses_tenant',30)->default('')->comment('房号上的租户');
            $table->string('broker_name',30)->default('')->comment('租户的隶属经纪人');
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
