<?php

namespace Infrastructure\Auth\Requests;

use Infrastructure\Http\ApiRequest;

class ResetPasswordRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token'    => ['required'],
            'password' => ['required'],
        ];
    }

    public function attributes()
    {
        return [
            'token'         => __('token'),
            'password'      => __('password'),
        ];
    }
}
