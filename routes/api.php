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
    Route::get('data',function(){
       $data=  App\Model\Level::all();
       return response()->json([
        'code' => 200,
        'msg' =>"成功",
        'data' => $data
    ],200);
    });//注册
    Route::post('register','Api\UserController@register');//注册
    Route::post('login','Api\UserController@login');//登录
    Route::get('reg/code','Api\UserController@regCode');//注册验证码


    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('logout', 'Api\UserController@logout');//退出登录

        Route::post('newpass', 'Api\UserController@newpass');//修改密码
        Route::post('info', 'Api\UserController@update');//修改用户信息
        Route::post('user_head', 'Api\UserController@uploadUserHead');//上传头像
        Route::post('myActivity','Api\UserController@myActivity');//获取消息中心的活动
        Route::post('validatePasswd','Api\UserController@validatePasswd');//手势密码中验证登录密码
        Route::post('amount','Api\UserController@amount');//钱包余额

        Route::post('sendcode', 'Api\MailController@sendcode');//验证邮箱
        Route::post('bindmail','Api\MailController@bindmail');//绑定邮箱

        Route::post('feedback','Api\FeedBackController@feedback');//意见反馈
        Route::post('myFeedback','Api\FeedBackController@myFeedback');//获取意见反馈

        Route::post('add/card','Api\CardController@addCard');//添加银行卡
        Route::get('look/card','Api\CardController@lookCard');//添加银行卡
        Route::post('remove/card','Api\CardController@removeCard');//解除银行卡

    });
});

Route::middleware('cors')->prefix('content')->group(function (){
    Route::get('rotation','Api\ContentController@rotation');//首页轮播
    Route::get('activity','Api\ContentController@activity');//活动
    Route::get('default/head','Api\ContentController@defaultHead');//默认头像
    Route::get('support','Api\ContentController@support');//赞助
    Route::get('version','Api\ContentController@version');//app版本

    Route::get('platform/list','Api\PlatformController@platformList');//平台名称列表
    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('affiche','Api\ContentController@affiche');//公告
        Route::post('notice','Api\ContentController@notice');//获取消息中心的通知
        Route::get('platform/record','Api\PlatformController@platformRecord');//获取投注记录
        Route::get('transaction/record','Api\TransactionController@transactionRecord');//获取交易记录

        Route::post('vip/grade','Api\VipApiController@vipGrade');//获取vip等级

        Route::post('vip/redPacket','Api\VipApiController@redPacket');//领取每月红包，每个月的1号
    });
});

Route::middleware('cors')->prefix('game')->group(function (){

    Route::post('/tc/gameList','Api\TCApiController@gameList');//天成游戏列表
    Route::post('/tc/gameRecord','Api\TCApiController@gameRecord');//玩家电子游戏及真人投注详情
    Route::post('/tc/lotteryRecord','Api\TCApiController@lotteryRecord');//玩家天成彩票投注详情

    Route::post('/yb/gameList','Api\YBApiController@gameList');//亚博查询游戏列表

    Route::post('/yb/chessRecord','Api\YBApiController@chessRecord');//亚博棋牌投注记录请求链接在方法内更改
    Route::post('/yb/ybcpRecord','Api\YBCPApiController@ybcpRecord');//亚博彩票投注记录请求链接在方法内更改

    Route::post('/fast/betRecord','Api\FastApiController@betRecord');//获取平台投注记录

    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::post('/fast/register','Api\FastApiController@register');//注册
        Route::post('/fast/login','Api\FastApiController@login');//登入
        Route::post('/fast/balance','Api\FastApiController@balance');//获取会员钱包余额
        Route::post('/fast/transfer','Api\FastApiController@transfer');//转帐
        Route::post('/fast/checkTransfer','Api\FastApiController@checkTransfer');//检查转帐



        Route::post('/cancelCollect','Api\GameCollectController@cancelCollect');//取消收藏的游戏
        Route::post('/collectGame','Api\GameCollectController@collectGame');//用户收藏的游戏
        Route::post('/collect','Api\GameCollectController@collect');//游戏收藏

        Route::post('/tc/CRegister','Api\TCApiController@CRegister');//创建/确认玩家接口
        Route::post('/tc/launchGame','Api\TCApiController@launchGame');//启动游戏
        Route::post('/tc/balance','Api\TCApiController@balance');//获取余额
        Route::post('/tc/transfer','Api\TCApiController@transfer');//奖金转帐
        Route::post('/tc/checkTransaction','Api\TCApiController@checkTransaction');//检查交易状态

        Route::post('/yb/launchGame','Api\YBApiController@launchGame');//亚博登陆注册
        Route::post('/yb/transferIn','Api\YBApiController@transferIn');//亚博上分
        Route::post('/yb/transferOut','Api\YBApiController@transferOut');//亚博下分
        Route::post('/yb/queryOrderStatus','Api\YBApiController@queryOrderStatus');//亚博查询订单状态
        Route::post('/yb/queryBalance','Api\YBApiController@queryBalance');//亚博查询玩家额度
        Route::post('/yb/updateMemberPwd','Api\YBApiController@updateMemberPwd');//亚博修改玩家密码

        Route::post('/ybcp/memberCreate','Api\YBCPApiController@memberCreate');//亚博彩票玩家注册
        Route::post('/ybcp/memberLogin','Api\YBCPApiController@memberLogin');//亚博彩票玩家登陆
        Route::post('/ybcp/transferBalance','Api\YBCPApiController@transferBalance');//亚博彩票商户非免转会员钱包转帐接口
        Route::post('/ybcp/balanceQuery','Api\YBCPApiController@balanceQuery');//亚博彩票查询该商户的用户在本系统的账户余额
        Route::post('/ybcp/balanceRecords','Api\YBCPApiController@balanceRecords');//亚博彩票查询该商户的用户在平台与本系统的转账记录

    });

});

Route::middleware('cors')->prefix('9pay')->group(function (){
        Route::post('/notifyUrl','Api\PayApiController@notifyUrl');//9pay 回调地址
        Route::group(['middleware' => 'auth.jwt'], function () {
            Route::post('/recharge','Api\PayApiController@recharge');//9pay充值
            Route::post('/agentPay','Api\PayApiController@agentPay');//9pay代付
            Route::post('/orderQuery','Api\PayApiController@orderQuery');//9pay订单查询接口
            Route::post('/balanceQuery','Api\PayApiController@balanceQuery');//9pay余额查询接口
            Route::post('/numBalance','Api\PayApiController@numBalance');//用户的提款次数，额度

        });
});



