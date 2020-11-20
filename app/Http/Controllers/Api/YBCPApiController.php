<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class YBCPApiController extends Controller
{
    public $merchant= "byyl";//商户号
    public $signKey= "94D5BD8FCF4940CD";//key值
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

    public function memberCreate(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        if ($user->username != $request->member) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }

        $data= array();
        $data['member']= $request->member;
        $data['memberType'] = $request->memberType;
        $data['password'] = $request->password;
        $data['merchant'] = $this->merchant;
        $data['doubleList'] = "";
        $data['normalList'] = "";
        $data['timestamp'] = (int)(microtime(true)*1000);
        $data['sign']=md5('doubleList'.'member'.$data['member'].'memberType'.$data['memberType'].
        'merchant'.$data['merchant'].'normalList'.$data['normalList'].'password'.$data['password'].
                        'timestamp'.$data['timestamp'].$this->signKey);

        $type= array("Content-Type:application/json","User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

        $url=$request->url;
        //return http_build_query($data);
        $this->curlData($url,json_encode($data),$type);
    }

    public function memberLogin(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        if ($user->username != $request->member) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }

        $data= array();
        $data['member']= $request->member;
        $data['password'] = $request->password;
        $data['merchant'] = $this->merchant;
        $data['timestamp'] = (int)(microtime(true)*1000);
        $data['sign']=md5('member'.$data['member'].
        'merchant'.$data['merchant'].'password'.$data['password'].
                        'timestamp'.$data['timestamp'].$this->signKey);

        $type= array("Content-Type:application/x-www-form-urlencoded","User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

        $url=$request->url;
        $this->curlData($url,http_build_query($data),$type);
    }

    public function transferBalance(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        if ($user->username != $request->member) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }

        $data= array();
        $data['amount']= $request->amount;
        $data['member']= $request->member;
        $data['transferType'] = intval($request->transferType);
        $data['merchantAccount'] = $this->merchant;
        $data['notifyId']= $request->notifyId;
        $data['timestamp'] = (int)(microtime(true)*1000);
        $data['sign']=md5('amount'.$data['amount'].'member'.$data['member'].'merchantAccount'.$data['merchantAccount'].'notifyId'.$data['notifyId'].'timestamp'.$data['timestamp'].'transferType'.$data['transferType'].$this->signKey);

        $type= array("Content-Type:application/json","User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

        $url=$request->url;
        //return http_build_query($data);
        $this->curlData($url,json_encode($data),$type);
    }

    public function balanceQuery(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        if ($user->username != $request->member) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }

        $data= array();

        $data['member']= $request->member;

        $data['merchant'] = $this->merchant;

        $data['timestamp'] = (int)(microtime(true)*1000);
        $data['sign']=md5('member'.$data['member'].'merchant'.$data['merchant'].
                        'timestamp'.$data['timestamp'].$this->signKey);

        $type= array("Content-Type:application/json","User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

        $url=$request->url;
        $this->curlData($url,json_encode($data),$type);
    }

    public function balanceRecords(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user= JWTAuth::authenticate($request->token);
        if ($user->username != $request->member) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名错误',
            ], 200);
        }

        $data= array();

        $data['endTime']= $request->endTime?$request->endTime:'';
        $data['member']= $request->member;
        $data['merchant'] = $this->merchant;
        $data['notifyId']= $request->notifyId?$request->notifyId:'';
        $data['pageSize']= $request->pageSize?$request->pageSize:'';
        $data['pageNum']= $request->pageNum?$request->pageNum:'';
        $data['startTime']= $request->startTime?$request->startTime:'';
        $data['timestamp'] =(int)(microtime(true)*1000);
        $data['tradeType'] = $request->tradeType?$request->tradeType:'';
        $data['sign']=md5('endTime'.$data['endTime'].'member'.$data['member'].'merchant'.$data['merchant'].'notifyId'.$data['notifyId'].'pageNum'.$data['pageNum'].'pageSize'.$data['pageSize'].'startTime'.$data['startTime'].'timestamp'.$data['timestamp'].'tradeType'.$data['tradeType'].$this->signKey);

        $type= array("Content-Type:application/json","User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64)");

        $url=$request->url;
        $this->curlData($url,json_encode($data),$type);
    }

    public function ybcpRecord()
    {

        $date = date('Ym/d',time()-8*60*60);
        $fileName = intval(date('H',time()-8*60*60)) + 7;
        $fileName = $fileName.'.json';
        $url ='http://pull.shayexiang.com/'.$date.'/real/order/'.$fileName;

        $json_string = file_get_contents($url);
        if ($json_string != null) {
            $json_string = '['.str_replace('}','},',$json_string);
            $json = substr($json_string, 0, -2).']';

            $data = json_decode($json,true);
            DB::table('yb_lottery_record')->insert($data);
        }

    }
}
