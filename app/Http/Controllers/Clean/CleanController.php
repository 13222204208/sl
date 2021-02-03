<?php

namespace App\Http\Controllers\Clean;

use App\Model\User;
use App\Model\Clean;
use App\Model\GetClean;
use App\Exports\CleanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class CleanController extends Controller
{
    public function export() 
    {
        return Excel::download(new CleanExport, '扫楼记录.xlsx');
    }

    public function userinfo()
    {
        $id = session('id');//用户id
        $user = User::find($id);

        return $user;
    }
    public function gainClean(Request $request)
    { 
        if ($request->ajax()) {
            $limit = $request->get('limit'); 
           // $state= $this->userinfo()->hasPermissionTo('导出权限');
            $data = GetClean::with(['companytype:id,type_name','paytype:id,type_name','tenantneed:id,type_name'])->whereIn('permission',$this->userPermission())->orderBy('id','desc')->paginate($limit);
            return $data;
            //return response()->json(['status'=>200,'data'=>$data]);
      
        }
    }

    public function changeHouses(Request $request)
    {
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= DB::table('update_level_name')->orderBy('id','desc')->paginate($limit);

            return $data;
      
        }
    }

    public function searchClean(Request $request)
    {
        $limit= $request->get('limit'); 
        $startTime = $request->get('startTime');
        $stopTime = $request->get('stopTime');

        $data= GetClean::with(['companytype:id,type_name','paytype:id,type_name','tenantneed:id,type_name'])->whereIn('permission',$this->userPermission())->where('created_at','>',$startTime)->where('created_at','<',$stopTime)->paginate($limit);
        return $data;
    }

    public function houseTenant(Request $request)
    {   
        $limit= $request->get('limit'); 
        $name=  $request->get('name');

        $data= GetClean::with(['companytype:id,type_name','paytype:id,type_name','tenantneed:id,type_name'])->whereIn('permission',$this->userPermission())->where('houses_name','like','%'.$name.'%')->orWhere('tenant_name','like','%'.$name.'%')->paginate($limit);

        return $data;
    }

    public function userPermission()//用户部门权限
    {
        $id = session('id');//用户id
        $user = User::find($id);
        $arr = explode(',',$user->branch);
        return $arr;
    }

    public function getPermission()
    {
        $state= $this->userinfo()->hasPermissionTo('导出权限');
        return response()->json(['status'=>200,'state'=> $state]);
    }
}
