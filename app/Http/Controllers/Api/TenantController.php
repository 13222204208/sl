<?php

namespace App\Http\Controllers\Api;

use App\Model\Level;
use App\Model\Demand;
use App\Model\Tenant;
use App\Model\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class TenantController extends Controller
{
    

    public function tenantRecord(Request $request)//租户记录
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token); 
        if (!$user->account) {
            return response()->json(['msg' =>'请登陆', 'code' => -1]);
        }

        //try {

            $size = 20;
            if($request->size){
                $size = $request->size;
            }
    
            $page = 0;
            if($request->page){
                $page = ($request->page -1)*$size;
            }
    
            if($request->has('tenant_name')){
                $tenant_name = $request->tenant_name; 
            }
    
            if($request->has('business_area') && $request->tenant_name != ""){//判断商圈下的租户
                $data= Tenant::where('uid',$user->id)->where('business_area',$request->business_area)->where('tenant_name', 'like','%'.$tenant_name.'%')->orderBy('id', 'desc')->get(['is_we_company','tenant_name','stop_time','id']);
                return response()->json([
                    'code' => 1,
                    'msg' => '查询成功',
                    'data'=> $data
                ], 200);
            }
    
    
            if($request->has('business_area')){//显示所有商圈 下的租户
                $data= Tenant::where('uid',$user->id)->where('business_area',$request->business_area)->orderBy('id', 'desc')->get(['is_we_company','tenant_name','stop_time','id']);
                return response()->json([
                    'code' => 1,
                    'msg' => '查询成功',
                    'data'=> $data
                ], 200);
            }
    
            if( $request->has('tenant_name') &&$request->tenant_name == "" ){//为空时显示所有商圈
                $data= Tenant::where('uid',$user->id)->select('business_area', DB::raw('count(*) as num'))->groupBy('business_area')->get();
    
                return response()->json([
                    'code' => 1,
                    'msg' => '查询成功',
                    'data'=> $data
                ], 200);
            }
    
    
            
           if($request->has('tenant_name')){  //查询租户名称
                $data= Tenant::where('uid',$user->id)->where('tenant_name', 'like','%'.$tenant_name.'%')->orderBy('id', 'desc')->skip($page)->take($size)->get(['is_we_company','tenant_name','stop_time','id']);//如何填写租户名称 ，刚按租户名称搜索
            
                return response()->json([
                    'code' => 1,
                    'msg' => '查询成功',
                    'data'=> $data
                ], 200);
           }
    
        
    
            if(isset($request->mature)){
                $stop_time= date('Y-m-d H:i:s',strtotime("+1 month"));//判断租户合同到期时间
                $data= Tenant::where('uid',$user->id)->where('stop_time','<=', $stop_time)->orderBy('id', 'desc')->skip($page)->take($size)->get();//获取快到期的租户
                return response()->json([
                    'code' => 1,
                    'msg' => '查询成功',
                    'data'=> $data
                ], 200);
            }
    
            if (isset($request->due)) {
                $pay_time= date('Y-m-d H:i:s',strtotime("+1 month"));//判断租户付款到期时间，一个月内
                $data= Tenant::where('uid',$user->id)->where('pay_time','<=', $pay_time)->orderBy('id', 'desc')->skip($page)->take($size)->get();//获取付款快到期的租户
    
                return response()->json([
                    'code' => 1,
                    'msg' => '查询成功',
                    'data'=> $data
                ], 200);
            }
    
            if($request->has('id')){
                $payType = array();
                $cPeriod = array();
                $companyType = array();
                $demand = array();
                $h_n = '';

                $data= Tenant::where('id',$request->id)->get();//查询租户的详情
                $follow = FollowUp::where('tenant_id',$request->id)->get(['content','created_at']);//查询租户跟进信息


                if($data[0]->pay_type != ''){
                    $payType = DB::table('paytype')->where('id',$data[0]->pay_type)->get(['id','type_name','month']);//付款方式 
                }
    
                if($data[0]->contract_period != ''){
                    $cPeriod = DB::table('period')->where('id',intval($data[0]->contract_period))->get(['id','type_name','month']);//合同期限
                }
    
                if($data[0]->company_type != ''){
                    $companyType = DB::table('company_type')->where('id',intval($data[0]->company_type))->get(['id','type_name']);//公司类型
                }
    
                if($data[0]->houses_num != ''){
                    $h_n ="";
                    $houses_num = $data[0]->houses_num;
                    $hnum= array_filter(explode(',',$houses_num));
                    foreach($hnum as $num){
                       $h_num=  Level::where('id',intval($num))->value('type_name');
        
                       $h_n .= ','.$h_num;
                    }
                     $h_n= substr($h_n,1);
        
                    $demand = Demand::ancestorsAndSelf(intval($data[0]->tenant_need));//租户需求
                }

                if(count($payType)){
                    $payType =get_object_vars($payType[0]);
                   }else{
                       $payType ='';
                   }
      
                   if(count($cPeriod)){
                    $cPeriod =get_object_vars($cPeriod[0]);
                   }else{
                       $cPeriod ='';
                   }
        
                   
                   if(count($companyType)){
                    $companyType =get_object_vars($companyType[0]);
                   }else{
                       $companyType ='';
                   }
        
                   if(count($demand)){
                    $demand =get_object_vars($demand[0]);
                   }else{
                       $demand ='';
                   }
                   
                   if($data[0]['stop_time'] == ''){
                       $data[0]['stop_time'] ='未录入';
                   }
    
                $arr = array();
                foreach($data as  $d){
                    $d['pay_type'] = $payType;
                    $d['contract_period'] =  $cPeriod;
                    $d['company_type'] = $companyType;
                    $d['tenant_need'] = $demand;
                    $d['houses_num'] = $h_n;

                    
                    if($follow->count()){ 
                        $d['follow_up'] = $follow;
                    }else{
                        $d['follow_up'] = '';
                    }
    
    
                    $arr[] =$d;
                }

                
                return response()->json([
                    'code' => 1,
                    'msg' => '查询成功',
                    'data'=> $arr
                ], 200);
            }
    
            $data= Tenant::where('uid',$user->id)->select('business_area', DB::raw('count(*) as num'))->groupBy('business_area')->get();//返回商圈的统计信息
    
            return response()->json([
                'code' => 1,
                'msg' => '查询成功',
                'data'=> $data
            ], 200);
         
