<?php

namespace App\Http\Controllers\Branch;


use App\Menu;
use App\Model\Branch;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function gainBranch(Request $request)
    { 
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Branch::where('parent_id',null)->paginate($limit);

            return $data;
      
        }
    }

    public function gainBranchType(Request $request,$pid)
    {  
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Branch::where('parent_id',$pid)->paginate($limit);

            return $data;
      
        }
    }

    public function createName(Request $request)
    {
        if ($request->ajax()) {

            $str= str_replace(" ",'',$request->name);
            $type_name= explode('，',$str);
            //$data= array();
            $pid = intval($request->pid);

            if ($pid == 0) {
                for ($i=0; $i < count($type_name) ; $i++) { 
                  $status=  Branch::create(['name'=> $type_name[$i],'title'=> $type_name[$i]]);
                }
            }else{
               
                for ($i=0; $i < count($type_name) ; $i++) { 
                    $status=  Branch::create(['name'=> $type_name[$i],'title'=> $type_name[$i],'parent_id'=>$pid]);
                  }
            }

            if ($status) {
                return response()->json(['status'=>200]);
           }else{
                return response()->json(['status'=>403]);
           }
      /*       if ($pid == 0 ) {
                $state = '/';
            }else{
                $Branch = Branch::find($pid);
                $state = rtrim($Branch->tree,'/').'/'.strval($pid).'/';
                //return response()->json(['status'=>$tree]);
            }
           
            for ($i=0; $i < count($type_name) ; $i++) { //指添加分类数据
                $data[$i]['type_name'] = $type_name[$i];
                $data[$i]['parent_id_id'] = $pid;
                $data[$i]['tree'] = $state;
            }
            $status = Branch::insert($data);
           
           if ($status) {
                return response()->json(['status'=>200]);
           }else{
                return response()->json(['status'=>403]);
           } */
        }
    }

    public function updateName(Request $request)
    {
        if ($request->ajax()) {

            $Branch = Branch::find($request->id);
            $Branch->name= $request->name;
            $Branch->title= $request->name;
            if ($Branch->save()) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
        
    }

    public function delName(Request $request)
    {
        if ($request->ajax()) {
            $branch = Branch::find($request->id);
            $state= $branch->delete();
        
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            } 
          /*  $tree = rtrim($request->tree,'/').'/'.$request->id.'%';
          
            
           Branch::where('tree','like',$tree)->delete();
           $state= Branch::destroy($request->id);
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            } */
        }
        
    }
}
