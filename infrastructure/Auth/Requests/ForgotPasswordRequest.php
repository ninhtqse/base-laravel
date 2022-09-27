<?php

namespace Infrastructure\Auth\Requests;

use Infrastructure\Http\ApiRequest;

class ForgotPasswordRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'         => 'required|email|max:255'
        ];
    }

    public function attributes()
    {
        return [
            'email'         => __('email'),
        ];
    }
}
