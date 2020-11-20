<?php
namespace App\Http\Controllers\Api;

use App\Model\FastRecord;
use App\Model\UserDetail;
use App\Model\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class FastApiController extends Controller
{
    public $apiKey= 'testbyyl2020720';//商家apikey 奥奇平台
    public $apiSecret = 'zocGNgmOkQTkseY5c3TnqdzGi1fYGoqp3rkbak1MGYiBa97am3';//私匙

    public function curlData($url,$data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_URL, $url);//要访问的地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//执行结果是否被返回，0是返回，1是不返回
        curl_setopt($ch, CURLOPT_POST, 1);// 发送一个常规的POST请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $res= curl_exec($ch);//执行并获取数据
        curl_close($ch);
        return $res;
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        if ($user->username != $request->MemberAccount) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }
        $data = array();
        $data['ApiKey']= $request->ApiKey;
        $data['Timestamp'] = intval($request->Timestamp);
        $data['Game'] = $request->Game;
        $data['MemberAccount'] = $request->MemberAccount;
        $data['MemberPassword'] = $request->MemberPassword;
        $data['Hash'] = $request->Hash;
        $data = json_encode($data);
        $url = $request->url;

        $result= $this->curlData($url,$data);
        return $result;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        if ($user->username != $request->MemberAccount) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }
        $data = array();
        $data['ApiKey']= $request->ApiKey;
        $data['Timestamp'] = intval($request->Timestamp);
        $data['Game'] = $request->Game;
        $data['MemberAccount'] = $request->MemberAccount;
        $data['MemberPassword'] = $request->MemberPassword;
        $data['GameCode'] = $request->GameCode;
        $data['UserIP'] =  $request->UserIP;
        $data['DeviceType']= intval($request->DeviceType);
        $data['IsTrial'] = intval($request->IsTrial);
        $data['Hash'] = $request->Hash;
        $data = json_encode($data);
        $url = $request->url;

        $result= $this->curlData($url,$data);
        return $result;

    }

    public function balance(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        if ($user->username != $request->MemberAccount) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }
        $data = array();
        $data['ApiKey']= $request->ApiKey;
        $data['Timestamp'] = intval($request->Timestamp);
        $data['Game'] = $request->Game;
        $data['MemberAccount'] = $request->MemberAccount;
        $data['MemberPassword'] = $request->MemberPassword;
        $data['Hash'] = $request->Hash;
        $data = json_encode($data);
        $url = $request->url;

        $result= $this->curlData($url,$data);
        return $result;

    }

    public function transfer(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        if ($user->username != $request->MemberAccount) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }
        $data = array();
        $data['ApiKey']= $request->ApiKey;
        $data['Timestamp'] = intval($request->Timestamp);
        $data['Game'] = $request->Game;
        $data['MemberAccount'] = $request->MemberAccount;
        $data['MemberPassword'] = $request->MemberPassword;
        $data['TransType'] = $request->TransType;
        $data['Amount'] = $request->Amount;
        $data['OrderNo'] = $request->OrderNo;
        $data['Hash'] = $request->Hash;
        $data = json_encode($data);
        $url = $request->url;

            //转帐
            $money= UserDetail::where('username',$user->username)->first();
            if ($request->TransType == 'Deposit') {
                if ($money->balance < $request->Amount) {
                    return response()->json([
                        'code' => 1001,
                        'msg' => '金额不足',
                    ], 200);
                }else {
                    $money->balance = $money->balance - $request->Amount;
                }
            }

            if ($request->TransType == 'Withdraw') {
                $money->balance = $money->balance + $request->Amount;
            }

        $result= $this->curlData($url,$data);
        $res= json_decode($result,true);
       // Log::info('arr.', ['s'=>$resa['IsSuccess']]);
        if ($res['IsSuccess'] === true) {

            $money->save();

            $transaction= new Transaction;
            $transaction->order_num= $request->OrderNo;
            $transaction->username= $user->username;
            $transaction->business_type= '转账';
            $transaction->business_mode= $request->TransType;
            $transaction->business_money= $request->Amount;
            if ($request->business_game) {
                $transaction->business_game = $request->business_game;
            }

            $transaction->ask_time= date('Y-m-d H:i:s');
            $transaction->business_state = 1;
            $transaction->save();
        }
        return $result;

    }

    public function checkTransfer(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        if ($user->username != $request->MemberAccount) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }
        $data = array();
        $data['ApiKey']= $request->ApiKey;
        $data['Timestamp'] = intval($request->Timestamp);
        $data['Game'] = $request->Game;
        $data['MemberAccount'] = $request->MemberAccount;
        $data['MemberPassword'] = $request->MemberPassword;
        $data['OrderNo'] = $request->OrderNo;
        $data['Hash'] = $request->Hash;
        $data = json_encode($data);
        $url = $request->url;

        $result= $this->curlData($url,$data);
        return $result;

    }

    public function betRecord()
    {
        $start= time()-600;
        $StartDate= date("Y/m/d H:i:s",$start);
        $EndDate = date("Y/m/d H:i:s");
        $data = array();
        $data['ApiKey']= $this->apiKey;
        $data['Timestamp'] = time();

        $data['StartDate'] = $StartDate;
        $data['EndDate'] = $EndDate;


        $url = 'http://api.test.fastapi2020.com:6080/Api/Game/BetRecord';//奥奇api链接

        $game= array('AG','BBIN','OGPlus','AllBet','EG','WM','AVIA','IMSB','LC','VR','ThreeSing','SABA');

        for ($i=0; $i < count($game); $i++) {
            $data['Game'] = $game[$i];
            $data['Hash'] = MD5($this->apiKey.$data['Game'].$data['StartDate'].$data['EndDate'].$this->apiSecret.$data['Timestamp']);
            $jsonData = json_encode($data);

            $result= $this->curlData($url,$jsonData);
            $record= json_decode($result,true);

            if ( $record['Data'] != null && $record['Code']===0) {
                $tableName = 'aq_'.strtolower($data['Game']).'_record';//拼接数据表名,插入数据
                DB::table($tableName)->insert($record['Data']);
            }
        }

    }
}
