<?php

namespace App\Auths\Requests;

use Infrastructure\Http\WebRequest;

class LoginRequest extends WebRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'  => 'required|email|max:255',
            'password'  => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'email'  => __('email'),
            'password'  => __('password'),
        ];
    }
}
