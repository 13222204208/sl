<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if (!(session('account'))) {
            return redirect('login');
        }

        

       
        $url=  Request::path(); // echo("<script>window.alert('$url')</script>");
        $arr = array();
        if ($url != '/' ) {
            $arr=explode("/",$url);
            if (session('id') != 1) {
                $role = User::find(session('id'));
                $data = DB::table('bg_roles')->where('role_name',$role->role)->value('role_scope');
                $data = json_decode($data,true);
                array_push($data,'home');
                $state= in_array($arr[0],$data);
                if ($state == false) {                 
                    return redirect()->to('404');
                }
            }
           
        }
        return $next($request);
    }
}
