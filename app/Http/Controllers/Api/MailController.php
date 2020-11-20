<?php

namespace App\Http\Controllers\Api;

use App\Model\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendcode(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
        ]);

        $email= $request->email;
        $code = mt_rand(100000, 999999);
        $user = JWTAuth::authenticate($request->token);
  
        $detail= UserDetail::where('username',$user->username)->first();
        $msg ="验证码：" .$code;
     
        Mail::raw($msg , function(Message $message)use($email){
            // 邮件接收者
            $message->to($email);
            // 邮件主题
            $message->subject('博亿网');
        });

        $detail->email_code = $code;
        $detail->email = $email;
        $state= $detail->save();

        if ($state) {
            return response()->json([
                'code' => 200,
                'msg' =>"发送成功",
            ],200);
        }else {
            return response()->json([
                'code' => 0,
                'msg' =>"失败",
            ],200);
        }
        
    }

    public function bindmail(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'email_code'=>'required',
        ]);

        $user = JWTAuth::authenticate($request->token);
  
        $detail= UserDetail::where('username',$user->username)->first();
        if ($detail->email == $request->email && $detail->email_code == $request->email_code) {
            return response()->json([
                'code' => 200,
                'msg' =>"邮箱绑定成功",
            ],200);
        }else {
            $detail->email = "";
            
            $detail->save();
            return response()->json([
                'code' => 0,
                'msg' =>"验证码填写错误或邮箱与验证码不匹配,请重新验证",
            ],200);
        }
    }
}
