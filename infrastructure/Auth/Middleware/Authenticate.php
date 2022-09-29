<?php

namespace Infrastructure\Auth\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        if(Auth::check()){
            return $next($request);
        }else{
            return redirect(constants('uri.login'));
        }
    }
}
