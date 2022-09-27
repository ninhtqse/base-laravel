<?php

namespace Infrastructure\Auth\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, $scopesString = null)
    {
        return $next($request);
    }
}
