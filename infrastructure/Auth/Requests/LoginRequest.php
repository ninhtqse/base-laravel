<?php

namespace Infrastructure\Auth\Requests;

use Infrastructure\Http\ApiRequest;

class LoginRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'         => 'required|max:255',
            'password'      => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'email'         => __('email'),
            'password'      => __('password'),
        ];
    }
}
