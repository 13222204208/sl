<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiBaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePassRequest extends ApiBaseRequest
{
    /**
     * 获取应用于请求的验证规则
     *
     *
     */
    public function rules()
    {
        return [
            'newpass' => 'required|min:6|max:12|alpha_num',
            'password' => 'required|min:6|max:12|alpha_num',
        ];
    }

    public function messages()
    {
       return [
            'newpass.alpha_num' => '只允许字母和数字',
            'password.alpha_num'=> '只允许字母和数字',
        ];
    }
}
