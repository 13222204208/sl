<?php

namespace App\Http\Controllers\Work;

use App\Model\User;
use App\Model\Clean;
use App\Model\House;
use App\Model\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class WorkController extends Controller
{
    public function brokerList(Request $request)
    {
        if ($request->ajax()) {  
            $limit = $request->get('limit'); 
            $data= User::where('id','>',1)->paginate($limit);
            return $data; 
        }
    }

    public function queryAccount(Request $request ,$account)
    {
        if ($request->ajax()) {
            $limit = $request->get('limit'); 

            $data= User::where('account','like','%'.$account.'%')->orWhere('name','like','%'.$account.'%')->paginate($limit);
            return $data;
        }
    }
    
    public function info(Request $request)//经纪人工作详情
    {
        if($request->ajax()){
            $id = $request->id;//经纪人id
            $cleanNum = Clean::where('uid',$id)->count();//经纪人扫楼记录条数
            $comeNum = Clean::where('uid',$id)->groupBy('houses_name')->pluck('houses_name')->count();//经纪人扫楼记录条数
           
            $data['cleanNum'] = $cleanNum;
            $data['comeNum'] = $comeNum;
            return response()->json(['status'=>200,'data'=>$data]);
        }
    }

    public function brokerRecord(Request $request)
    {
        if($request->ajax()){
            $id = $request->broker_id;//经纪人id
            $startTime = $request->startTime;
            $stopTime = $request->stopTime;
            $cleanNum = Clean::where('uid',$id)->where('created_at','>',$startTime)->where('created_at','<',$stopTime)->count();//经纪人扫楼记录条数
            $comeNum = Clean::where('uid',$id)->where('created_at','>',$startTime)->where('created_at','<',$stopTime)->groupBy('houses_name')->pluck('houses_name')->count();//经纪人扫楼记录条数
           
            $data['cleanNum'] = $cleanNum;
            $data['comeNum'] = $comeNum;
            return response()->json(['status'=>200,'data'=>$data]);
        }
    }
}
