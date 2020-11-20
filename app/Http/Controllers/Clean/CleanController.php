<?php

namespace App\Http\Controllers\Clean;

use App\Model\Clean;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CleanController extends Controller
{
    public function gainClean(Request $request)
    { 
        if ($request->ajax()) {
            $limit = $request->get('limit');
            $data= Clean::paginate($limit);

            return $data;
      
        }
    }
}
