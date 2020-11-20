<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Demand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand', function (Blueprint $table) {//vip表
            $table->increments('id');
            $table->string('type_name',50)->default('')->comment('类型名称');
            $table->integer('parent_id')->unsigned()->comment('父id');
			$table->string('tree',50)->default('')->comment('结构');
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
