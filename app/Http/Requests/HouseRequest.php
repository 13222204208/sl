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
            'position' => 'required|max:200'

        ];
    }
}
