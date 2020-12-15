<?php

namespace App\Http\Controllers\Home;

use Carbon\Carbon;
use App\Model\Clean;
use App\Model\Tenant;
use App\Model\Betting;
use App\Model\Recharge;
use App\Model\UserInfo;
use App\Model\GetTenant;
use App\Model\Withdrawal;
use Illuminate\Http\Request;
use App\Model\UserStatistics;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Model\level;

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

        $info= Clean::whereDate('created_at','>=',$request->startTime)->whereDate('created_at','<=',$request->stopTime)->get();

        if (!is_null($dateNum)) {
            return response()->json(['status'=>200,'dateNum'=>$dateNum,'info'=>$info]);
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

    public function tenantKong()//楼盘空置率
    {
        $data = Level::all();
        $num1 = 0;
        foreach($data as $d){
            $state = Level::where('parent_id',$d['id'])->get();
            if(empty($state[0])){
                $num1++;
            }
        }

        $num2 = Level::where('lpid',1)->count();
     
        $num = intval(($num2/$num1) *100);
        if (!is_null($num)) {
            return response()->json(['status'=>200,'num'=>$num]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function tenantDate(Request $request)//租户范围内记录
    {
        $dateNum= Clean::whereDate('created_at','>=',$request->startTime)->whereDate('created_at','<=',$request->stopTime)->count();

        $tenantInfo= Clean::whereDate('created_at','>=',$request->startTime)->whereDate('created_at','<=',$request->stopTime)->get();
        if (!is_null($dateNum)) {
            return response()->json(['status'=>200,'dateNum'=>$dateNum ,'info'=> $tenantInfo]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function tenantType()//租户类型饼状图占比
    { 
        $data= DB::table('tenant')
        ->select('is_we_company', DB::raw('count(*) as total'))
        ->groupBy('is_we_company')
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
            $data= GetTenant::whereDate('stop_time','<=',$dueTime)->paginate($limit);
            return $data;
        }
    }

   
}
