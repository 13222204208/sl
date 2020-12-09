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
            
            if($request->has('houses_name')){
                $data = House::where('houses_name','like','%'.$request->houses_name.'%')->get(['id','houses_name','map','city','business_area','property_type']);
                
                $arr = array();
                foreach($data as $d){
                   
                    $id = Level::where('type_name',$d['houses_name'])->where('parent_id',null)->value('id');
                    $d['id'] = $id;
                    $arr[] = $d;
                }

                return response()->json([
                    'code' => 1,
                    'msg' => '成功',
                    'data' =>$arr
                ], 200);
            }


            if($request->has('all')){
                
                 $data= House::skip($page)->take($size)->get(['id','houses_name','map','city','business_area','property_type']);

                 $arr = array();
                 foreach($data as $d){
                    
                     $id = Level::where('type_name',$d['houses_name'])->where('parent_id',null)->value('id');
                     $d['id'] = $id;
                     $arr[] = $d;
                 }
                 
                return response()->json([
                    'code' => 1,
                    'msg' => '成功',
                    'data' =>$data
                ], 200); 
            }
               
                    $lpid = $request->lpid;
                    $data = Level::where('parent_id',$lpid)->get(['type_name','id']);
            
               
       
                 $arr= array();
    
                 $house = '';
                 if($request->has('cid')){
                     $d= Level::defaultOrder()->ancestorsAndSelf($request->cid);
                   
                     foreach($d as $h){
                         $house .= $h['type_name'].'>';
                     }
                     $arr['house_info'] = $house;
    
                 }else{
                    $d= Level::defaultOrder()->ancestorsAndSelf($request->lpid);
                   
                    foreach($d as $h){
                        $house .= $h['type_name'].'>';
                    }
                 }
    
                 $hnum = Clean::where('houses_info',$house)->get('houses_num');
                 $farr = array();
                 foreach($hnum as  $fj){
                    $farr[] = $fj['houses_num'];
                 }
    
                foreach($data as $k=> $key){
                    $arr['data'][] = $key;
                  $d = Level::where('parent_id',$key['id'])->get(['type_name','id']);
                  if(empty($d[0])){
                    $arr['data'][$k]['num'] = 1;//判断当前的层级，1 为最后一层，2 为倒数第二层
                    $numhave= Level::where('id',$arr['data'][$k]['id'])->value('lpid');
                    if($numhave == 1){
                        $arr['data'][$k]['have'] = 1;
                    }else{
                        $arr['data'][$k]['have'] = 0;//判断房间号是否已经录入过
                    }
                    }else{
                        $arr['data'][$k]['num'] = 0;
                        $st = Level::where('parent_id',$d[0]['id'])->get(['type_name','id']);
                        if(empty($st[0])){
                            $arr['data'][$k]['num'] = 2;
                        }
                    }
                    
                }
    
    
    
                return response()->json([
                    'code' => 1,
                    'msg' => '成功',
                    'data' => $arr
                ], 200);

            

            $data= House::where('uid',intval($user->id))->skip($page)->take($size)->get(['id','houses_name','map','city','business_area','property_type']);
            
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
