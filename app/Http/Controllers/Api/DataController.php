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

        try {

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

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 0,
                'msg' =>"错误",
            ],200);
        }
      

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

        try {

            $data= House::where('uid',intval($user->id))->skip($page)->take($size)->get(['id','houses_name','map','city','business_area','property_type']);

            if($request->has('houses_name')){
                $data = House::where('uid',intval($user->id))->where('houses_name','like','%'.$request->houses_name.'%')->get(['id','houses_name','map','city','business_area','property_type']);
            }
    
            return response()->json([
                'code' => 1,
                'msg' => '成功',
                'data' =>$data
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 0,
                'msg' =>"错误",
            ],200);
        }


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

        try {
            $data= DB::table('paytype')->get(['type_name','month','id']);
            return response()->json([
                'code' => 1,
                'msg' => '成功',
                'data' =>$data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 0,
                'msg' =>"错误",
            ],200);
        }
        


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

        try {
            $data= DB::table('period')->get(['type_name','month','id']);
            return response()->json([
                'code' => 1,
                'msg' => '成功',
                'data' =>$data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 0,
                'msg' =>"错误",
            ],200);
        }



    }

    public function protocol(Request $request)
    {
        try {
            $data= DB::table('protocol')->where('key',$request->key)->value('content');
            return response()->json([
                'code' => 1,
                'msg' => '成功',
                'data' =>$data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 0,
                'msg' =>"错误",
            ],200);
        }

;
    }
}
