<?php

namespace App\Http\Controllers\Work;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;

class WorkController extends Controller
{
    public function brokerList(Request $request)
    {
        if ($request->ajax()) {  
            $limit = $request->get('limit'); 
            $data= User::where('id','>',1)->paginate($limit);
            return $data; 
        }
    }

    public function queryAccount(Request $request ,$account)
    {
        if ($request->ajax()) {
            $limit = $request->get('limit'); 
            $data= User::where('account',$account)->paginate($limit);
            return $data;
        }
    }
}
