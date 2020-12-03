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
}
