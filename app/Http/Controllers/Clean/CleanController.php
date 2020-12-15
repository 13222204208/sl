<?php

namespace App\Http\Controllers\Clean;

use App\Model\Clean;
use App\Model\GetClean;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CleanController extends Controller
{
    public function gainClean(Request $request)
    { 
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= GetClean::orderBy('id','desc')->paginate($limit);

            return $data;
      
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

        $data= GetClean::where('created_at','>',$startTime)->where('created_at','<',$stopTime)->paginate($limit);
        return $data;
    }

    public function houseTenant(Request $request)
    {   
        $limit= $request->get('limit'); 
        $name=  $request->get('name');

        $data= GetClean::where('houses_name','like','%'.$name.'%')->orWhere('tenant_name','like','%'.$name.'%')->paginate($limit);

        return $data;
    }
}
