<?php

namespace App\Http\Controllers\Api;

use App\Model\Notice;
use App\Model\Affiche;
use App\Model\Support;
use App\Model\Activity;
use App\Model\DelNotice;
use App\Model\AppVersion;
use App\Model\UserDetail;
use App\Model\RotationChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class ContentController extends Controller
{
    public function rotation()
    {
        $data = RotationChart::where('state',1)->get(['img_url','jump_url','title','img_sort']);
        if ($data) {
            return response()->json([
                'code' => 200,
                'msg' =>"成功",
                'data' => $data
            ],200);
        }else {
            return response()->json([
                'code' => 0,
                'msg' =>"无数据",
            ],200);
        }
    }

    public function activity()
    {
        $data = Activity::where('activity_state',1)->get(['id','activity_type','activity_title',
        'activity_img','activity_url','label_img','activity_sort','start_time','stop_time']);

        if ($data) {
            return response()->json( ['msg'=>'成功','data'=>$data,'code'=>200]);
        }else {
            return response()->json([
                'code' => 0,
                'msg' =>"无数据",
            ],200);
        }
    }

    public function defaultHead()
    {
        $data = DB::table('f_default_head')->select('default_head')->get();
        if ($data) {
            return response()->json(['msg'=>'成功','data'=>$data,'code'=>200],200);
        }else {
            return response()->json([
                'code' => 0,
                'msg' =>"无数据",
            ],200);
        }
    }

    public function affiche(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $data= Affiche::orderBy('created_at','desc')->get(['id','affiche_title','affiche_content','great_affiche','created_at']);
        if ($data) {
            return response()->json(['msg'=>'成功','data'=>$data,'code'=>200],200);
        }else {
            return response()->json([
                'code' => 0,
                'msg' =>"无数据",
            ],200);
        }
    }

    public function notice(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user = JWTAuth::authenticate($request->token);
        $username = $user->username;
        if ($request->id) {
            $DelNotice= new DelNotice;
            $DelNotice->del_id = $request->id;
            $DelNotice->username = $user->username;
            $DelNotice->type = "notice";
            $DelNotice->save();
        }

        $delID= DelNotice::where('username',$user->username)->get('del_id')->toArray();

        if ($delID) {
            $del= array_column($delID,'del_id');
            $str = implode(',',$del);
            $id = explode(',',$str);

            $notice = new Notice;
            $all = "all";
            $data= $notice->orderBy('created_at','desc')->whereNotIn('id',$id)->whereDate('created_at','>',$user->register_time) ->when($all, function ($query) use ($all) {
                $query->where('notice_receive','=', $all);
            })->orWhere('notice_receive','=', $username)->get(['id','notice_title','notice_content','state','created_at']);


            if ($data) {
                return response()->json(['msg'=>'成功','data'=>$data,'code'=>200],200);
            }else {
                return response()->json([
                    'code' => 0,
                    'msg' =>"无数据",
                ],200);
            }
        }


        $notice = new Notice;
        $all = "all";
        $data= $notice->orderBy('created_at','desc')->whereDate('created_at','>',$user->register_time) ->when($all, function ($query) use ($all) {
            $query->where('notice_receive','=', $all);
        })->orWhere('notice_receive','=', $username)->get(['id','notice_title','notice_content','state','created_at']);


        if ($data) {
            return response()->json(['msg'=>'成功','data'=>$data,'code'=>200],200);
        }else {
            return response()->json([
                'code' => 0,
                'msg' =>"无数据",
            ],200);
        }
    }

    public function support()
    {
        $data = Support::where('state',1)->get(['app_img_url','title',
        'link_url','sort']);

        if ($data) {
            return response()->json( ['msg'=>'成功','data'=>$data,'code'=>200]);
        }else {
            return response()->json([
                'code' => 0,
                'msg' =>"无数据",
            ],200);
        }
    }

    public function version()
    {
        $data = AppVersion::where('id',1)->get(['new_version','update_content',
        'is_update','compel_update','ios_href','android_href']);

        if ($data) {
            return response()->json( ['msg'=>'成功','data'=>$data,'code'=>200]);
        }else {
            return response()->json([
                'code' => 0,
                'msg' =>"无数据",
            ],200);
        }
    }

}
