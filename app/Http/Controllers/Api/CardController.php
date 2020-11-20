<?php

namespace App\Http\Controllers\Api;

use App\Model\BankCard;
use Illuminate\Http\Request;
use App\Http\Requests\CardRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class CardController extends Controller
{
    public function addCard(CardRequest $request)//添加银行卡
    {
        $card = new BankCard;
        $user = JWTAuth::authenticate($request->token);

        $card->username = $user->username;
        $card->card_name = $request->card_name;
        $card->bank_name = $request->bank_name;

        $card->card_num = $request->card_num;
        $card->subsidiaryBank = $request->subsidiaryBank;
        $card->subbranch = $request->subbranch;
        $card->province = $request->province;
        $card->city = $request->city;

        $state = $card->save();


        if ($state) {
            return response()->json(['msg' => '成功','code' => 200],200);
        } else {
            return response()->json([
                'code' => 0,
                'msg' => "失败",
            ], 200);
        }
    }

    public function lookCard(Request $request)//查看银行卡
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user = JWTAuth::authenticate($request->token);

        $data= BankCard::where('username',$user->username)->where('state',1)->get(['id','card_num','bank_name','username',
            'subsidiaryBank','subbranch','province','city'
        ]);

        if ($data) {
            return response()->json(['msg' => '成功','data'=>$data,'code' => 200],200);
        } else {
            return response()->json([
                'code' => 0,
                'msg' => "失败",
            ], 200);
        }
    }

    public function removeCard(Request $request)//移除银行卡
    {
        $this->validate($request, [
            'token' => 'required',
            'id' => 'required'
        ]);
        $user = JWTAuth::authenticate($request->token);

        $card = BankCard::find($request->id);
        $card->state = 0;
        $state = $card->save();

        if ($state) {
            return response()->json(['msg' => '成功','code' => 200],200);
        } else {
            return response()->json([
                'code' => 0,
                'msg' => "失败",
            ], 200);
        }
    }
}
