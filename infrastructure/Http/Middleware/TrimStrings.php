<?php

namespace Infrastructure\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'user.new_password',
        'user.password',
        'password',
        'password_confirmation',
    ];
}
