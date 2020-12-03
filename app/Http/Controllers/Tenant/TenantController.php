<?php

namespace App\Http\Controllers\Tenant;

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

    public function gainInfo(Request $request,$state)
    {
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= GetTenant::where('tenant_name',$state)->paginate($limit);

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
            $day = '+'.$request->day.'day';
            $date= date("Y-m-d",strtotime($day));
            $limit = $request->get('limit');
            $data = GetTenant::whereDate('stop_time','<',$date)->paginate($limit);
            return $data;
        }
    }
}
