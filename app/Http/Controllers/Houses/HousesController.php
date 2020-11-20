<?php

namespace App\Http\Controllers\Houses;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Model\Level;

use function PHPSTORM_META\type;

class HousesController extends Controller
{
    public function gainLoupan(Request $request)
    { 
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Level::where('parent_id',0)->paginate($limit);

            return $data;
      
        }
    }

    public function gainLoupanType(Request $request,$pid)
    { 
       
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Level::where('parent_id',$pid)->paginate($limit);

            return $data;
      
        }
    }

    public function createName(Request $request)
    {
        if ($request->ajax()) {

            $str= str_replace(" ",'',$request->type_name);
            $type_name= explode('，',$str);
            $data= array();
            $pid = intval($request->pid);
            if ($pid == 0 ) {
                $state = '/';
            }else{
                $level = Level::find($pid);
                $state = rtrim($level->tree,'/').'/'.strval($pid).'/';
                //return response()->json(['status'=>$tree]);
            }
           
            for ($i=0; $i < count($type_name) ; $i++) { //指添加分类数据
                $data[$i]['type_name'] = $type_name[$i];
                $data[$i]['parent_id'] = $pid;
                $data[$i]['tree'] = $state;
            }
            $status = Level::insert($data);
           
           if ($status) {
                return response()->json(['status'=>200,'pid'=>2]);
           }else{
                return response()->json(['status'=>403]);
           }
        }
    }

    public function updateName(Request $request)
    {
        if ($request->ajax()) {
            $level = Level::find($request->id);
            $level->type_name= $request->type_name;
            if ($level->save()) {
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
          
            
           Level::where('tree','like',$tree)->delete();
           $state= Level::destroy($request->id);
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
        
    }
}
