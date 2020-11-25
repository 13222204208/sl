<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\BgUser;
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
            return redirect()->to('login');
        }

        
        return $next($request);
    }
}
