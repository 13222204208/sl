<?php

namespace App\Http\Controllers\Api;

use App\Model\VipRebate;
use App\Model\UserDetail;
use App\Model\Transaction;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VipApiController extends Controller
{
    public function vipGrade(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);

        $amount= Transaction::where('username',$user->username)->where('business_state',1)->where('business_type','存款')->sum('business_money');//查询用户存款额度

        $running= Transaction::where('username',$user->username)->where('business_state',1)->where('business_type','转账')->sum('business_money');//查询用户的流水

        $vip = 0;
//判断用户的vip等级
        if ($amount >= 500 && $running >= 3000) $vip = 1;
        if ($amount >= 2000 && $running >= 12000) $vip = 2;
        if ($amount >= 10000 && $running >= 60000) $vip = 3;
        if ($amount >= 50000 && $running >= 300000) $vip = 4;
        if ($amount >= 200000 && $running >= 1200000) $vip = 5;
        if ($amount >= 500000 && $running >= 3000000) $vip = 6;
        if ($amount >= 1200000 && $running >= 7200000) $vip = 7;
        if ($amount >= 3000000 && $running >= 18000000) $vip = 8;
        if ($amount >= 10000000 && $running >= 60000000) $vip = 9;
        if ($amount >= 30000000 && $running >= 180000000) $vip = 10;


        if ($vip > 0) {
            $state= Transaction::where('username',$user->username)->where('business_mode','vip'.$vip)->where('business_type','VIP升级礼金')->first();//查询升级礼金是否发放

            if ($state == null) {
                $cash_gift= VipRebate::where('vip',$vip)->value('cash_gift');//查询升级礼金额度
                $transaction = new Transaction;
                $transaction->order_num = 'cash_gift'.$user->username.time();
                $transaction->username = $user->username;
                $transaction->business_type = 'VIP升级礼金';
                $transaction->business_mode = 'vip'.$vip;
                $transaction->business_money = $cash_gift;
                $transaction->ask_time= date('Y-m-d H:i:s',time());
                $transaction->business_state = 1;
                $transaction->save();
                $money= UserDetail::where('username',$user->username)->first();
                $money->balance = $money->balance + $cash_gift;//添加升级礼金到余额
                $money->save();
            }

        }

        UserDetail::where('username',$user->username)->update(['vip'=>$vip]);
        return response()->json([
            'msg' => '成功',
            'amount' => $amount,
            'running'=> $running,
            'vip' => $vip,
            'code' => 200
        ],200);
    }

    public function redPacket(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);

        $y = date('Y',time());
        $m = date('m',time());
        $d = date('d',time());
        $time = $y.$m.$d;
        if ($time == $y.$m.'14') {//每月红包的发放日期,
            $money= UserDetail::where('username',$user->username)->first();//查询用户vip等级
            $vip = $money->vip;
            if ($vip > 0) {
                $state= Transaction::where('username',$user->username)->where('business_mode',$time.$vip)->where('business_type','VIP每月红包')->first();//查询用户本月是否已领取每月红包

                if ($state == null) {

                    $red_packet= VipRebate::where('vip',$vip)->value('red_packet');//查询vip的红包额度
                    $transaction = new Transaction;
                    $transaction->order_num = 'red_packet'.$user->username.time();
                    $transaction->username = $user->username;
                    $transaction->business_type = 'VIP每月红包';
                    $transaction->business_mode = $time.$vip;
                    $transaction->business_money = $red_packet;
                    $transaction->ask_time= date('Y-m-d H:i:s',time());
                    $transaction->business_state = 1;
                    $transaction->save();
                    $money= UserDetail::where('username',$user->username)->first();
                    $money->balance = $money->balance + $red_packet;//红包添加到余额
                    $money->save();

                    return response()->json([
                        'msg' => '每月红包发放成功',
                        'red_packet' => $red_packet,
                        'code' => 200
                    ],200);
                }else{
                    return response()->json([
                        'msg' => '已经发放',
                        'code' => 2001
                    ],200);
                }
            }else{
                return response()->json([
                    'msg' => '不是vip',
                    'code' => 2002
                ],200);
            }

        }else{
            return response()->json([
                'msg' => '不到发放日期',
                'code' => 2003
            ],200);
        }
    }
}
