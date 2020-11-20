<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiBaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends ApiBaseRequest
{
    public function rules()
    {
        return [
            'token' => 'required',
            'card_name'=> 'required|regex:/^[\x{4e00}-\x{9fa5}]{2,4}$/u',
            'bank_name' => 'required|min:4|max:16',
            'card_num' => 'required|min:16|max:19',

        ];
    }

    public function messages()
    {
       return [
            'card_name.required' => '请填写持卡人姓名',
            'card_num.required' => '请填写正确的银行卡号'
        ];
    }

}
