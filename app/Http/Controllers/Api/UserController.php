<?php

namespace App\Http\Controllers\Api;

use App\Model\Activity;
use App\Model\UserInfo;
use App\Model\UserDetail;
use App\Model\DelActivity;

use App\Model\LoginRecord;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\UpdatePassRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UploadController;
use App\Http\Requests\RegisterAuthRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\UserDetailAuthRequest;

class UserController extends Controller
{
    public $loginAfterSignUp = true;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        return response()->json([
            'code' => 201,
            'msg' =>"注册成功",
            'data' => $request->username
        ],200);

        $data = $request->all();
        $validator = Validator::make($data, [
            'key' => 'required',
            'regCode' => 'required|captcha_api:' . $request->input('key')
        ]);//注册验证码

        if ($validator->fails()) {
            return [
                'code' => 0, 'msg' => '验证码不匹配'
            ];
        }

     /*    if (!captcha_api_check($request->regCode, $request->key)){
            return response()->json([
                'code' => 0, 'msg' => '验证码不匹配'
            ]);
         } */

        if ($request->register_ip) {//判断ip
            if(!filter_var($request->register_ip, FILTER_VALIDATE_IP)) {
                $request->register_ip ="错误的ip格式";
            }
        }else{
            $request->register_ip ="无法获取ip";
        }

            $user = new UserInfo;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->register_ip = $request->register_ip;
            $user->register_time = date('Y-m-d H:i:s',time());
            $user->save();//保存注册用户

            $detail = new UserDetail;
            $detail->username = $request->username;
            $detail->save();//保存注册用户详细信息

            if ($this->loginAfterSignUp) {
                return $this->login($request);//注册完成后直接登陆
            }

            return response()->json([
                'code' => 201,
                'msg' =>"注册成功",
                'data' => $user
            ],200);
    }

    public function login(Request $request)
    {
        $input = $request->only('username', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'code' => 0,
                'msg' => '用户名或者密码错误',
            ], 200);
        }
        $detail = UserDetail::where('username',$request->username)->first();
        LoginRecord::insert([
            'username' => $detail->username,
            'login_ip'=>$request->login_ip,
            'login_time'=> $request->login_time
        ]);//保存登陆用户的ip和登录时间

        UserInfo::where('username',$detail->username)->update(['login_time'=>$request->login_time]);
//更新用户的最后登录时间

        return response()->json([
            'code' => 201,
            'msg' =>"成功",
            'data'=>$detail,
            'token' => $jwt_token,
        ],200);
    }


    public function update(UserDetailAuthRequest $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        $detail = UserDetail::where('username',$user->username)->first();
//更新用户信息
            if ($request->true_name) {
                if ($detail->true_name) {
                    return response()->json([
                        'code' => 0,
                        'msg' =>"真实姓名不能修改",
                    ],200);
                }
            }

            if ($request->date_brith) {
                if ($detail->date_brith) {
                    return response()->json([
                        'code' => 0,
                        'msg' =>"出生日期不能修改",
                    ],200);
                }
            }


            $res = $request->except(['token']);
            $updated = $detail->fill($res)->save();

            if ($updated) {
                return response()->json([
                    'code' => 200,
                    'msg' =>"成功",
                    'data'=>$detail
                ],200);
            } else {
                return response()->json([
                    'code' => 0,
                    'msg' =>"更改失败",
                ],200);
            }

    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'code' => 200,
                'msg' => '用户退出成功'
            ],200);
        } catch (JWTException $exception) {
            return response()->json([
                'code' => 0,
                'msg' => '对不起，用户无法注销'
            ], 200);
        }
    }

    public function newpass(UpdatePassRequest $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        $pass = UserInfo::where('username',$user->username)->first();
//修改用户密码
        if (password_verify($request->password ,$pass->password)) {
            $pass->password = bcrypt($request->newpass);
            $state= $pass->save();
            if ($state) {
                return response()->json([
                    'msg' =>'修改成功',
                    'code' => 200
                ],200);
            }
        }else {
            return response()->json([
                'msg' =>'原始密码不正确',
                'code' => 0
            ],200);
        }
    }

    public function uploadUserHead(Request $request)
    {
        $upload= new UploadController;
        $namePath= $upload->uploadImg($request->file('file'),'UserHeadImg');//上传用户头像1,图片,2图片存放目录
        $namePath = 'http://'.$_SERVER['HTTP_HOST'].'/'.$namePath;//图片访问地址
        if ($namePath) {
            $this->validate($request, [
                'token' => 'required'
            ]);

            $user = JWTAuth::authenticate($request->token);
            if (!$user) {
                return response()->json(['msg' =>'找不到用户', 'code' => 0]);
            }
            UserDetail::where('username',$user->username)->update([
                'user_head'=> $namePath
            ]);
            return response()->json(['user_head' =>$namePath, 'code' => 200,'msg'=>'上传成功']);
        } else {
            return response()->json(['msg' =>'上传失败', 'code' => 0]);
        }
    }

    public function myActivity(Request $request)//活动
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        if ($request->id) {
            $delActivity= new DelActivity;
            $delActivity->del_id = $request->id;
            $delActivity->username = $user->username;
            $delActivity->type = "activity";
            $delActivity->save();
        }

        $delID= DelActivity::where('username',$user->username)->get('del_id')->toArray();
        $del= array_column($delID,'del_id');
        if ($delID) {

            $str = implode(',',$del);
            $id = explode(',',$str);
            $data = Activity::whereDate('created_at','>',$user->register_time)->whereNotIn('id',$id)
            ->get(['id','activity_title',
        'activity_img','activity_describe','activity_sort','created_at']);

        return response()->json( ['msg'=>'成功','data'=>$data,'code'=>200]);
        }
        $data = Activity::whereDate('created_at','>',$user->register_time)->get(['id','activity_title',
        'activity_img','activity_describe','activity_sort','created_at']);

        return response()->json( ['msg'=>'成功','data'=>$data,'code'=>200]);
  /*       if ($data->first()) {
            return response()->json( ['msg'=>'成功','data'=>$data,'code'=>200]);
        }else {
            return response()->json([
                'code' => 0,
                'msg' =>"无符合条件数据",
            ],200);
        } */
    }

    public function regCode()
    {
        return response()->json([
            'code' => 200,
            'msg' => '创建成功',
            'url' => app('captcha')->create('default', true)
        ]);
    }

    public function validatePasswd(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'token' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'code' => 0,
                'msg' => '参数不正确'
            ];
        }

        $user = JWTAuth::authenticate($request->token);

        if (password_verify($request->password ,$user->password)) {
            return [
                'code' => 200,
                'msg' => '密码验证通过'
            ];
        }else{
            return [
                'code' => 0,
                'msg' => '密码错误'
            ];
        }
    }


    public function amount(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user = JWTAuth::authenticate($request->token);
        $username = $user->username;

        $amount = UserDetail::where('username',$username)->value('balance');

        return response()->json([
            'code' => 200,
            'msg' => '成功',
            'amount' => $amount
        ]);
    }

}
