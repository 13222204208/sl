<?php

namespace App\Http\Controllers\Tenant;

use App\Model\Tenant;
use App\Model\GetTenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TenantController extends Controller
{
    public function queryTenant(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= GetTenant::orderBy('id','desc')->paginate($limit);

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
            $data= GetTenant::where('tenant_name','like','%'.$state.'%')->orWhere('tenant_user','like','%'.$state.'%')->paginate($limit);

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


    public function stopDate(Request $request,$day)//查询合同快到期的租户
    {
        if ($request->ajax()) {
            $limit = $request->get('limit');

            if($day == "yes" || $day =="no"){
                if($day == "yes"){
                    $day ="1";
                }

                if($day == "no"){
                    $day = "0";
                }

                $data = GetTenant::where('is_we_company',$day)->paginate($limit);
                return $data;
            }

            $day = '+'.$request->day.'day';
            $date= date("Y-m-d",strtotime($day));
        
            $data = GetTenant::whereDate('stop_time','<',$date)->paginate($limit);
            return $data;
        }
    }
}
