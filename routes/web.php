<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('login', function () {
    return view('login.login');
});

/* Route::middleware('role')->group(function () {

}); */



Route::prefix('home')->group(function () {
    Route::get('homepage', function () {
        return view('home.homepage');
    });

    Route::get('clean/count','Home\HomePageController@cleanCount');//获取扫楼记录数量
    Route::post('clean/date','Home\HomePageController@cleanDate');//获取时间范围内扫楼记录数量

    Route::get('tenant/count','Home\HomePageController@tenantCount');//获取租户信息数量
    Route::post('tenant/date','Home\HomePageController@tenantDate');//获取时间范围内扫楼记录数量

    Route::get('tenant/type','Home\HomePageController@tenantType');//租户类型，饼状图
});

Route::get('admin/code','Login\LoginController@adminLogin');//后台登录验证码
Route::post('login/login','Login\LoginController@login');//后台登录验证

Route::prefix('houses')->group(function () {//楼盘管理

    Route::get('created', function () {
        return view('houses.create-house');//创建楼盘
    });
    Route::get('gain/loupan','Houses\HousesController@gainLoupan');//获取楼盘信息
    Route::get('gain/loupan/type/{id}','Houses\HousesController@gainLoupanType');//获取分类下楼盘
    Route::post('create/name','Houses\HousesController@createName');//创建分类名称
    Route::post('update/name','Houses\HousesController@updateName');//更新分类名称
    Route::post('del/name','Houses\HousesController@delName');//删除分类

    Route::get('list', function () {
        return view('houses.house-list');//楼盘列表
    });
});

Route::prefix('branch')->group(function () {//组织架构管理

    Route::get('created', function () {
        return view('branch.create-branch');//创建部门
    });

    Route::get('gain/branch','Branch\BranchController@gainBranch');//获取公司类型信息
    Route::get('gain/branch/type/{id}','Branch\BranchController@gainBranchType');//获取公司类型
    Route::post('create/name','Branch\BranchController@createName');//创建分类名称
    Route::post('update/name','Branch\BranchController@updateName');//更新分类名称
    Route::post('del/name','Branch\BranchController@delName');//删除分类
    
    Route::get('list', function () {
        return view('branch.branch-list');//部门列表
    });
});

Route::prefix('broker')->group(function () {//经纪人管理

    Route::get('account', function () {
        return view('broker.account');//帐号管理
    });

    Route::post('add/account','Broker\BrokerController@addAccount');//添加后台帐号
    Route::post('del/account','Broker\BrokerController@delAccount');//删除一个帐号
    Route::get('query/account','Broker\BrokerController@queryAccount');//获取所有后台帐号
    Route::post('add/role','Broker\BrokerController@addRole');//添加角色
    Route::get('query/role','Broker\BrokerController@queryRole');//查看所有角色
    Route::get('del/role','Broker\BrokerController@delRole');//删除一个角色
    Route::get('gain/role','Broker\BrokerController@gainRole');//获取所有角色

    Route::post('add/role/scope','Broker\BrokerController@addRoleScope');//获取所有角色

    Route::post('add/power','Broker\BrokerController@addPower');//添加权限
    Route::get('gain/power','Broker\BrokerController@gainPower');//获取所有权限名称
    
    Route::get('power', function () {
        return view('broker.power');//权限管理
    });

    Route::get('role', function () {
        return view('broker.role');//角色管理
    });
});

Route::prefix('work')->group(function () {//工作管理

    Route::get('broker-list', function () {
        return view('work.broker-list');//经纪人列表
    });

    Route::get('broker/list','Work\WorkController@brokerList');//获取经纪人列表
    Route::get('query/account/{account}','Work\WorkController@queryAccount');//查询经纪人
    
    Route::get('broker-workinfo', function () {
        return view('work.broker-workinfo');//经纪人工作详情
    });
});

Route::prefix('clean')->group(function () {//扫楼记录管理

    Route::get('all-record', function () {
        return view('clean.all-record');//查看全部扫楼记录
    });

    Route::get('gain/clean','Clean\CleanController@gainClean');
    
    Route::get('record-change', function () {
        return view('clean.record-change');//按楼盘查看数据变更
    });
    Route::get('change/houses','Clean\CleanController@changeHouses');//按楼盘查看数据变更
});

Route::prefix('tenant')->group(function () {//租户管理

    Route::get('query', function () {
        return view('tenant.query');//租户查询
    });

    Route::get('query/tenant','Tenant\TenantController@queryTenant');//租户信息列表 
    Route::get('gain/info/{state}','Tenant\TenantController@gainInfo');//查询单个租户信息

    Route::post('del/tenant','Tenant\TenantController@delTenant');//删除租户信息
    Route::post('update/tenant','Tenant\TenantController@updateTenant');//更新租户信息
    Route::get('stop/date/{day}','Tenant\TenantController@stopDate');//合同快到期的租户
    
    Route::get('manage', function () {
        return view('tenant.manage');//租户管理
    });
});


Route::prefix('parm')->group(function () {//经纪人管理

    Route::get('company', function () {
        return view('parm.company-type');//公司类型
    });

    Route::get('gain/company','Parm\CompanyTypeController@gainCompany');//获取公司类型信息
    Route::get('gain/company/type/{id}','Parm\CompanyTypeController@gainCompanyType');//获取公司类型
    Route::post('create/name','Parm\CompanyTypeController@createName');//创建分类名称
    Route::post('update/name','Parm\CompanyTypeController@updateName');//更新分类名称
    Route::post('del/name','Parm\CompanyTypeController@delName');//删除分类

    Route::get('demand', function () {
        return view('parm.demand');//租户需求
    });
    Route::get('gain/demand','Parm\DemandController@gainDemand');//获取租户需求信息
    Route::get('gain/demand/type/{id}','Parm\DemandController@gainDemandType');//获取租户需求类型
    Route::post('create/name','Parm\DemandController@createName');//创建分类名称
    Route::post('update/name','Parm\DemandController@updateName');//更新分类名称
    Route::post('del/name','Parm\DemandController@delName');//删除分类
});