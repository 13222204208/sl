<?php

namespace App\Http\Controllers\Houses;


use App\Model\User;
use App\Model\Clean;
use App\Model\House;
use App\Model\Level;

use App\Model\Tenant;
use App\Model\GetTenant;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HousesController extends Controller
{
    public function gainLoupan(Request $request ,$id)
    { 
        //$limit = $request->get('limit');  
        if ($request->ajax()) {
            $lpname = House::where('id',$id)->value('houses_name');
          
            $id = Level::where('type_name',$lpname)->value('id');
            if($id){ 
                //$data= Level::where('parent_id',$id)->paginate($limit);
                $data= Level::descendantsAndSelf($id);
                return response()->json([
                    'code' =>0,
                    'msg' => '',
                    'data' =>$data
                ]);
            }else{
                Level::create(['type_name' => $lpname,'parent_id'=>0]);
                $id = Level::where('type_name',$lpname)->value('id');

                $data= Level::descendantsAndSelf($id);
                return response()->json([
                    'code' =>200,
                    //'msg' => '',
                    'data' =>$data
                ]);
            }
            
      
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
            $house->uid = session('id');
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

    public function gainHouseNum()
    { 

        $data= Level::all();
        return response()->json([
            'code' =>0,
            'msg' => '',
            'data' =>$data
        ]);
     
    }

    public function lookNum(Request $request ,$id)
    {
        $limit = $request->get('limit');

        $state= true;
        $data= Level::descendantsAndSelf($id)->where('lpid',1);
        $arr = array();
        foreach($data as $d){
            $arr[] = $d['id'];
        }
        //$result= GetTenant::whereRaw('FIND_IN_SET(?,houses_num)',$arr)->paginate($limit);
        if(empty($arr)){ 
            return [];
        }
        $res = GetTenant::when($state,function ($q) use ($arr) {
            foreach ($arr as $d) { 
            $q->orWhereRaw('FIND_IN_SET(?,houses_num)',[$d]);
            }
        })->paginate($limit);
       
        return $res;
    }

    public function createName(Request $request)
    {
        if ($request->ajax()) {

            
            $str= str_replace("，",",",$request->type_name);
            $type_name= array_filter(explode(',',$str));
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
            $b= $level->type_name;

            $datas= Level::ancestorsOf(intval($request->id));
            $before = "";
         
            foreach($datas as $data){
                $before .= $data['type_name'].' ，';
            }
             if($level->parent_id == null){
                 $pid = 0;
             }else{
                 $pid = $level->parent_id;
             }
            $level->type_name = $request->type_name;

            

            if ($level->save()) {
                $time= date('Y-m-d H:i:s');
                    DB::table('update_level_name')->insertGetId([
                       'before'=>$before.$b,'update_name'=>$before.$request->type_name,
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

    public function updateHouse(Request $request)//修改楼盘信息
    {
        DB::beginTransaction();
        try {
            if ($request->ajax()) {
                $data = $request->data;
                $before_name = $request->before;
                House::where('id',$request->id)->update($data);

                Level::where('type_name',$before_name)->where('parent_id',null)->update([
                    'type_name'=>$data['houses_name']
                ]);

                // Clean::where('houses_name',$before_name)->update([
                //     'houses_name'=>$data['houses_name']
                // ]);

                Tenant::where('houses_name',$before_name)->update([
                    'houses_name'=>$data['houses_name']
                ]);

                DB::commit();//提交至数据库
                return response()->json(['status'=>200]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status'=>403]);
        }

    }

    public function search(Request $request, $hname)//  搜索楼盘   
    {
        $data = House::where('houses_name','like','%'.$hname.'%')->get();
        $id = session('id');//用户id
        $user = User::find($id);
        $state= $user->hasPermissionTo('导出权限');
        if ($data) {
            return response()->json(['status'=>200,'data'=>$data,'state' => $state]);
        }else{
             return response()->json(['status'=>403]);
        }
    }
}
