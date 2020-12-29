<?php

use App\Model\User;
use App\Model\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

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
      
         if (session('id')==1) {
             $per = array('统计','楼盘架构管理','组织架构管理','经纪人管理','工作管理','扫楼记录管理','租户','参数配置','新建楼盘','楼盘列表','编辑部门','帐号管理','角色管理','权限管理','经纪人列表','查看全部扫楼记录','按楼盘查看数据变更',
             '表单选项配置','合同到期提醒手机');

             return view('index',['per'=>$per]);
         }else if (session('id')) {
            $user= User::find(session('id'));
            $permission= $user->getAllPermissions();
            $data= json_decode($permission);
            $per = array();
        
            foreach($data as $d){
                $per[] = $d->{'name'};
            }
             //dd($per);exit;
            return view('index',['per'=>$per]);
        }
        
     })->middleware('adminLogin');



Route::get('login', function () {
    return view('login.login');
});

//后台退出
Route::get('logout', function (Illuminate\Http\Request $request) {
    $request->session()->flush();
    return redirect('login');
});
/* Route::middleware('role')->group(function () {

}); */

Route::get('user/upassword', function () {
    return view('user.upassword');
});
Route::get('testsms','TestController@testsms');
Route::post('user/set/mypass','Login\LoginController@setMypass');//修改登陆密码

Route::prefix('home')->group(function () {
    Route::get('homepage', function () {
        return view('home.homepage');
    })->name('home')->middleware('adminRoute');

    Route::get('clean/count','Home\HomePageController@cleanCount');//获取扫楼记录数量
    Route::post('clean/date','Home\HomePageController@cleanDate');//获取时间范围内扫楼记录数量

    //已出租即有租户的房间占总体房间数的百分比
    Route::get('tenant/kong','Home\HomePageController@tenantKong');//获取时间范围内扫楼记录数量


    Route::get('tenant/count','Home\HomePageController@tenantCount');//获取租户信息数量
    Route::post('tenant/date','Home\HomePageController@tenantDate');//获取时间范围内扫楼记录数量

    Route::get('tenant/type','Home\HomePageController@tenantType');//租户类型，饼状图

    Route::get('due/tenant','Home\HomePageController@dueTenant');//快到期租户
});

Route::get('admin/code/{tmp}','Login\LoginController@adminLogin');//后台登录验证码
Route::post('login/login','Login\LoginController@login');//后台登录验证

Route::prefix('houses')->group(function () {//楼盘管理

    Route::get('created', function () {
        return view('houses.create-house');//创建楼盘
    })->name('houses')->middleware('adminRoute');
    Route::get('gain/loupan/{id}','Houses\HousesController@gainLoupan');//获取楼盘信息
    Route::get('testa/{id}','Houses\HousesController@testa');//获取楼盘信息
    Route::get('info','Houses\HousesController@info');//楼盘详细信息
    Route::post('update/house','Houses\HousesController@updateHouse');//楼盘详细信息
    Route::get('search/{hname}','Houses\HousesController@search');//搜索楼盘
    
    Route::get('get/permission','Clean\CleanController@getPermission');//是否有导出权限

    Route::get('tenant/info/{hnum}','Houses\HousesController@tenantInfo');//楼盘上的租户信息

    Route::get('add', function () {
        return view('houses.add-house');//添加楼盘
    });

    Route::get('look/num/{id}','Houses\HousesController@lookNum');//获取分类下楼盘的房间号
  
    Route::get('gain/house/num','Houses\HousesController@gainHouseNum');

    Route::post('add/house','Houses\HousesController@addHouse')->middleware('adminLogin');//创建楼盘信息
    Route::get('look/house','Houses\HousesController@lookHouse');//查看楼盘信息

    Route::get('gain/loupan/type/{id}','Houses\HousesController@gainLoupanType');//获取分类下楼盘
    Route::post('create/name','Houses\HousesController@createName');//创建分类名称
    Route::post('update/name','Houses\HousesController@updateName');//更新分类名称
    Route::post('del/name','Houses\HousesController@delName');//删除分类

     Route::get('list', function () {
        return view('houses.house-list');//楼盘列表
    })->name('hlist')->middleware('adminRoute');
});

