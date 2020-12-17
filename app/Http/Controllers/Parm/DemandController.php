<?php

namespace App\Http\Controllers\Parm;


use App\Model\Phone;
use App\Model\Demand;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DemandController extends Controller
{
    public function gainDemand(Request $request)
    { 
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Demand::where('parent_id',null)->paginate($limit);

            return $data;
      
        }
    }

    public function gainDemandType(Request $request,$pid)
    { 
       
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Demand::where('parent_id',$pid)->paginate($limit);

            return $data;
      
        }
    }

    public function createName(Request $request)
    {
        if ($request->ajax()) {

            $str= str_replace(" ",'',$request->type_name);
            $type_name= array_filter(explode('，',$str));
           
            $pid = intval($request->pid);

            $result= Demand::withDepth()->find($pid);
            $depth= $result->depth; 
            if($depth >= 2){
                return response()->json(['status'=>403]);
            }

            if ($pid == 0) {
                for ($i=0; $i < count($type_name) ; $i++) { 
                  $status=  Demand::create(['type_name'=> $type_name[$i]]);
                }
            }else{
               
                for ($i=0; $i < count($type_name) ; $i++) { 
                    $status=  Demand::create(['type_name'=> $type_name[$i],'parent_id'=>$pid]);
                  }
            }
           
           if ($status) {
                return response()->json(['status'=>200]);
           }else{
                return response()->json(['status'=>403]);
           }
        }
    }

    public function updateName(Request $request)
    {
        if ($request->ajax()) {
            $Demand = Demand::find($request->id);
            $Demand->type_name= $request->type_name;
            if ($Demand->save()) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
        
    }

    public function delName(Request $request)
    {
        if ($request->ajax()) {
            $branch = Demand::find($request->id);
            $state= $branch->delete();
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
        
    }

    public function updatePhone(Request $request)
    {
        if ($request->ajax()) {
            $state= Phone::updateOrCreate(['id'=>1],['phone'=>$request->phone]);
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
    }

    public function queryPhone(Request $request)
    {
        if ($request->ajax()) {
            $state= Phone::find(1);
            if ($state) {
                return response()->json(['status'=>200,'phone'=> $state->phone]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
    }

    public function addPaytype(Request $request)
    {
        if ($request->ajax()) {
            $state= DB::table('paytype')->insert([
                'type_name' => $request->type_name,
                'month' => intval($request->month)
            ]);
            
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
    }

    public function addPeriod(Request $request)
    {
        if ($request->ajax()) {
            $state= DB::table('period')->insert([
                'type_name' => $request->type_name,
                'month' => intval($request->month)
            ]);
            
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
    }

    public function gainPayType(Request $request)//查看付款方式
    { 
       
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= DB::table('paytype')->paginate($limit);

            return $data;
      
        }
    }

    public function delPayType(Request $request)//删除付款方式
    {
        if ($request->ajax()) {
        
            $state= DB::table('paytype')->where('id',$request->id)->delete();
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
        
    }

    
    public function editPayType(Request $request)//修改付款方式
    {
        if ($request->ajax()) { 
            $state= DB::table('paytype')->where('id',intval($request->id))->update([
                'type_name' => $request->type_name,
                'month' => $request->month
            ]);
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
    }

    public function gainPeriod(Request $request)//查看合同期期限
    { 
       
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= DB::table('period')->paginate($limit);

            return $data;
      
        }
    }

    public function delPeriod(Request $request)//删除合同期期限
    {
        if ($request->ajax()) {
        
            $state= DB::table('period')->where('id',$request->id)->delete();
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
        
    }

    
    public function editPeriod(Request $request)//修改合同期期限
    {
        if ($request->ajax()) { 
            $state= DB::table('period')->where('id',intval($request->id))->update([
                'type_name' => $request->type_name,
                'month' => $request->month
            ]);
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
    }

    public function createProtocol(Request $request)
    {
        $state= DB::table('protocol')->insert([
            'title' => $request->title,
            'content' => $request->content,
            'key' => $request->key
        ]);

        if ($state) {
            return response()->json(['status'=>200]);
        }else{
             return response()->json(['status'=>403]);
        }
    }

    public function gainProtocol(Request $request)//查看用户协议
    { 
       
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= DB::table('protocol')->paginate($limit);

            return $data;
      
        }
    }


    public function editProtocol(Request $request)//修改用户协议
    {
        if ($request->ajax()) { 
            $state= DB::table('protocol')->where('id',intval($request->id))->update([
                 'content' => $request->content
            ]);
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
    }
}
