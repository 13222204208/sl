<?php

namespace App\Http\Controllers\Houses;


use App\Model\Level;
use App\Model\Tenant;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HousesController extends Controller
{
    public function gainLoupan(Request $request)
    { 
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Level::where('parent_id',null)->paginate($limit);

            return $data;
      
        }
    }

    public function info(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $datas= DB::table('houses')->paginate($limit);
            return $datas;
      
        }
    }

    public function tenantInfo(Request $request,$hnum)
    {
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Tenant::where('tenant_address',$hnum)->orderBy('created_at','desc')->paginate($limit);
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
            if ($pid == 0) {
                for ($i=0; $i < count($type_name) ; $i++) { 
                  $status=  Level::create(['type_name'=> $type_name[$i]]);
                }
            }else{
               
                for ($i=0; $i < count($type_name) ; $i++) { 
                    $status=  Level::create(['type_name'=> $type_name[$i],'parent_id'=>$pid]);
                  }
            }
           
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
            $before= $level->type_name;
             if($level->parent_id == null){
                 $pid = 0;
             }else{
                 $pid = $level->parent_id;
             }
            $level->type_name = $request->type_name;

            if ($level->save()) {
                $time= date('Y-m-d H:i:s');
                    DB::table('update_level_name')->insertGetId([
                       'before'=>$before,'update_name'=>$request->type_name,
                        'pid'=>$pid,'hid'=>$request->id,'created_at'=>$time]);
                        return response()->json(['status'=>200]);
                         }else{
                              return response()->json(['status'=>403]);
                         }
                        
            }
            
      
        
        
    }

    public function delName(Request $request)
    {
        if ($request->ajax()) {
            $company = Level::find($request->id);
            $state= $company->delete();
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
        
    }
}
