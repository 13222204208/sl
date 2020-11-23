<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Model\Tenant;
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
            return response()->json(['msg' =>'请登陆', 'code' => 301]);
        }

        $tenant_name = '';
        if (isset($request->tenant_name)) {
            $tenant_name = $request->tenant_name;
        }

        $data= Tenant::where('uid',$user->id)->when($tenant_name, function ($query) use ($tenant_name) {
            $query->where('tenant_name', $tenant_name);
        })->get();//如何填写租户名称 ，刚按租户名称搜索

        if(isset($request->mature)){
            $stop_time= date('Y-m-d H:i:s',strtotime("+1 month"));//判断租户合同到期时间
            $data= Tenant::where('uid',$user->id)->when($tenant_name, function ($query) use ($tenant_name) {
            $query->where('tenant_name', $tenant_name);
        })->where('stop_time','<=', $stop_time)->get();//获取快到期的租户
        }

        if (isset($request->due)) {
            $pay_time= date('Y-m-d H:i:s',strtotime("+1 month"));//判断租户付款到期时间，一个月内
            $data= Tenant::where('uid',$user->id)->when($tenant_name, function ($query) use ($tenant_name) {
                $query->where('tenant_name', $tenant_name);
            })->where('pay_time','<=', $pay_time)->get();//获取付款快到期的租户
        }


        return response()->json([
            'code' => 201,
            'msg' => '查询成功',
            'data'=> $data
        ], 200);
    }
}