Route::prefix('branch')->group(function () {//组织架构管理

    Route::get('created', function () {
        return view('branch.create-branch');//创建部门
    })->name('branch')->middleware('adminRoute');

    Route::get('gain/branch','Branch\BranchController@gainBranch');//获取公司类型信息
    Route::get('gain/branch/type/{id}','Branch\BranchController@gainBranchType');//获取公司类型
    Route::post('create/name','Branch\BranchController@createName');//创建分类名称
    Route::post('update/name','Branch\BranchController@updateName');//更新分类名称
    Route::post('del/name','Branch\BranchController@delName');//删除分类
    
/*     Route::get('list', function () {
        return view('branch.branch-list');//部门列表
    }); */
});

Route::prefix('broker')->group(function () {//经纪人管理

    Route::get('account', function () {
        return view('broker.account');//帐号管理
    })->name('account')->middleware('adminRoute');

    Route::post('add/account','Broker\BrokerController@addAccount');//添加后台帐号
    Route::post('have/branch','Broker\BrokerController@haveBranch');//选择部门

    Route::post('user/branch','Broker\BrokerController@userBranch');//给用户分配部门

    Route::post('del/account','Broker\BrokerController@delAccount');//删除一个帐号
    Route::post('update/account','Broker\BrokerController@updateAccount');//更新帐号
    Route::patch('account/status/{id}','Broker\BrokerController@accountStatus');//更新帐号状态

    Route::get('query/account','Broker\BrokerController@queryAccount');//获取所有后台帐号
    Route::post('add/role','Broker\BrokerController@addRole');//添加角色
    Route::get('query/role','Broker\BrokerController@queryRole');//查看所有角色

    Route::get('query/permission','Broker\BrokerController@queryPermission');//查看所有权限
    Route::get('gain/broker/permission/{id}','Broker\BrokerController@gainPermission');//查看子权限
    Route::post('del/permission','Broker\BrokerController@delPermission');//删除一个权限
    Route::post('update/pname','Broker\BrokerController@updatePname');//更新权限的名称


    Route::post('del/role','Broker\BrokerController@delRole');//删除一个角色
    Route::get('gain/role','Broker\BrokerController@gainRole');//获取所有角色

    //Route::post('all/role','Broker\BrokerController@addRoleScope');//获取所有角色

    Route::post('have/role','Broker\BrokerController@haveRole');//获取当前用户的角色名称
    Route::post('update/role','Broker\BrokerController@updateRole');//更新用户的角色


    Route::post('update/permission','Broker\BrokerController@updatePermission');//更新角色的权限
    Route::post('add/power','Broker\BrokerController@addPower');//添加权限
    Route::get('all/permission','Broker\BrokerController@allPermission');//获取所有权限名称
    Route::post('have/permission','Broker\BrokerController@havePermission');//获取当前角色的权限名称

    
    Route::get('power', function () {
        return view('broker.power');//权限管理
    })->name('power')->middleware('adminRoute');



    Route::get('role', function () {
        return view('broker.role');//角色管理
    })->name('role')->middleware('adminRoute');
});

Route::prefix('work')->group(function () {//工作管理

    Route::get('broker-list', function () {
        return view('work.broker-list');//经纪人列表
    })->name('work')->middleware('adminRoute');

    Route::get('broker/list','Work\WorkController@brokerList');//获取经纪人列表
    Route::get('query/account/{account}','Work\WorkController@queryAccount');//查询经纪人

    Route::post('info','Work\WorkController@info');//经纪人工作详情
    Route::post('broker/record','Work\WorkController@BrokerRecord');//经纪人时间段内提交的数据
    
    
});

Route::prefix('clean')->group(function () {//扫楼记录管理

    Route::get('all-record', function () {
        return view('clean.all-record');//查看全部扫楼记录
    })->name('clean')->middleware('adminRoute');

    Route::get('gain/clean','Clean\CleanController@gainClean');

    Route::get('house/tenant','Clean\CleanController@houseTenant');//搜索扫楼信息

    Route::get('get/permission','Clean\CleanController@getPermission');//是否有导出权限

    Route::get('search/clean','Clean\CleanController@searchClean');//按楼盘查看数据变更
    
    Route::get('record-change', function () {
        return view('clean.record-change');//按楼盘查看数据变更
    })->name('change')->middleware('adminRoute');
    Route::get('change/houses','Clean\CleanController@changeHouses');//按楼盘查看数据变更
});

