<?php

namespace App\Http\Controllers\Api;

use App\Model\User;
use App\Model\Clean;
use App\Model\Level;
use App\Model\Demand;
use App\Model\Company;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;
use App\Http\Requests\RegisterAuthRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

class SaoLouController extends Controller
{
    public $loginAfterSignUp = true;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterAuthRequest $request)
    { 

        if ($request->password !== $request->rpassword) {
            return response()->json([
                'code' => 101,
                'msg' =>"两次密码不一致",
            ],200);
        }


            $user = new User;
            $user->account = $request->account;
            $user->password = bcrypt($request->password);
  
            $user->save();//保存注册用户

   

            if ($this->loginAfterSignUp) {
                return $this->login($request);//注册完成后直接登陆
            }

            return response()->json([
                'code' => 201,
                'msg' =>"注册成功",
                'data' => $user
            ],200);
    }

    public function newpass(Request $request)
    {
        try {
            $request->validate([
                'account' => 'required|min:11|max:11',
                'password' => 'required|min:6|max:30',
            ]); 
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 102,
                'msg' =>"输入错误",
            ],200);
        }

        if ($request->password !== $request->rpassword) {
            return response()->json([
                'code' => 101,
                'msg' =>"两次密码不一致",
            ],200);
        }


            $user = User::where('account',$request->account)->update([
                'password'=> bcrypt($request->password)
            ]);

            return response()->json([
                'code' => 201,
                'msg' =>"修改密码成功",
            ],200);
    }

    public function login(Request $request)
    {
        $input = $request->only('account', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'code' => 103,
                'msg' => '用户名或者密码错误',
            ], 200);
        }


        return response()->json([
            'code' => 201,
            'msg' =>"成功",
            'account' => $request->account,
            'token' => $jwt_token,
        ],200);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'code' => 201,
                'msg' => '用户退出成功'
            ],200);
        } catch (JWTException $exception) {
            return response()->json([
                'code' => 0,
                'msg' => '对不起，用户无法注销'
            ], 200);
        }
    }

    public function houses(Request $request)//获取楼盘架构
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        if (isset($request->id)) {
            $pid = $request->id;
        }else{
            $pid = 0;
        }

        $user = JWTAuth::authenticate($request->token);
        if ($user->account) {
            $data= Level::where('parent_id',$pid)->get(['id','type_name']);
            return response()->json([
                'code' => 201,
                'msg' => '成功',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'code' => 301,
                'msg' => '请登陆'
            ], 200);
        }

    }

    public function company(Request $request)//获取公司类型
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        if (isset($request->id)) {
            $pid = $request->id;
        }else{
            $pid = 0;
        }

        $user = JWTAuth::authenticate($request->token);

        if ($user->account) {
            $data= Company::where('parent_id',$pid)->get(['id','type_name']);
            return response()->json([
                'code' => 201,
                'msg' => '成功',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'code' => 301,
                'msg' => '请登陆'
            ], 200);
        }

    }

    public function demand(Request $request)//获取租户需求
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        if (isset($request->id)) {
            $pid = $request->id;
        }else{
            $pid = 0;
        }

        $user = JWTAuth::authenticate($request->token);

        if ($user->account) {
            $data= Demand::where('parent_id',$pid)->get(['id','type_name']);
            return response()->json([
                'code' => 201,
                'msg' => '成功',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'code' => 301,
                'msg' => '请登陆'
            ], 200);
        }

    }

    public function headImg(Request $request)//用户头像上传
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token); 
        if (!$user->account) {
            return response()->json(['msg' =>'请登陆', 'code' => 301]);
        }

        $upload= new UploadController;
        $namePath= $upload->uploadImg($request->file('head_img'),'UserHeadImg');//上传用户头像1,图片,2图片存放目录
        $namePath = 'http://'.$_SERVER['HTTP_HOST'].'/'.$namePath;//图片访问地址
    
        if ($namePath) {
            User::where('account',$user->account)->update([
                'head_img'=>$namePath
            ]);
         
            return response()->json(['head_img' =>$namePath, 'code' => 201,'msg'=>'上传成功']);
        } else {
            return response()->json(['msg' =>'上传失败', 'code' => 0]);
        }
    }

    public function slRecord(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token); 
        if (!$user->account) {
            return response()->json(['msg' =>'请登陆', 'code' => 301]);
        }

        if (isset($request->start_time)) {
            $start_time = $request->start_time;
        }else{
            $start_time = "2020-11-16 15:30:59";
        }

        if (isset($request->stop_time)) {
            $stop_time = $request->stop_time;
        }else{
            $stop_time = date('Y-m-d H:i:s');
        }

        if (isset($request->houses_name)) {
            $data= Clean::where('houses_name',$request->houses_name)->where('created_at','>=',$start_time)->where('created_at','<=',$stop_time)->get();
        }else{
            $data= Clean::where('created_at','>=',$start_time)->where('created_at','<=',$stop_time)->get();
        }

        return response()->json([
            'code' => 201,
            'msg' => '查询成功',
            'data'=> $data
        ], 200);
    }
}
