<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class AdminMiddleware
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
        
        $route = Route::currentRouteName();

        $user= User::find(session('id'));
    
        if (session('id') != 1) {
            
            if ($permission = Permission::where('route', $route)->first()) {
                if (!$user->hasPermissionTo($permission->id)) {
                    return response()->view('errors.403', ['status' => "权限不足，需要：{$permission->name}权限"]);
                }
            }else{
                return response()->view('errors.403', ['status' => "没有找到：{$route}权限"]);
            }
        }

        return $next($request);
        
    }
}