Route::prefix('tenant')->group(function () {//租户管理

    Route::get('query', function () {
        return view('tenant.query');//租户查询
    })->name('tenant')->middleware('adminRoute');

    Route::get('query/tenant','Tenant\TenantController@queryTenant');//租户信息列表 
    Route::get('gain/info','Tenant\TenantController@gainInfo');//查询单个租户信息

    Route::post('del/tenant','Tenant\TenantController@delTenant');//删除租户信息
    Route::post('update/tenant','Tenant\TenantController@updateTenant');//更新租户信息
    Route::get('stop/date','Tenant\TenantController@stopDate');//合同快到期的租户

    Route::get('query/account','Tenant\TenantController@queryAccount');//转移租户
    Route::post('update/broker','Tenant\TenantController@updateBroker');//确定转移租户

    Route::get('get/permission','Clean\CleanController@getPermission');//是否有权限
    
    Route::get('manage', function () {
        return view('tenant.manage');//租户管理
    })->name('tenant')->middleware('adminRoute');
});


Route::prefix('parm')->group(function () {//经纪人管理

    Route::get('company', function () {
        return view('parm.company-type');//公司类型
    })->name('form')->middleware('adminRoute');

    Route::get('gain/company','Parm\CompanyTypeController@gainCompany');//获取公司类型信息
    Route::get('gain/company/type/{id}','Parm\CompanyTypeController@gainCompanyType');//获取公司类型
    Route::post('create/cname','Parm\CompanyTypeController@createName');//创建分类名称
    Route::post('update/cname','Parm\CompanyTypeController@updateName');//更新分类名称
    Route::post('del/cname','Parm\CompanyTypeController@delName');//删除分类

    Route::get('demand', function () {
        return view('parm.demand');//租户需求
    })->name('form')->middleware('adminRoute');
    Route::get('gain/demand','Parm\DemandController@gainDemand');//获取租户需求信息
    Route::get('gain/demand/type/{id}','Parm\DemandController@gainDemandType');//获取租户需求类型
    Route::post('create/name','Parm\DemandController@createName');//创建分类名称
    Route::post('update/name','Parm\DemandController@updateName');//更新分类名称
    Route::post('del/name','Parm\DemandController@delName');//删除分类

    Route::get('phone', function () {
        return view('parm.phone');//合同到期提醒手机号设置
    })->name('phone')->middleware('adminRoute');

    Route::post('update/phone','Parm\DemandController@updatePhone');//更新手机号
    Route::get('query/phone','Parm\DemandController@queryPhone');//
    
    Route::get('paytype', function () {
        return view('parm.paytype');//付款方式设置
    })->name('form')->middleware('adminRoute');

    Route::get('period', function () {
        return view('parm.period');//合同期限设置
    })->name('form')->middleware('adminRoute');
    Route::post('add/paytype','Parm\DemandController@addPaytype');//添加付款方式
    Route::get('gain/paytype','Parm\DemandController@gainPayType');//查看付款方式
    Route::post('del/paytype','Parm\DemandController@delPayType');//删除付款方式
    Route::post('edit/paytype','Parm\DemandController@editPayType');//编辑付款方式

    Route::post('add/period','Parm\DemandController@addPeriod');//添加合同期限
    Route::get('gain/period','Parm\DemandController@gainPeriod');//查看合同期限
    Route::post('del/period','Parm\DemandController@delPeriod');//删除合同期限
    Route::post('edit/period','Parm\DemandController@editPeriod');//编辑合同期限

    Route::get('protocol', function () {
        return view('parm.protocol');//用户协议
    })->name('form')->middleware('adminRoute');
    Route::post('create/protocol','Parm\DemandController@createProtocol');//创建用户协议
    Route::post('edit/protocol','Parm\DemandController@editProtocol');//修改用户协议
    Route::get('gain/protocol','Parm\DemandController@gainProtocol');//查看合同期限
    Route::post('del/period','Parm\DemandController@delPeriod');//删除合同期限
    Route::post('edit/period','Parm\DemandController@editPeriod');//编辑合同期限
});
