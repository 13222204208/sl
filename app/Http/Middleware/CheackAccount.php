<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheackAccount
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
        $user= JWTAuth::parseToken()->authenticate();
        if ($user->status == 2) {
            return response()->json([
                'code' => -1,
                'msg' => '账户已停用，请联系管理员',
            ], 200);
        }
        return $next($request);
    }
}
