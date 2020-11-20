<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Level extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level', function (Blueprint $table) {//vip表
            $table->increments('id');
            $table->string('type_name',50)->default('')->comment('类型名称');
            $table->integer('parent_id')->unsigned()->comment('父id');
			$table->string('tree',50)->default('')->comment('楼盘架构');
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