/*         } catch (\Throwable $th) {
            return response()->json([
                'code' => 0,
                'msg' =>"输入错误",
            ],200);
        } */

       
    }

    public function updateTenant(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token); 
        if (!$user->account) {
            return response()->json(['msg' =>'请登陆', 'code' => -1]);
        }

        try {
            $payType = DB::table('paytype')->where('id',intval($request->pay_type))->value('month');//付款方式 
            $month = '+'.$payType.'month';
            $pay_time = date("Y-m-d",strtotime($month,strtotime($request->start_time)));
    
            $cPeriod = DB::table('period')->where('id',intval($request->contract_period))->value('month');//合同期限
            $m = '+'.$cPeriod.'month';
            $contract_period = date("Y-m-d",strtotime($m,strtotime($request->start_time)));//由合同期限得出合同到期时间
    
            $id = intval($request->id);
            $data = $request->input();

            if($request->follow_up != ''){
                FollowUp::create([
                    'tenant_id' => $id,
                    'content' => $request->follow_up
                ]);
            }
            
            $data['pay_time'] = $pay_time;
            $data['stop_time'] = $contract_period;
            unset($data['token']);
            unset($data['id']);
            unset($data['follow_up']);
    
            $state= Tenant::where('id',$id)->update($data);
    
            if($state){
                return response()->json([
                    'code' => 1,
                    'msg' => '成功',
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
