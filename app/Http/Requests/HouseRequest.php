<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiBaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class HouseRequest extends ApiBaseRequest
{
    public function rules()
    {
        return [
            'token'=> 'required',
            'houses_name' => 'required|max:200',
            //'houses_info' => 'required|max:200',
            'houses_num' => 'required|max:200',
            'tenant_name' => 'required|max:200',
            'is_we_company' => 'required|max:200',
            'tenant_user' => 'required|max:200',
            'start_time' => 'required|max:200',
            'contract_period' => 'required|max:200',
            'pay_type' => 'required|max:200',
            'position' => 'required|max:200'

        ];
    }
}
