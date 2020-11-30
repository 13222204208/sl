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


    public function newpass(Request $request)//忘记密码
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

            if ($user) {
                return response()->json([
                    'code' => 1,
                    'msg' =>"修改密码成功",
                ],200);
            }else{
                return response()->json([
                    'code' => 0,
                    'msg' => '用户名不存在',
                ], 200);
            }

    }

    public function login(Request $request)
    {
        $input = $request->only('account', 'password');
      
        $jwt_token = null;
 
        if (! $jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名或者密码错误',
            ], 200);
        }

        $user = User::where('account',$request->account)->first();
        $data = array();

        if(!$permission= substr(strrchr($user->branch, ","), 1)){
            $permission = $user->branch;
        }

        $data['account'] = $user->account;
        $data['branch'] = $user->branch;
        $data['name'] = $user->name;
        $data['role'] =  $user->getRoleNames();
        $data['permission'] =  $permission;
        $data['token']= $jwt_token;
        $data['img_url'] = $user->head_img;
        return response()->json([
            'code' => 1,
            'msg' =>"成功",
            'data' => $data
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
        
        $month = '+'.$request->pay_type.'month';
        $pay_time = date("Y-m-d",strtotime($month,strtotime($request->start_time)));

        if ($request->has('enclosre')) { 
            $upload= new UploadController;
            $images = $request->file('enclosre');
            $pathUrls = [];
            if (is_array($images)) {
                foreach($images as $key=>$v)
                {
                    $namePath= $upload->uploadImg($images[$key],'saolouImg');
                    $enclosre = $namePath;
                    array_push($pathUrls,$enclosre);
    
                }
            }else {
                $namePath= $upload->uploadImg($images,'saolouImg');
                    $enclosre = $namePath;
                    array_push($pathUrls,$enclosre);
            }

            
            if (!$namePath) {
                return response()->json(['msg' =>'图片上传失败', 'code' => 0]);
            }
            $pathUrls = implode(',',$pathUrls);
           //return $pathUrls;
      
        }

        if($request->is_we_company == 1){
            $is_we_company ="是";
        }else{
            $is_we_company ="否";
        }

        $clean= new Clean;//扫楼记录表
        $clean->houses_name = $request->houses_name;//楼盘名称
        $clean->houses_info = $request->houses_info;//租户信息
        $clean->houses_num = $request->houses_num;//房间号
        $clean->property_type = $request->property_type;//物业类型
        $clean->tenant_name = $request->tenant_name;//租户名称
        $clean->is_we_company = $is_we_company;//是否是我司租户
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
        $clean->enclosure = $pathUrls;//附件扫楼记录时上传的图片
        $clean->save();

        $tenant = new Tenant;

        $tenant->houses_name = $request->houses_name;//楼盘名称
        $tenant->tenant_name = $request->tenant_name;//租户名称
        $tenant->tenant_address = $request->houses_info;//租户地址
        $tenant->property_type = $request->property_type;//物业类型
        $tenant->is_we_company = $is_we_company;//是否是我司租户
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
        $tenant->save();//保存租户表信息

        DB::table('houses')->insertGetId([
            'houses_num'=> $request->houses_info,//楼盘信息
            'map' => $request->position//当前提交的位置
        ]);

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

       

        $user = JWTAuth::authenticate($request->token);
        if ($user->account) {
            $lpid = $request->lpid;
            $data= level::where('lpid',$lpid)->get()->toTree();
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
         
            return response()->json(['img_url' =>$namePath, 'code' => 1,'msg'=>'上传成功']);
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

        $size = 20;
        if($request->size){
            $size = $request->size;
        }

        $page = 0;
        if($request->page){
            $page = ($request->page -1)*$size;
        }

        if (isset($request->houses_name)) {
            $data= DB::table('clean')->where('uid',intval($user->id))->where('houses_name','like','%'.$request->houses_name.'%')->where('created_at','>=',$start_time)->where('created_at','<=',$stop_time)->skip($page)->take($size)->get();
        }else{
            $data= DB::table('clean')->where('uid',intval($user->id))->where('created_at','>=',$start_time)->where('created_at','<=',$stop_time)->skip($page)->take($size)->get();
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
