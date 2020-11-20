<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLevelName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        {
            Schema::create('update_level_name', function (Blueprint $table) {//vip表
                $table->increments('id');
                $table->string('before',150)->default('')->comment('修改前的类型名称');
                $table->string('update_name',150)->default('')->comment('修改后的类型名称');
                $table->integer('pid')->unsigned()->comment('父id');
                $table->integer('hid')->unsigned()->comment('被修改名称的id');
                $table->string('tree',50)->default('')->comment('树');
                $table->timestamps();
            });
        }
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
