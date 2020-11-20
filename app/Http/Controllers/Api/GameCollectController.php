<?php

namespace App\Http\Controllers\Api;

use App\Model\GameCollect;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class GameCollectController extends Controller
{
    public function collect(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'productType' => 'required|max:20',
            'tcgGameCode' => 'required|max:20',
            'productCode' => 'required|max:20',
            'gameName' => 'required|max:30',
        ]);

        $user = JWTAuth::authenticate($request->token);

        $data= GameCollect::where(['username'=>$user->username,'tcgGameCode'=>$request->tcgGameCode])->get();

        if ($data->first()) {

            GameCollect::where('username',$user->username)->where('tcgGameCode',$request->tcgGameCode)->update([
                'state'=>1
            ]);
            return response()->json([
                'code' => 1002,
                'msg' =>"已收藏"
            ],200);
        }
        $collect = new GameCollect;
        $collect->username = $user->username;
        $collect->productType = $request->productType;
        $collect->tcgGameCode = $request->tcgGameCode;
        $collect->productCode = $request->productCode;
        $collect->gameName = $request->gameName;
        $state= $collect->save();
        if ($state) {
            return response()->json([
                'code' => 201,
                'msg' =>"收藏成功"
            ],200);
        }else{
            return response()->json([
                'code' => 0,
                'msg' =>"收藏失败"
            ],200);
        }
    }

    public function cancelCollect(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'tcgGameCode' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        $data= GameCollect::where(['username'=>$user->username,'tcgGameCode'=>$request->tcgGameCode])->get();

        if ($data->first()) {
            GameCollect::where('username',$user->username)->where('tcgGameCode',$request->tcgGameCode)->update([
                'state'=>2
            ]);
            return response()->json([
                'code' => 201,
                'msg' =>"取消收藏成功"
            ],200);
        }

        return response()->json([
            'code' => 0,
            'msg' =>"你没有收藏此游戏"
        ],200);

    }

    public function collectGame(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
        ]);

        $user = JWTAuth::authenticate($request->token);

        $data= GameCollect::where(['username'=>$user->username,'state'=>1])->orderBy('id','desc')->get(['productType','tcgGameCode','productCode','gameName']);

        if ($data->first()) {
            return response()->json([
                'code' => 200,
                'msg' =>"游戏收藏列表",
                'data'=>$data
            ],200);
        }else{
            return response()->json([
                'code' => 0,
                'msg' =>"无收藏的游戏",
            ],200);
        }
    }

}
