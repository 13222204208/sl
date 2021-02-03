<?php

namespace App\Http\Controllers\Tenant;

use App\Model\User;
use App\Model\Tenant;
use App\Model\GetTenant;
use Illuminate\Http\Request;
use App\Exports\TenantExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class TenantController extends Controller
{
    public function export() 
    {
        return Excel::download(new TenantExport, '租户记录.xlsx');
    }

    public function queryTenant(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= GetTenant::whereIn('permission',$this->userPermission())->orderBy('id','desc')->paginate($limit);

            return $data;
        }
    }

    public function gainInfo(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $state = $request->get('tenant_name');
            if($state == null){
                $data= GetTenant::paginate($limit);

                return $data;
            }
            $data= GetTenant::whereIn('permission',$this->userPermission())->where('tenant_name','like','%'.$state.'%')->orWhere('tenant_user','like','%'.$state.'%')->paginate($limit);

            return $data;
        }
    }

    public function delTenant(Request $request)
    {
        if($request->ajax()){
            $tenant= GetTenant::find($request->id);
            $state= $tenant->delete();
            if ($state) {
                return response()->json([
                    'status'=>200,
                ]);
            }else{
                return response()->json([
                    'status'=>403,
                ]);
            }
        }
    }

    public function updateTenant(Request $request)
    {
        if($request->ajax()){

            $tenant= GetTenant::where('id',$request->id)->update($request->data);
           
            if ($tenant) {
                return response()->json([
                    'status'=>200,
                ]);
            }else{
                return response()->json([
                    'status'=>403,
                ]);
            }
        }
    }


    public function stopDate(Request $request)//查询合同快到期的租户
    {
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $day = strval($request->get('day'));

            $state = true;
            if($day == ""){
                $state = false;
            }  
          
            $status = false;
            $is_we_company= '';
            if($request->get('is_we_company') == 0 || $request->get('is_we_company') == 1){
                $is_we_company = $request->get('is_we_company');
                $status = true;
            }


            $day = '+'.$day.'day';
            $date= date("Y-m-d",strtotime($day));

            $tt = $request->get('tenant_name');
          
            $data = GetTenant::whereIn('permission',$this->userPermission())->when($status, function ($query) use ($is_we_company){
                return $query->where('is_we_company',$is_we_company);
            })->when($state, function ($query) use ($date) {
                return $query->whereDate('stop_time','<',$date);
            })->when($tt, function ($query) use ($tt) {
                return $query->where('tenant_name','like','%'.$tt.'%')->orWhere('tenant_user','like','%'.$tt.'%')->orWhere('broker_name','like','%'.$tt.'%');
            })->paginate($limit);
            return $data;
        }
    }

    public function queryAccount(Request $request)
    { 
        $state = false;
        $broker = '';
        if($request->has('broker')){
            $state = true;
         $broker =$request->get('broker');
        }
        $limit = $request->get('limit');
        $permission = $this->userPermission();
        $data= User::where('id','>',1)->when($state, function ($query) use ($broker) {
            return $query->where('name','like','%'.$broker.'%');
        })->select('id','branch','account','name','status')->orderBy('id','desc')->paginate($limit);
        
        $newData= array();
        foreach ($data as $user) {
           $arr = explode(',',$user->branch);
           $str= $arr[count($arr)-1];
           if(in_array($str,$permission)){
               $newData[] = $user;
           } 
       } 
       return response()->json(['status'=>200,'data'=>$newData]);
    }

    public function updateBroker(Request $request)
    {
        $tid = $request->tid;//租户信息id
        $bid = $request->id;//经纪人id
        $arr = array_filter(explode(',',$tid));

        $user = User::find($bid);
        $state =Tenant::whereIn('id',$arr)->update([
            'uid' => $bid,
            'broker_name' => $user->name,
            'broker_phone' => $user->account
        ]);

        if ($state) {
            return response()->json([
                'status'=>200,
            ]);
        }else{
            return response()->json([
                'status'=>403,
            ]);
        }

    }

    public function userPermission()
    {
        $id = session('id');//用户id
        $user = User::find($id);
        $arr = explode(',',$user->branch);
        return $arr;
    }
}
