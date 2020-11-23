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
            'account' => 'required|unique:users|min:11|max:11',
            'password' => 'required|min:6|max:30',
        
        ];
    }

    public function messages()
    {
       return [
            'account.required' => '请先填写用户名',
            'account.unique' => '用户名重复',
        ];
    }

}
