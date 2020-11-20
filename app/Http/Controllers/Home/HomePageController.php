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

    public function todayWithdrawalRecords()
    {
        $time=Carbon::now()->toDateString();
        $with_apply_num = Withdrawal::whereDate('ask_time', $time)->where('state',2)->count();
        $with_deposit_num = Withdrawal::whereDate('ask_time', $time)->where('state',1)->count();
        $withdrawal_money = Withdrawal::whereDate('ask_time', $time)->where('state',1)
                        ->pluck('draw_money')->sum();      
                       
        if (!is_null($withdrawal_money)) {
            return response()->json([
                'status'=>200 ,'with_apply_num'=>$with_apply_num,
                'with_deposit_num'=>$with_deposit_num,
                'withdrawal_money'=>$withdrawal_money
            ]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function todayAwardRecords()
    {
        $time=Carbon::now()->toDateString();
        $award_money = UserStatistics::whereDate('created_at', $time)->pluck('reward_sum')->sum();   
        $award_num = UserStatistics::whereDate('created_at', $time)->count();
                         
                       
        if (!is_null($award_num)) {
            return response()->json([
                'status'=>200 ,'award_num'=>$award_num,
                'award_money'=>$award_money
            ]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function todayRegisterUser()
    {
        $time=Carbon::now()->toDateString();
        $register_num = UserInfo::whereDate('register_time', $time)->count(); 
        if (!is_null($register_num)) {
            return response()->json(['status'=>200,'register_num'=>$register_num]);
        }else{
            return response()->json(['status'=>403]);
        }
    }



    public function yearCashFlow()
    {
        $time=Carbon::now()->toDateString();
        $subtime = Carbon::now()->subYears(1);
         $data = UserStatistics::whereBetween('time',[$subtime,$time])
         ->selectRaw('DATE_FORMAT(time,"%Y-%m") as date,SUM(deposit_sum) as deposit,SUM(draw_money_sum) as draw_money,SUM(reward_sum) as reward')
         ->groupBy('date')->orderBy('date')->get();

        if (!is_null($data)) {
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>403]);
        } 
    }

    public function yearBettingRecords()
    {
        $time=Carbon::now()->toDateString();
        $subtime = Carbon::now()->subYears(1);
         $datas = Betting::whereBetween('bottom_pour_time',[$subtime,$time])
       //  ->selectRaw('DATE_FORMAT(bottom_pour_time,"%Y-%m") as date,SUM(bottom_pour) as bottom')
         ->select(DB::raw('DATE_FORMAT(bottom_pour_time,"%Y-%m") as date ,SUM(bottom_pour) as bottom ,platform_name') )
         ->groupBy('platform_name','date')->orderBy('date')->get();

 
        $result = array();
        foreach ($datas as $data) {//把字段值相同的平台名称值拼装成 一个数组
            isset($result[$data['platform_name']]) || $result[$data['platform_name']] = array();
            $result[$data['platform_name']]['bottom'][] = $data['bottom'];
            $result[$data['platform_name']]['date'][] = $data['date'];
        }
        return $result;

        if (!is_null($data)) {
            return response()->json(['status'=>200,'data'=>$result]);
        }else{
            return response()->json(['status'=>403]);
        } 
    }

    public function moneySwitchControl()
    {
        $data = Recharge::where('recharge_money','>',5000)->where('state',1)->get();
        if(!is_null($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>403]);
        } 
    }

    public function moneyRolloutControl()
    {
        $data = Withdrawal::where('draw_money','>',5000)->where('state',1)->get();
        if(!is_null($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>403]);
        } 
    }

    public function sameIp()
    {
       /*  $data = Betting::groupBy('login_ip')->having('count(login_ip)','>',2) 
        ->get(); */

            $datas = DB::table('bg_betting')
                ->select('login_ip')
                ->groupBy('login_ip')
                ->havingRaw('count(login_ip) > 1')
                ->get()->map(function ($value) {
                    return (array)$value;
                })->toArray();

                $result = array();
                foreach($datas as $data){
                    $result[]= $data['login_ip'];
                }
                
          $ipData = Betting::whereIn('login_ip',$result)->get();
           
        if(!is_null($ipData)){
            return response()->json(['status'=>200,'data'=>$ipData]);
        }else{
            return response()->json(['status'=>403]);
        }  
    }
}
