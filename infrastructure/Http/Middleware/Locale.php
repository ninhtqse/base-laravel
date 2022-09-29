<?php

namespace Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Locale
{
    public function __construct(){}

    public function handle($request, Closure $next)
    {
        App::setLocale('vi');
        return $next($request);
    }
}
