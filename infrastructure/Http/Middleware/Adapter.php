<?php

namespace Infrastructure\Http\Middleware;

use Illuminate\Support\Facades\App;
use Illuminate\Routing\Router;
use Closure;

class Adapter
{

    protected $router;

    protected $adapter;

    public function __construct(Router $router){
        $this->router  = $router;
        $this->adapter = App::make('Adapter');
    }

    public function handle($request, Closure $next)
    {
        $actionName = $this->router->getRoutes()->match($request)->getActionName();
        $explode    = explode("\\",$actionName);
        $folderName = array_shift($explode);
        if($folderName == 'App'){
            $this->adapter->withWeb();
        }else{
            $this->adapter->withApi();
        }
        return $next($request);
    }
}
