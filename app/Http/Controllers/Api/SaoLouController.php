<?php

namespace App\Http\Controllers\Api;

use App\Model\User;
use App\Model\Clean;
use App\Model\Level;
use App\Model\Demand;
use App\Model\Tenant;
use App\Model\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;
use App\Http\Requests\RegisterAuthRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

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
                'code' => 0,
                'msg' =>"两次密码不一致",
            ],200);
        }

        $phoneCode = $request->account.$request->pcode;
        $num = Cache::get($phoneCode);

        if ($phoneCode != $num) {
            return response()->json([
                'code' => 0,
                'msg' =>"验证码不正确",
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
                'code' => 1,
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
                'code' => 0,
                'msg' =>"输入错误",
            ],200);
        }

        if ($request->password !== $request->rpassword) {
            return response()->json([
                'code' => 0,
                'msg' =>"两次密码不一致",
            ],200);
        }

        $phoneCode = $request->account.$request->pcode;
        $num = Cache::get($phoneCode);

        if ($phoneCode != $num) {
            return response()->json([
                'code' => 0,
                'msg' =>"验证码不正确",
            ],200);
        }

            $user = User::where('account',$request->account)->update([
                'password'=> bcrypt($request->password)
            ]);

            return response()->json([
                'code' => 1,
                'msg' =>"修改密码成功",
            ],200);
    }

    public function login(Request $request)
    {
        $input = $request->only('account', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名或者密码错误',
            ], 200);
        }


        return response()->json([
            'code' => 1,
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
                'code' => 1,
                'msg' => '用户退出成功'
            ],200);
        } catch (JWTException $exception) {
            return response()->json([
                'code' => 0,
                'msg' => '对不起，用户无法注销'
            ], 200);
        }
    }

    public function entering(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user = JWTAuth::authenticate($request->token);
        
        $month = $day = '+'.$request->pay_type.'month';
        $pay_time = date("Y-m-d",strtotime($month,strtotime($request->start_time)));

        $clean= new Clean;//扫楼记录表
 
        $clean->houses_info = $request->houses_info;//租户信息
        $clean->tenant_name = $request->tenant_name;//租户名称
        $clean->is_we_company = $request->is_we_company;//是否是我司租户
        $clean->company_type = $request->company_type;//公司类型
        $clean->tenant_user = $request->tenant_user;//联系人
        $clean->start_time = $request->start_time;//合同开始时间
        $clean->stop_time = $request->stop_time;//合同结束时间
        $clean->pay_type = $request->pay_type;//付款方式
        $clean->pay_time = $pay_time;//下次付款时间
        $clean->tenant_need = $request->tenant_need;//租户需求
        $clean->remark = $request->remark;//备注
        $clean->position = $request->position;//当前提交的位置
        $clean->broker_name = $user->name;//当前提交的经纪人的名称
        $clean->broker_phone = $user->account;//当前提交的经纪人手机号或者帐号
        $clean->position = $request->position;//当前提交的位置
        $clean->enclosure = $request->enclosre;//附件
        $clean->save();

        $tenant = new Tenant;

        $tenant->tenant_name = $request->tenant_name;//租户名称
        $tenant->is_we_company = $request->is_we_company;//是否是我司租户
        $tenant->company_type = $request->company_type;//公司类型
        $tenant->tenant_user = $request->tenant_user;//联系人
        $tenant->start_time = $request->start_time;//合同开始时间
        $tenant->stop_time = $request->stop_time;//合同结束时间
        $tenant->pay_type = $request->pay_type;//付款方式
        $tenant->pay_time = $pay_time;//下次付款时间
        $tenant->tenant_need = $request->tenant_need;//租户需求
        $tenant->remark = $request->remark;//备注
        $tenant->broker_name = $user->name;//当前提交的经纪人的名称
        $tenant->broker_phone = $user->account;//当前提交的经纪人手机号或者帐号
        $tenant->uid = $user->id;//当前提交人的id

        if ($clean->save()) {
            return response()->json([
                'code' => 1,
                'msg' => '成功'
            ], 200);
        }else{
            return response()->json([
                'code' => 0,
                'msg' => '提交失败'
            ], 200);
        }
    }

    public function houses(Request $request)//获取楼盘架构
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        if (isset($request->pid)) {
            $pid = $request->pid;
        }else{
            $pid = 0;
        }

        $user = JWTAuth::authenticate($request->token);
        if ($user->account) {
            $data= Level::where('parent_id',$pid)->get(['id','type_name']);
            return response()->json([
                'code' => 1,
                'msg' => '成功',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'code' => -1,
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
                'code' => 1,
                'msg' => '成功',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'code' => -1,
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
                'code' => 1,
                'msg' => '成功',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'code' => -1,
                'msg' => '请登陆'
            ], 200);
        }

    }

    public function uploadImg(Request $request)//图片上传
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token); 
        if (!$user->account) {
            return response()->json(['msg' =>'请登陆', 'code' => -1]);
        }

        $upload= new UploadController;
        $namePath= $upload->uploadImg($request->file('img'),'UserImg');//上传用户头像1,图片,2图片存放目录
        $namePath = $namePath;//图片访问地址
    
        if ($namePath) {
        /*     User::where('account',$user->account)->update([
                'head_img'=>$namePath
            ]); */
         
            return response()->json(['img_url' =>$namePath, 'code' => 201,'msg'=>'上传成功']);
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
            return response()->json(['msg' =>'请登陆', 'code' => -1]);
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
            'code' => 1,
            'msg' => '查询成功',
            'data'=> $data
        ], 200);
    }

    public function pcode(Request $request)
    {
        $phone = $request->account;
        $pcode = rand(1000,9999);
        $phoneCode = $phone.$pcode;

        Cache::put($phoneCode , $phoneCode,6000);
        $sms = app('easysms');
        try {
            $sms->send($phone, [
                'template' => 'SMS_171855867',
                'data' => [
                    'code' => $pcode
                ],    
            ]);
        } catch (NoGatewayAvailableException $exception) {
            $message = $exception->getException('aliyun')->getMessage();
            return response()->json([
                'code' => 0,
                'msg' => $message
            ], 200);
        }

        return response()->json([
            'code' => 1,
            'msg' => '发送成功',
           // 'pcode' => $pcode
        ], 200);

    }
    
    public function updateHead(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'img_url' => 'required',
        ]);

        $user = JWTAuth::authenticate($request->token); 

        $user->head_img = $request->img_url;
        
        if ($user->save()) {
            return response()->json([
                'code' => 1,
                'msg' => '修改成功',
               // 'pcode' => $pcode
            ], 200);
        }else{
            return response()->json([
                'code' => 0,
                'msg' => '修改失败'
            ], 200);
        }
    }

    public function paytype()
    {
        $paytype = DB::table('paytype')->get(['type_name','month']);

        if ($paytype) {
            return response()->json([
                'code' => 1,
                'msg' => '成功',
                'data' => $paytype
            ], 200);
        }else{
            return response()->json([
                'code' => 0,
                'msg' => '获取付款方式失败'
            ], 200);
        }
    }
}
