<?php

namespace App\Http\Controllers\Api;

use App\Model\User;
use App\Model\Clean;
use App\Model\House;
use App\Model\Level;
use App\Model\Demand;
use App\Model\Tenant;
use App\Model\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\HouseRequest;
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

    public function entering(HouseRequest $request)
    {

        $user = JWTAuth::authenticate($request->token);
        
        $month = '+'.$request->pay_type.'month';
        $pay_time = date("Y-m-d",strtotime($month,strtotime($request->start_time)));

      
        $m = '+'.$request->contract_period.'month';
        $contract_period = date("Y-m-d",strtotime($m,strtotime($request->start_time)));//由合同期限得出合同到期时间
       //return $request->enclosure;
        $house = House::where('houses_name',$request->houses_name)->get();
        $property_type=$house[0]->property_type;//物业类型待更改

       

        $clean= new Clean;//扫楼记录表
        $clean->houses_name = $request->houses_name;//楼盘名称
        $clean->houses_info = $request->houses_info;//租户信息
        $clean->houses_num = $request->houses_num;//房间号
        $clean->property_type = $property_type;//物业类型

        $clean->business_area = $house[0]->business_area;//商圈
        $clean->tenant_name = $request->tenant_name;//租户名称
        $clean->is_we_company = $request->is_we_company;//是否是我司租户

        if ($request->company_type) {
            $clean->company_type = $request->company_type;//公司类型
        }
        $clean->tenant_user = $request->tenant_user;//联系人
        $clean->start_time = $request->start_time;//合同开始时间
        $clean->contract_period = $contract_period ;//合同期限多少个月
        $clean->stop_time = $contract_period;//合同结束时间
        $clean->pay_type = $request->pay_type;//付款方式
        $clean->pay_time = $pay_time;//下次付款时间

        if ($request->tenant_need) {
            $clean->tenant_need = $request->tenant_need;//租户需求
        }

        if ($request->remark) {
            $clean->remark = $request->remark;//备注
        }

        $clean->position = $request->position;//当前提交的位置
        $clean->broker_name = $user->name;//当前提交的经纪人的名称
        $clean->broker_phone = $user->account;//当前提交的经纪人手机号或者帐号
        if ($request->enclosure) {
            $clean->enclosure = $request->enclosure;//附件扫楼记录时上传的图片
        }
        $clean->uid = $user->id;//当前提交人的id
        $clean->save();

        $tenant = new Tenant; 

        $tenant->houses_name = $request->houses_name;//楼盘名称
        $tenant->tenant_name = $request->tenant_name;//租户名称
        $tenant->houses_num = $request->houses_num;//房间号
        $tenant->houses_info = $request->houses_info;//租户信息
        $tenant->business_area = $house[0]->business_area;//商圈

        $tenant->property_type = $property_type;//物业类型
        $tenant->is_we_company = $request->is_we_company;//是否是我司租户
        $tenant->company_type = $request->company_type;//公司类型
        $tenant->tenant_user = $request->tenant_user;//联系人
        $tenant->start_time = $request->start_time;//合同开始时间
        $tenant->stop_time = $contract_period;//合同结束时间
        $tenant->contract_period = $request->contract_period;//合同期限多少个月
        $tenant->pay_type = $request->pay_type;//付款方式
        $tenant->pay_time = $pay_time;//下次付款时间

        if ($request->tenant_need) {
            $tenant->tenant_need = $request->tenant_need;//租户需求

        }

        if ($request->remark) {
            $tenant->remark = $request->remark;//备注
        }
        $tenant->broker_name = $user->name;//当前提交的经纪人的名称
        $tenant->broker_phone = $user->account;//当前提交的经纪人手机号或者帐号
        $tenant->uid = $user->id;//当前提交人的id
        $tenant->save();//保存租户表信息



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
            $data = Level::where('parent_id',$lpid)->get(['type_name','id']);
   
             $arr= array();

             $house = '';
             if($request->has('cid')){
                 $d= Level::defaultOrder()->ancestorsAndSelf($request->cid);
               
                 foreach($d as $h){
                     $house .= $h['type_name'].'>';
                 }
                 $arr['house_info'] = $house;


             }else{
                $d= Level::defaultOrder()->ancestorsAndSelf($request->lpid);
               
                foreach($d as $h){
                    $house .= $h['type_name'].'>';
                }
             }

             $hnum = Clean::where('houses_info',$house)->get('houses_num');
             $farr = array();
             foreach($hnum as  $fj){
                $farr[] = $fj['houses_num'];
             }

            foreach($data as $k=> $key){
                $arr['data'][] = $key;
              $d = Level::where('parent_id',$key['id'])->get(['type_name','id']);
              if(empty($d[0])){
                $arr['data'][$k]['num'] = 1;//判断当前的层级，1 为最后一层，2 为倒数第二层
                if(in_array($arr['data'][$k]['type_name'],$farr)){
                    $arr['data'][$k]['have'] = 1;
                }else{
                    $arr['data'][$k]['have'] = 0;//判断房间号是否已经录入过
                }
                }else{
                    $arr['data'][$k]['num'] = 0;
                    $st = Level::where('parent_id',$d[0]['id'])->get(['type_name','id']);
                    if(empty($st[0])){
                        $arr['data'][$k]['num'] = 2;
                    }
                }
                
            }



            return response()->json([
                'code' => 1,
                'msg' => '成功',
                'data' => $arr
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

   

        $user = JWTAuth::authenticate($request->token);

        if ($user->account) {
            $data= Company::get(['id','type_name']);
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

        $user = JWTAuth::authenticate($request->token);

        if ($user->account) {
            $data= Demand::get()->toTree();;
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
            $data= DB::table('clean')->where('uid',intval($user->id))->where('houses_name','like','%'.$request->houses_name.'%')->where('created_at','>=',$start_time)->where('created_at','<=',$stop_time)->skip($page)->take($size)->get(['houses_name','houses_info','tenant_name','created_at','id']);
        }else{
            $data= DB::table('clean')->where('uid',intval($user->id))->where('created_at','>=',$start_time)->where('created_at','<=',$stop_time)->skip($page)->take($size)->get(['houses_name','houses_info','tenant_name','created_at','id']);
        }

        if($request->has('slid')){
            $data= DB::table('clean')->where('uid',intval($user->id))->where('id',intval($request->slid))->get();
            $payType = DB::table('paytype')->where('id',$data[0]->pay_type)->get(['id','type_name','month']);//付款方式 
            $cPeriod = DB::table('period')->where('id',intval($data[0]->contract_period))->get(['id','type_name','month']);//合同期限
            $companyType = DB::table('company_type')->where('id',intval($data[0]->company_type))->get(['id','type_name']);//公司类型

            $h_n ="";
            $houses_num = $data[0]->houses_num;
            $hnum= array_filter(explode(',',$houses_num));
            foreach($hnum as $num){
               $h_num=  Level::where('id',intval($num))->value('type_name');

               $h_n .= ','.$h_num;
            }
             $h_n= substr($h_n,1);

            $demand = Demand::ancestorsAndSelf(intval($data[0]->tenant_need));//租户需求
            
            $arr = array();
            foreach($data[0] as $k=> $d){
                $arr[$k] = $d;
                $arr['pay_type'] = $payType;
                $arr['contract_period'] = $cPeriod;
                $arr['company_type'] = $companyType;
                $arr['tenant_need'] = $demand;
                $arr['houses_num'] = $h_n;
            }
            $data = $arr;
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
