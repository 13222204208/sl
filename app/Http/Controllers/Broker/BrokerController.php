<?php

namespace App\Http\Controllers\Broker;

use App\Model\BgUser;
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
                    'account' => 'required|unique:users|max:12',
                    'name' => 'required|unique:users|max:12',
                ]); 
            } catch (\Throwable $th) {
                return response()->json(['status'=>403]);
            }
            $role= Role::where('name',$request->role_name)->first();
       
            $id= DB::table('users')->insertGetId([
                'account'=>$request->input('account'),
                'name'=>$request->input('name'),
                'password'=>encrypt($request->input('password')),
                'role_id' => $role->id
            ]);
/*             $user= BgUser::where('id',$id)->first();
            $user->assignRole('编辑');
            $user= Role::find(1);
            $permissions = $user->permissions; */

            if ($id) {
                return response()->json(['status'=>200]);
            }else{
                return response()->json(['status'=>403]);
            }
           
        }
    }

    public function addRole(Request $request)//添加角色
    {
        $role = Role::create(['name' => $request->role_name]);
       
        if ($role) {
            return response()->json(['status'=>200]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function addPower(Request $request)//添加权限 
    {
        $role = Permission::create(['name' => $request->name]);
       
        if ($role) {
            return response()->json(['status'=>200]);
        }else{
            return response()->json(['status'=>403]);
        }
    }

    public function queryRole(Request $request)
    {
        $limit = $request->get('limit');
        $roles = Role::paginate($limit);//获取所有角色
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

    public function queryAccountRole(Request $request,$role)
    {
        $limit = $request->get('limit');
        $data= DB::table('bg_users')->where('role','=',$role)->select('id','account_num','nickname','role','state')->paginate($limit);
        return $data;
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
            $state= DB::table('users')->where('id',$id)->delete();
        }

        if ($state) {
            return response()->json(['status'=>200]);
        }else{
            return response()->json(['status'=>403]);
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
