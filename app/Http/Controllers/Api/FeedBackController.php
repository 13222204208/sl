<?php

namespace App\Http\Controllers\Api;

use App\Model\Feedback;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\FeedBackRequest;
use App\Http\Controllers\UploadController;

class FeedBackController extends Controller
{
    public function feedback(FeedBackRequest $request)
    {
        $user = JWTAuth::authenticate($request->token);
        $feedback = new Feedback;
        $feedback->username = $user->username;
        $feedback->feedback_type = intval($request->feedback_type);
        $feedback->feedback_content = $request->feedback_content;
        if ($request->img_url) { 
            $upload= new UploadController;
            $images = $request->file('img_url');
            $pathUrls = [];
            if (is_array($images)) {
                foreach($images as $key=>$v)
                {
                    $namePath= $upload->uploadImg($images[$key],'FeedBackImg');
                    $img_url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$namePath;
                    array_push($pathUrls,$img_url);
    
                }
            }else {
                $namePath= $upload->uploadImg($images,'FeedBackImg');
                    $img_url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$namePath;
                    array_push($pathUrls,$img_url);
            }

            
            if (!$namePath) {
                return response()->json(['msg' =>'图片上传失败', 'code' => 0]);
            }
            //$pathUrls=response()->json($pathUrls);
            $pathUrls = implode(',',$pathUrls);
            //$img_url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$namePath;
            $feedback->img_url=  $pathUrls;
        }

        $state= $feedback->save();

        if ($state) {
            return response()->json(['code' => 200,'msg'=>'成功']);
        } else {
            return response()->json(['msg' =>'失败', 'code' => 0]);
        }   
    }

    public function myFeedback(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        $data = Feedback::where('username',$user->username)->orderBy('created_at','desc')->get(['username','feedback_type','feedback_content','img_url','state','created_at']);
        if ($data) {
            return response()->json(['msg'=>'成功','code'=>200, 'data'=>$data]);
        }else {
            return response()->json(['msg' =>'无数据', 'code' => 0]);
        }  
        
    }
}
