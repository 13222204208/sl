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
            $type_name= array_filter(explode('，',$str));
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

    public function updateName(Request $request)//修改楼盘架构名称
    {
        if ($request->ajax()) {
            $level = Level::find($request->id);
            
            $before = "";
            if ($level->tree == '/') {
                $before = $level->type_name;
                $newName = $request->type_name;
            }else{
                $tree = explode('/',$level->tree);//拼接楼盘分类名称
                $tree = array_filter($tree);
                foreach($tree as $tid){

                    $tname = Level::find(intval($tid));
                
                    $before .=  $tname->type_name;
                }

                $newName = $before.$request->type_name;

                $before .= $level->type_name;
               
            }
    
            $level->type_name= $request->type_name;
            $pid = $level->parent_id;
            if ($level->save()) {
                $time= date('Y-m-d H:i:s');
                DB::table('update_level_name')->insertGetId([
                    'before'=>$before,'update_name'=>$newName,
                    'pid'=>$pid,'hid'=>$request->id,'created_at'=>$time,
                    'tree'=> $level->tree
                    ]);

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
