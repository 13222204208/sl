<?php

namespace App\Http\Controllers\Broker;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class BrokerController extends Controller
{
    public function addAccount(Request $request)//添加帐号
    {
        if ($request->ajax()) {
            try {
                $request->validate([
                    'account' => 'required|unique:users|max:20',
                ]); 
            } catch (\Throwable $th) {
                return response()->json(['status'=>403]);
            }

            $user= User::create([
                'account'=>$request->account,
                'name' => $request->name,
                'password' => encrypt($request->password)
            ]); 
        
            $roles= $request->limits;
            
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
            return response()->json(['status'=>200]);
        }
    }

    public function addRole(Request $request)//添加角色
    {
        try {
            $request->validate([
                'name' => 'required|unique:roles|max:12',
            ]); 
        } catch (\Throwable $th) {
            return response()->json(['status'=>403]);
        }
        $role = Role::create(['name' => $request->name]);

        $permissions= $request->limits;
        
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }
       
            return response()->json(['status'=>200]);
    }


    public function haveRole(Request $request)//获取当前用户所关联的角色
    {
        $user= User::find($request->id);
        $have = array();
        $roles= Role::select('name')->get();

        foreach ($roles as $role) {//判断角色是否拥有此权限

            $state= $user->hasAnyRole($role['name']);
            if ($state) {
                $have[]= $role['name'];
            }
        }
        return response()->json(['status'=>200,'data'=>$have]);

    }

    public function updateRole(Request $request)//更新用户的角色
    {  
        $user= User::find($request->id);
        $roles= $request->role;

        if (isset($roles)) {    
            $user->roles()->detach(); // 如果没有选择任何与用户关联的角色则将之前关联角色解除
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
        } 


        return response()->json(['status'=>200]);
      
    }

    public function havePermission(Request $request)//获取当前角色的权限
    {
        $role= Role::find($request->id);
        $have = array();
        $permissions= Permission::select('name')->get();
        foreach ($permissions as $permission) {//判断角色是否拥有此权限

            $state= $role->hasPermissionTo($permission['name']);
            if ($state) {
                $have[]= $permission['name'];
            }
        }
        return response()->json(['status'=>200,'data'=>$have]);

    }

    public function allPermission()//获取所有权限名称
    {
        $permissions = Permission::select('name')->get();
        if ($permissions) {
            return response()->json(['status'=>200,'data'=>$permissions]);
        }else{
            return response()->json(['status'=>403]);
        }

    }

    public function delPermission(Request $request)//删除一个权限
    {
        if ($request->ajax()) {
            $id= $request->input('id');
            $state= Permission::destroy($id);
        }

        if ($state) {
            return response()->json(['status'=>200]);
        }else{
            return response()->json(['status'=>403]);
        }

    }

    public function updatePname(Request $request)//更新一个权限的名称
    {
        if ($request->ajax()) {
            $permission= Permission::find(intval($request->id));
            $permission->name = $request->name;
            $permission->route = $request->route;

            if ($permission->save()) {
                return response()->json(['status'=>200]);
            }else{
                return response()->json(['status'=>403]);
            }

        }
    }

    public function updatePermission(Request $request)//更新角色的权限范围
    {  
        $role= Role::find($request->id);
        $permissions= $request->permission;
        
        $p_all = Permission::all();
        foreach ($p_all as $p) {
            $role->revokePermissionTo($p);
        }
        
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        return response()->json(['status'=>200]);
      
    }

    public function addPower(Request $request)//添加权限 
    {
        $role = Permission::create(['name' => $request->name,'route'=>$request->route]);
       
        if ($role) {
            return response()->json(['status'=>200]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function queryRole(Request $request)
    {
        $limit = $request->get('limit');
        $roles = Role::paginate($limit);//获取所有角色列表
        return $roles;
    }

    public function queryAccount(Request $request)
    {
        $limit = $request->get('limit');
        $data= DB::table('users')->select('id','account','name')->paginate($limit);
        return $data;
    }

    public function addRoleScope(Request $request)//给角色添加权限范围
    {
        if ($request->ajax()) {
            $role= Role::where('name',$request->rolename)->first();
            $role->syncPermissions($request->limits);

            if ($role) {
                return response()->json(['status'=>200]);
            }else{
                return response()->json(['status'=>403]);
            }

        }
    }

    public function queryPermission(Request $request)
    {   
        $limit = $request->get('limit');
        $permissions = Permission::paginate($limit);//获取所有角色
        return $permissions; 
    }

    public function delAccount(Request $request)
    {
        if ($request->ajax()) {
            $id= $request->input('id');
            $state= DB::table('users')->where('id',$id)->delete();
        }

        if ($state) {
            return response()->json(['status'=>200]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function delRole(Request $request)
    {
        if ($request->ajax()) {
            $id= $request->input('id'); 
            $state = Role::destroy($id);
            if ($state) {
                return response()->json(['status'=>200]);
            }else{
                return response()->json(['status'=>403]);
            }
        }
    }

    public function gainRole()//获取所有角色名
    {
        $role_name= Role::select('name')->get();

        if ($role_name) {
            return response()->json(['role_name'=>$role_name,'status'=>200]);
        }else{
            return response()->json(['role_name'=>$role_name,'status'=>403]);
        }
    }

    public function gainPower()//获取所有权限名称
    {
        $role_name= Permission::select('name')->get();

        if ($role_name) {
            return response()->json(['role_name'=>$role_name,'status'=>200]);
        }else{
            return response()->json(['role_name'=>$role_name,'status'=>403]);
        }
    }

  
}
