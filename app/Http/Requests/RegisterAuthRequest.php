<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiBaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class RegisterAuthRequest extends ApiBaseRequest
{

    /**
     * 获取应用于请求的验证规则
     *
     *
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:f_userinfo|min:4|max:12|alpha_num',//只允许数字和字母
            'password' => 'required|min:6|max:12|alpha_num',
            'regCode' => 'required',
            'key' => 'required'
        ];
    }

    public function messages()
    {
       return [
            'username.required' => '请先填写用户名',
            'username.unique' => '用户名重复',
            'username.alpha_num'=> '用户名只允许数字和字母',
        ];
    }

}
