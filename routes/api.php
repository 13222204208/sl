<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::middleware('cors')->prefix('user')->group(function (){

    Route::post('register','Api\SaoLouController@register');//注册
    Route::post('login','Api\SaoLouController@login');//登录
    Route::post('newpass', 'Api\SaoLouController@newpass');//修改密码

    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::post('logout', 'Api\SaoLouController@logout');//退出登录

        Route::post('head_img', 'Api\SaoLouController@headImg');//用户头像上传

    });
});

Route::middleware('cors')->prefix('sl')->group(function (){

    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('houses', 'Api\SaoLouController@houses');//获取楼盘架构

        Route::get('company', 'Api\SaoLouController@company');//获取公司类型

        Route::get('demand', 'Api\SaoLouController@demand');//获取租户需求名称
        Route::post('sl_record', 'Api\SaoLouController@slRecord');//获取扫楼记录

        Route::post('tenant_record', 'Api\TenantController@tenantRecord');//获取我的租户记录

    });
});

Route::middleware('cors')->prefix('data')->group(function (){

    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::post('refer_record', 'Api\DataController@referRecord');//提交过多少条扫楼记录

    });
});





