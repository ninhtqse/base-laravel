<?php

namespace Infrastructure\Http\Middleware;

use Closure;
use Infrastructure\Exceptions as EfyException;

class Locale
{
    public function __construct()
    {
    }

    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
