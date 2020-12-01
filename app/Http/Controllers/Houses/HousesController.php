<?php

namespace App\Http\Controllers\Houses;


use App\Model\House;
use App\Model\Level;
use App\Model\Tenant;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HousesController extends Controller
{
    public function gainLoupan(Request $request ,$lpname)
    { 
        $limit = $request->get('limit'); 
        if ($request->ajax()) {
           
            $id = Level::where('type_name',$lpname)->get('id');
           
            if(count($id)){ 
                $limit = $request->get('limit');
                $data= Level::where('id',$id)->paginate($limit);
                return $data;
            }else{
                Level::create(['type_name' => $lpname,'parent_id'=>0]);
                $data= Level::where('type_name',$lpname)->paginate($limit);
                return $data;
            }
        /*     $limit = $request->get('limit');
            $data= Level::where('parent_id',null)->paginate($limit); */
            //$data= level::where('lpid',104)->get()->toTree();
            
      
        }
    }


    public function lookHouse(Request $request)//查看楼盘信息
    { 
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= House::paginate($limit);
            return $data;
      
        }
    }

    public function addHouse(Request $request)//创建楼盘信息
    {
        if ($request->ajax()) {
            try {
                $request->validate([
                    'houses_name' => 'required|unique:houses|max:50'
                ]); 
            } catch (\Throwable $th) {
                return response()->json(['status'=>403]);
            }

            $house= new House;
            $house->houses_name = $request->houses_name;
            $house->houses_address = $request->houses_address;
            $house->map = $request->map;
            $house->city = $request->city;
            $house->business_area = $request->business_area;
            $house->property_type = $request->property_type;
            if ($house->save()) {
                return response()->json(['status'=>200]);
            }else{
                return response()->json(['status'=>403]);
            }
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
            $lpid = intval($request->lpid);
            if ($pid == 0) {
                for ($i=0; $i < count($type_name) ; $i++) { 
                  $status=  Level::create(['type_name'=> $type_name[$i],'lpid'=>$lpid]);
                }
            }else{
               
                for ($i=0; $i < count($type_name) ; $i++) { 
                    $status=  Level::create(['type_name'=> $type_name[$i],'parent_id'=>$pid ,'lpid'=>$lpid]);
                  }
            }
           
           if ($status) {
                return response()->json(['status'=>200]);
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
