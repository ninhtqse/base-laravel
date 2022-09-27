<?php

namespace Infrastructure\Auth\Middleware;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Infrastructure\Exceptions as Exception;
use Illuminate\Foundation\Application;
use Closure;

class AccessTokenChecker
{
    private $app;

    /**
     * list route name
     */
    private $ignore = [];

    public function __construct(
        Application $app,
        Authenticate $authenticate
    ) {
        $this->app = $app;
        $this->authenticate = $authenticate;
    }

    public function handle($request, Closure $next, $scopesString = null)
    {
        $rq_token = $request->bearerToken();
        $this->checkHeaders();
        if ($this->app->environment() !== 'testing') {
            try {
                return $this->authenticate->handle($request, $next, 'api');
            } catch (AuthenticationException $e) {
                if ($rq_token) {
                    $token = $this->decodeToken($rq_token);
                    if ($token) {
                        $exp = $token->exp;
                        if ($exp < time()) {
                            throw new Exception\GeneralException('AWE009');
                        }
                    }
                }
                throw new Exception\GeneralException('AWE005');
            }
        }
        return $next($request);
    }

    //===================> SUPPORT METHOD <==============================
    private function checkHeaders()
    {
        $ignore_url = [
            domain().'/broadcasting/auth'
        ];
        $route_name = request()->route()->getName();
        if (in_array($route_name, $this->ignore) || in_array(request()->url(), $ignore_url)) {
            return;
        }
        if (request()->getMethod() != 'GET' && request()->getMethod() != 'DELETE') {
            $headers     = request()->header();
            $type_header = \Config('config.header_default_api');
            if (@$headers['accept'][0] != $type_header) {
                throw new Exception\GeneralException('AWE015');
            }
            if (@$headers['content-type'][0] != $type_header) {
                throw new Exception\GeneralException('AWE014');
            }
        }
    }

    private function decodeToken($token)
    {
        $explode = explode('.', $token);
        if (@$explode[1]) {
            return json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', $explode[1]))));
        }
    }
}
