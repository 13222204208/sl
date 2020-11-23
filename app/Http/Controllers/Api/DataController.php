<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;

use App\Model\Clean;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class DataController extends Controller
{
    

    public function referRecord(Request $request)//扫楼记录数量
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token); 
        if (!$user->account) {
            return response()->json(['msg' =>'请登陆', 'code' => 301]);
        }
        $datecount = array();
        $day=Carbon::now()->startOfDay()->toDateString();//今天
        $week=Carbon::now()->startOfWeek()->toDateString();//当前星期
        $month=Carbon::now()->startOfMonth()->toDateString();//当月
        $year=Carbon::now()->startOfYear()->toDateString();//今年
       
        $daycount = Clean::where('broker_phone',$user->account)->whereDate('created_at',$day)->count();

        $datecount['daycount']= $daycount;//今天的数量

        $weekcount = Clean::where('broker_phone',$user->account)->whereDate('created_at','>=',$week)->count();
        $datecount['weekcount']= $weekcount;//当前星期的数量

        $monthcount = Clean::where('broker_phone',$user->account)->whereDate('created_at','>=',$month)->count();
        $datecount['monthcount']= $monthcount;//当月的数量

        $yearcount = Clean::where('broker_phone',$user->account)->whereDate('created_at','>=',$year)->count();
        $datecount['yearcount']= $yearcount;//今年的数量
        
        
        return response()->json([
            'code' => 201,
            'msg' => '查询成功',
            'datecount' =>$datecount 
        ], 200);
    }
}
