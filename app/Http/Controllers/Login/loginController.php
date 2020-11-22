<?php

namespace App\Http\Controllers\Login;

use App\Model\User;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\Controller;

class loginController extends Controller
{
    public function login(Request $request)
    { 
        if ($request->ajax()) {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'captcha'  => 'required'
            ]);

            if (strtolower($request->captcha) != strtolower(session('piccode'))) {
                return response()->json(['status'=>404,'data'=>session('piccode')]);
            }

            $account_num= $request->username;
        
            $user = User::where('account', $account_num)->first();
        
            if (!$user || decrypt($user->password) != $request->password) {
                return response()->json(['status'=>403]);
            }
            session(['nickname' => $user->name]); 
            session(['id' => $user->id]); 
        
            $response = [
                'status'=>200
            ];
        
            return response($response);
        }
    }

    public function adminLogin()
    {
        $builder = new CaptchaBuilder;
        $builder->build();
        //$code = $builder->inline();  //获取图形验证码的url
        session()->put('piccode', $builder->getPhrase());  //将图形验证码的值写入到session中
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }
}
