<?php

namespace Infrastructure\Http;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Contracts\Validation\Validator;
use Infrastructure\Exceptions as IncException;
use Illuminate\Foundation\Http\FormRequest;

abstract class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        if (method_exists($this, 'createLog')) {
            $this->createLog();
        }
        $message = $validator->errors()->first();
        throw new IncException\GeneralException("AWE004", $message);
    }

    protected function failedAuthorization()
    {
        if (method_exists($this, 'createLog')) {
            $this->createLog();
        }
        throw new HttpException(403);
    }
}
