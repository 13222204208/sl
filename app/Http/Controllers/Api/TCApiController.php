<?php

namespace App\Http\Controllers\Api;

use App\Model\UserDetail;
use App\Model\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class TCApiController extends Controller
{
    public function __construct()
    {
        $this->url = 'http://www.connect6play.com/doBusiness.do';                    //天成API 连接
        $this->merchant_code = 'byylcny';        //代理商号
        $this->desKey = 'ZADKwrWZ';                //加密金钥
        $this->signKey = 'tw3947BNYH3Y1pn9';                //加密签名档
        $this->currency = 'CNY';                         //币别
    }

    public function curlData($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-type: application/x-www-form-urlencoded;charset=UTF-8"
        ));
        curl_setopt($ch, CURLOPT_URL, $url); //要访问的地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //执行结果是否被返回，0是返回，1是不返回
        curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的POST请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $res = curl_exec($ch); //执行并获取数据
        curl_close($ch);
        return $res;
    }

    /**
     * 组建en json参数数组
     * @param $plainText
     * @param $key
     * @return string
     */
    public function encryptText($plainText, $key)
    {;

        $encText = openssl_encrypt($plainText, 'DES-ECB', $key, OPENSSL_RAW_DATA);
        return base64_encode($encText);
    }


    public function send_require($sendParams)
    {
        $params =  $this->encryptText(json_encode($sendParams), $this->desKey);

        $sd = $params . $this->signKey; //echo $sd;exit;

        $sign = hash('sha256', $sd); //echo $sign;exit;

        $data = array('merchant_code' => $this->merchant_code, 'params' => $params, 'sign' => $sign);

        $res = $this->curlData($this->url, $data);
        return $res;
    }


    public function CRegister(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        if ($user->username != $request->username) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }

        $data = array();
        $data['method'] = "cm";
        $data['username'] = $request->username;
        $data['password'] = $request->password;
        $data['currency'] = $this->currency;

        $result = $this->send_require($data);
        return $result;
    }

    public function gameList(Request $request)
    {
        $data = array();
        $data['method'] = "tgl";
        $data['language'] = "ZH_CN";
        $data['product_type'] = $request->product_type;
        $data['platform'] = $request->platform;
        $data['client_type'] = $request->client_type;
        $data['game_type'] = $request->game_type;
        if ($request->page) {
            $data['page'] = $request->page;
        }
        if ($request->page_size) {
            $data['page_size'] = $request->page_size;
        }

        $result = $this->send_require($data);
        return $result;
    }

    public function launchGame(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        if ($user->username != $request->username) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }

        $data = array();
        $data['method'] = "lg";
        $data['username'] = $request->username;
        $data['product_type'] = $request->product_type;
        if ($request->platform) {
            $data['platform'] = $request->platform;
        }
        $data['game_mode'] = $request->game_mode;
        $data['game_code'] = $request->game_code;

        if ($request->view) {
            $data['view'] = $request->view;
        }

        if ($request->back_url) {
            $data['back_url'] = $request->back_url;
        }

        if ($request->lottery_bet_mode) {
            $data['lottery_bet_mode'] = $request->lottery_bet_mode;
        }

        if ($request->language) {
            $data['language'] = $request->language;
        }

        if ($request->series) {
            $data['series'] = $request->series;
        }
        //return response()->json($data);
        $result = $this->send_require($data);
        return $result;
    }

    public function balance(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        if ($user->username != $request->username) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }

        $data = array();
        $data['method'] = "gb";
        $data['username'] = $request->username;
        $data['product_type'] = $request->product_type;

        $result = $this->send_require($data);
        return $result;
    }

    public function transfer(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        if ($user->username != $request->username) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }

        $data = array();
        $data['method'] = "ft";
        $data['username'] = $request->username;
        $data['product_type'] = $request->product_type;
        $data['fund_type'] = $request->fund_type;
        $data['amount'] = $request->amount;
        $data['reference_no'] = $request->reference_no;

        //转帐
        $money = UserDetail::where('username', $user->username)->first();
        if ($request->fund_type == 1) {
            if ($money->balance < $request->amount) {
                return response()->json([
                    'code' => 1001,
                    'msg' => '金额不足',
                ], 200);
            } else {
                $money->balance = $money->balance - $request->amount;
            }
        }

        if ($request->TransType == 2) {
            $money->balance = $money->balance + $request->amount;
        }


        $result = $this->send_require($data);
        $state = json_decode($result, true);
        //Log::info('arr.', ['tc'=>$state]);
        if ($state['status'] === 0) {
            $money->save();

            $transaction = new Transaction;
            $transaction->order_num = $request->reference_no;
            $transaction->username = $user->username;
            $transaction->business_type = '转账';
            $transaction->business_mode = $request->fund_type;
            $transaction->business_money = $request->amount;
            if ($request->business_game) {
                $transaction->business_game = $request->business_game;
            }
            $transaction->ask_time = date('Y-m-d H:i:s');
            $transaction->business_state = 1;
            $transaction->save();
        }

        return $result;
    }

    public function checkTransaction(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        if ($user->username != $request->username) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }

        $data = array();
        $data['method'] = "cs";
        $data['product_type'] = $request->product_type;
        $data['ref_no'] = $request->ref_no;

        $result = $this->send_require($data);
        return $result;
    }

    public function gameRecord(Request $request)
    {
        $game= array('pvpbd','bd');


        $time= date('YmdHi',time()-15*60);

        $num= intval(substr($time,10));
        if ($num >= 0 && $num < 15) $batch_name = substr($time,0,-2).'00';
        if ($num >= 15 && $num < 30) $batch_name = substr($time,0,-2).'15';
        if ($num >= 30 && $num < 45) $batch_name = substr($time,0,-2).'30';
        if ($num >= 45 && $num < 60) $batch_name = substr($time,0,-2).'45';
        $data = array();
        for ($i = 0; $i < count($game); $i++) {

            $data['method'] = $game[$i];
            $data['batch_name'] = $batch_name;
            $result = $this->send_require($data);

            $record = json_decode($result, true);
            if ($record['details'] != null) {
                $len = count($record['details']);
                if ($data['method'] == 'pvpbd') {
                    for ($i=0; $i < $len; $i++) {
                        $record['details'][$i]['additionalInfo']= json_encode($record['details'][$i]['additionalInfo']);
                    }
                }

                if ($record['details'] != null && $record['status'] === 0) {
                    $tableName = 'tc_'.$data['method'].'_record';//拼接数据表名,插入数据
                    DB::table($tableName)->insert($record['details']);
                }
            }

        }
    }

    public function lotteryRecord()
    {
        $user = 'byylcny';
        $pwd = 'a123456';
        $time= date('Ymd',time());

        $conn = ftp_connect("123.51.167.66") or die("Could not connect");
        ftp_login($conn,$user,$pwd);
        ftp_pasv($conn,TRUE);
        ftp_chdir($conn,"/ELOTTO/SETTLED/$time/");
         $fileName= ftp_nlist($conn,".");
         $fileName = array_pop($fileName);
         $url= "ftp://$user:$pwd@123.51.167.66/ELOTTO/SETTLED/".$time.'/'.$fileName;

        $data= file($url);
        $array = json_decode($data[0],true);
        if ($array['list'] != null) {
            DB::table('tc_lottery_record')->insert($array['list']);
            return 'ok';
        }


    }
}
