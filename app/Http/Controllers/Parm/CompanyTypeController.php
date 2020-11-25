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
            $data= Company::where('parent_id',null)->paginate($limit);

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
            $type_name= array_filter(explode('，',$str));
            
            $pid = intval($request->pid);

            if ($pid == 0) {
                for ($i=0; $i < count($type_name) ; $i++) { 
                  $status=  Company::create(['type_name'=> $type_name[$i]]);
                }
            }else{
               
                for ($i=0; $i < count($type_name) ; $i++) { 
                    $status=  Company::create(['type_name'=> $type_name[$i],'parent_id'=>$pid]);
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
            $company = Company::find($request->id);
            $state= $company->delete();
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                 return response()->json(['status'=>403]);
            }
        }
        
    }
}
