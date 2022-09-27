<?php

namespace Infrastructure\Auth\Requests;

use Infrastructure\Http\ApiRequest;

class ChangePasswordRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password_old'  => 'required',
            'password_new'  => 'required|min:8|max:36',
        ];
    }

    public function attributes()
    {
        return [
            'password_old'  => __('password_old'),
            'password_new'  => __('password_new'),
        ];
    }
}
