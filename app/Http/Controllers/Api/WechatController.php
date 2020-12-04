<?php

namespace App\Http\Controllers\Api;

use App\Model\User;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class WechatController extends Controller
{
    public function wechat(Request $request)
    {
        ini_set("display_errors","On");
        error_reporting(E_ALL);

        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token); 
        if (!$user->account) {
            return response()->json(['msg' =>'未登陆', 'code' => -1]);
        }

        $config = [
            'app_id' =>env('WECHAT_API_ID'),
            'secret' => env('WECHAT_API_SECRET'),

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
 /*            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => __DIR__.'/wechat.log',
            ], */
        ];
        
       try {
            $app = Factory::miniProgram($config);
       
            $iv = $request->iv;
            $encryptedData = $request->encryptedData;
            $code= $request->code;
            $session = $app->auth->session((string)$code);
            
            if(empty($session)){
                return response()->json([
                'code' => 0,
                'msg' => '拉取失败，请重试',
                ], 200);
            }
            
            if(!array_key_exists('session_key',$session)){
                 return response()->json([
                'code' => 0,
                'msg' => '拉取失败，请重试',
                ], 200);
            }
        
        
            $decryptedData = $app->encryptor->decryptData($session['session_key'], $iv, $encryptedData);
    
            $data = array();
                $data['account'] =$user->account;
    
            if($decryptedData['phoneNumber']){
               $state=  User::where('account',"=",$user->account)->update(['account'=>$decryptedData['phoneNumber']]);
            }
    
       
            if($state){
                $phone = User::where('id',$user->id)->value('account');
                $data = array();
                $data['account'] =$phone;
                return response()->json([
                    'code' => 1,
                    'msg' => '成功',
                    'data' => $data
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 0,
                'msg' =>"输入错误",
            ],200);
        } 

    }

}
