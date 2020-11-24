<?php

namespace App\Http\Controllers\Home;

use Carbon\Carbon;
use App\Model\Clean;
use App\Model\Tenant;
use App\Model\Betting;
use App\Model\Recharge;
use App\Model\UserInfo;
use App\Model\Withdrawal;
use Illuminate\Http\Request;
use App\Model\UserStatistics;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{

    public function cleanCount()//扫数总记录
    {
        $num= Clean::all()->count();
        if (!is_null($num)) {
            return response()->json(['status'=>200,'num'=>$num]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function cleanDate(Request $request)//扫楼范围内记录
    {
        $dateNum= Clean::whereDate('created_at','>=',$request->startTime)->whereDate('created_at','<=',$request->stopTime)->count();
        if (!is_null($dateNum)) {
            return response()->json(['status'=>200,'dateNum'=>$dateNum]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function tenantCount()//租户总记录
    {
        $num= Tenant::all()->count();
        if (!is_null($num)) {
            return response()->json(['status'=>200,'num'=>$num]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function tenantDate(Request $request)//租户范围内记录
    {
        $dateNum= Clean::whereDate('created_at','>=',$request->startTime)->whereDate('created_at','<=',$request->stopTime)->count();
        if (!is_null($dateNum)) {
            return response()->json(['status'=>200,'dateNum'=>$dateNum]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function tenantType()//租户类型饼状图占比
    { 
        $data= DB::table('tenant')
        ->select('tenant_type', DB::raw('count(*) as total'))
        ->groupBy('tenant_type')
        ->get();
         //$data = Tenant::groupBy('tenant_type')->count('tenant_type');

        if (!is_null($data)) {
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>403]);
        } 
    }

    public function dueTenant(Request $request)
    {
        if ($request->ajax()) {

            $dueTime= date("Y-m-d",strtotime("+1 month",time()));
            $limit = $request->get('limit');
            $data= Tenant::whereDate('stop_time','<=',$dueTime)->paginate($limit);

            return $data;
        }
    }

   
}
