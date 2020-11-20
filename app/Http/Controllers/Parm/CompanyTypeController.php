<?php

namespace App\Http\Controllers\Parm;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Model\Company;

use function PHPSTORM_META\type;

class CompanyTypeController extends Controller
{
    public function gainCompany(Request $request)
    { 
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Company::where('parent_id',0)->paginate($limit);

            return $data;
      
        }
    }

    public function gainCompanyType(Request $request,$pid)
    { 
       
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Company::where('parent_id',$pid)->paginate($limit);

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
                $Company = Company::find($pid);
                $state = rtrim($Company->tree,'/').'/'.strval($pid).'/';
                //return response()->json(['status'=>$tree]);
            }
           
            for ($i=0; $i < count($type_name) ; $i++) { //指添加分类数据
                $data[$i]['type_name'] = $type_name[$i];
                $data[$i]['parent_id'] = $pid;
                $data[$i]['tree'] = $state;
            }
            $status = Company::insert($data);
           
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
            $Company = Company::find($request->id);
            $Company->type_name= $request->type_name;
            if ($Company->save()) {
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
          
            
           Company::where('tree','like',$tree)->delete();
           $state= Company::destroy($request->id);
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
        
    }
}
