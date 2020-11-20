<?php

namespace App\Http\Controllers\Api;

use App\Model\VipRebate;
use App\Model\UserDetail;
use App\Model\Transaction;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class PayApiController extends Controller
{
    public $partner= "1007741772";
    public $key= "hvlnsfxwazmpxedyne";

    public function curlData($url,$data,$type)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,$type);
        curl_setopt($ch, CURLOPT_URL, $url);//要访问的地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);//执行结果是否被返回，0是返回，1是不返回
        curl_setopt($ch, CURLOPT_POST, 1);// 发送一个常规的POST请求

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);//执行并获取数据
        curl_close($ch);
    }

    public function recharge(Request $request)//调用(9pay存款)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        $transaction= new Transaction;
        $transaction->order_num= $request->tradeNo;
        $transaction->username= $user->username;
        $transaction->business_type= '存款';
        $transaction->business_mode= $request->service;
        $transaction->business_money= $request->amount;
        $transaction->business_state = 0;
        $transaction->ask_time= date('Y-m-d H:i:s');
        $transaction->save();

        $money= UserDetail::where('username',$user->username)->first();
        $money->balance = $money->balance + $request->amount;
        $money->save();

        $data= array();
        $data['partner']= $this->partner;
        $data['service']= $request->service;
        $data['tradeNo']= $request->tradeNo;
        $data['amount']= $request->amount;
        $data['notifyUrl']= $request->notifyUrl;
        $data['resultType']= $request->resultType;
        $data['sign']= MD5('amount='.$data['amount'].'&notifyUrl='.$data['notifyUrl'].'&partner='.$data['partner'].'&resultType='.$data['resultType'].'&service='.$data['service'].'&tradeNo='.$data['tradeNo'].'&'.$this->key);

        $type= array("Content-Type:application/x-www-form-urlencoded","User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

        $url= $request->url;
        $this->curlData($url,http_build_query($data),$type);

    }

    public function agentPay(Request $request)//代付
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        if ($request->amount < 50) {
            return response()->json([
                'code' => 1005,
                'msg' =>"最低转帐50",
            ],200);
        }
        $user= JWTAuth::authenticate($request->token);

        $vip= UserDetail::where('username',$user->username)->value('vip');
        $time = $user->username.date('Ymd');
        $balance =$time.'money';
        Redis::incr($time);
        Redis::incrby($balance,$request->amount);
        $v= Redis::get($time);//今日提款次数
        $money= Redis::get($balance);//今日提款的总额

        if ($vip >0) {
            $data= VipRebate::where('vip',$vip)->get(['day_num','balance','min_transfer'])->toArray();
            if ($v > $data[0]['day_num'] || $money > $data[0]['balance'] || $request->amount < $data[0]['min_transfer']) {
                Redis::decr($time);
                Redis::decrby($balance,$request->amount);
                return response()->json([
                    'code' => 1003,
                    'msg' =>"超出今日提款次数或提款额度 ，最低转帐",
                ],200);
            }
        }


        if ($v > 5 || $money > 200000 ) {
            Redis::decr($time);
            Redis::decrby($balance,$request->amount);
            return response()->json([
                'code' => 1003,
                'msg' =>"超出今日提款次数或提款额度",
            ],200);
        }


        $data= array();
        $data['partner']= $this->partner;
        $data['service']= $request->service;
        $data['tradeNo']= $request->tradeNo;
        $data['bankCode']= $request->bankCode;
        $data['bankCardNo']= $request->bankCardNo;
        $data['bankCardholder']= $request->bankCardholder;
        $data['subsidiaryBank']= $request->subsidiaryBank;
        $data['subbranch']= $request->subbranch;
        $data['province']= $request->province;
        $data['city']= $request->city;
        $data['amount']= $request->amount;
        $data['notifyUrl']= $request->notifyUrl;
        $data['extra']= $request->extra?$request->extra:'noting';
        $data['sign']= MD5('amount='.$data['amount'].'&bankCardNo='.$data['bankCardNo'].'&bankCardholder='.$data['bankCardholder'].
        '&bankCode='.$data['bankCode'].'&city='.$data['city'].'&extra='.$data['extra'].'&notifyUrl='.$data['notifyUrl'].'&partner='.$data['partner'].'&province='.$data['province'].'&service='.$data['service'].'&subbranch='.$data['subbranch'].
        '&subsidiaryBank='.$data['subsidiaryBank'].'&tradeNo='.$data['tradeNo'].'&'.$this->key);

        $type= array("Content-Type:application/x-www-form-urlencoded","User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

        $url= $request->url;
        $this->curlData($url,http_build_query($data),$type);

    }

    public function orderQuery(Request $request)//订单查询
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        JWTAuth::authenticate($request->token);

        $data= array();
        $data['partner']= $this->partner;
        $data['service']= $request->service;
        $data['outTradeNo'] = $request->outTradeNo;
        $data['sign']= MD5('outTradeNo='.$data['outTradeNo'].'&partner='.$data['partner'].'&service='.$data['service'].'&'.$this->key);

        $type= array("Content-Type:application/x-www-form-urlencoded","User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

        $url= $request->url;
        $this->curlData($url,http_build_query($data),$type);

    }

    public function balanceQuery(Request $request)//余额查询
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        JWTAuth::authenticate($request->token);

        $data= array();
        $data['partner']= $this->partner;
        $data['service']= $request->service;
        $data['sign']= MD5('partner='.$data['partner'].'&service='.$data['service'].'&'.$this->key);

        $type= array("Content-Type:application/x-www-form-urlencoded","User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

        $url= $request->url;
        $this->curlData($url,http_build_query($data),$type);

    }

    public function notifyUrl(Request $request)//付款回调
    {

        Transaction::where('order_num',$request->outTradeNo)->update([
            'business_state'=>intval($request->status)
        ]);

        Log::info('statusStr.', ['outTradeNo'=>$request->outTradeNo,'statusStr'=>$request->statusStr,'amount'=>$request->amount,
            'status'=>$request->status
        ]);
        return 'success';
    }

    public function numBalance(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);

        $vip= UserDetail::where('username',$user->username)->value('vip');
        $time = $user->username.date('Ymd');
        $balance =$time.'money';

        if (Redis::exists($time)) {
            $v= Redis::get($time);//今日提款次数
            $day_num = 5 - $v;
        }else {
            $day_num = 5 ;
        }

        if (Redis::exists($balance)) {
            $money= Redis::get($balance);//今日提款的总额
            $balance = 200000 - intval($money);
        }else{
            $balance = 200000;
        }

        if ($vip >0) {
            $data= VipRebate::where('vip',$vip)->get(['day_num','balance','min_transfer'])->toArray();
            if (!isset($v)) {
                $day_num = $data[0]['day_num'];
            }else {
                $day_num = $data[0]['day_num'] - $v;
            }

            if (!isset($money)) {
                $balance = $data[0]['balance'];
            }else{
                $balance = intval($data[0]['balance']) - intval($balance);
            }

            return response()->json([
                'code' => 200,
                'day_num' => $day_num,//每日提款次数
                'balance' => $balance//每日提款额度
            ],200);
        }



        return response()->json([
            'code' => 200,
            'day_num' => $day_num,//每日提款次数
            'balance' => $balance//每日提款额度
        ],200);

    }
}
