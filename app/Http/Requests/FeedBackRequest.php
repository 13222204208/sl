<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiBaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class FeedBackRequest extends ApiBaseRequest
{
    public function rules()
    {
        return [
            'token'=> 'required',
            'feedback_type' => 'required|max:1',
            'feedback_content' => 'required|min:20|max:200',
        ];
    }
}
