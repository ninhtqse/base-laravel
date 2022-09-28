<?php

namespace Infrastructure\Auth\Middleware;

use Closure;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next, $scopesString = null)
    {
        return $next($request);
    }
}
