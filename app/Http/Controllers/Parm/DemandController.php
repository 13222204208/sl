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
            $data= Demand::where('parent_id',0)->paginate($limit);

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
            $data= array();
            $pid = intval($request->pid);
            if ($pid == 0 ) {
                $state = '/';
            }else{
                $Demand = Demand::find($pid);
                $state = rtrim($Demand->tree,'/').'/'.strval($pid).'/';
                //return response()->json(['status'=>$tree]);
            }
           
            for ($i=0; $i < count($type_name) ; $i++) { //指添加分类数据
                $data[$i]['type_name'] = $type_name[$i];
                $data[$i]['parent_id'] = $pid;
                $data[$i]['tree'] = $state;
            }
            $status = Demand::insert($data);
           
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
           $tree = rtrim($request->tree,'/').'/'.$request->id.'%';
          
            
           Demand::where('tree','like',$tree)->delete();
           $state= Demand::destroy($request->id);
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
}
