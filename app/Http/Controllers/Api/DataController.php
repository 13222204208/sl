<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;

use App\Model\Clean;
use App\Model\House;
use App\Model\Level;
use App\Model\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            return response()->json(['msg' =>'未登陆', 'code' => -1]);
        }
/*         $datecount = array();
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
        $datecount['yearcount']= $yearcount;//今年的数量 */
      
        $count = array();
        $cleancount = Clean::where('uid',$user->id)->count();//我的扫楼记录数量
        $count['cleancount']= $cleancount;

        $housecount = Clean::where('uid',intval($user->id))->groupBy('houses_name')->get()->count();//我的扫楼记录数量
        $count['housecount']= $housecount;//我扫过的楼盘

        $tenantcount = Tenant::where('uid',$user->id)->count();//我的租户记录数量
        $count['tenantcount'] = $tenantcount;
        
        return response()->json([
            'code' => 1,
            'msg' => '查询成功',
            'count' =>$count 
        ], 200);
    }

    public function loupan(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token); 
        if (!$user->account) {
            return response()->json(['msg' =>'未登陆', 'code' => -1]);
        }

        $size = 20;
        if($request->size){
            $size = $request->size;
        }

        $page = 0;
        if($request->page){
            $page = ($request->page -1)*$size;
        }



        $data= House::where('uid',intval($user->id))->skip($page)->take($size)->get(['id','houses_name','map','city','business_area','property_type']);

        if($request->has('houses_name')){
            $data = House::where('uid',intval($user->id))->where('houses_name','like','%'.$request->houses_name.'%')->get(['id','houses_name','map','city','business_area','property_type']);
        }
 /*        $d = Level::where('type_name',$data[0]->houses_name)->get(['type_name','id']);
        $pid =Level::where('parent_id',$d[0]->id)->get(['type_name','id']);
        return $pid;
        $arr= array();
            foreach($data as $k=> $key){
              array_push($arr,$key);
              $d = Level::where('parent_id',$key['id'])->get(['type_name','id']);
              if(empty($d[0])){
                $arr[$k]['num'] = 1;
                }else{
                    $st = Level::where('parent_id',$d[0]['id'])->get(['type_name','id']);
                    if(empty($st[0])){
                        $arr[$k]['num'] = 2;
                    }
                }
            }
            return $arr; */

        return response()->json([
            'code' => 1,
            'msg' => '成功',
            'data' =>$data
        ], 200);
    }

    public function payType(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token); 
        if (!$user->account) {
            return response()->json(['msg' =>'未登陆', 'code' => -1]);
        }

        $data= DB::table('paytype')->get(['type_name','month']);
        return response()->json([
            'code' => 1,
            'msg' => '成功',
            'data' =>$data
        ], 200);
    }

    public function period(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token); 
        if (!$user->account) {
            return response()->json(['msg' =>'未登陆', 'code' => -1]);
        }

        $data= DB::table('period')->get(['type_name','month']);
        return response()->json([
            'code' => 1,
            'msg' => '成功',
            'data' =>$data
        ], 200);
    }
}
