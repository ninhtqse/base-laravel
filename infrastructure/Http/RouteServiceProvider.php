<?php

namespace Infrastructure\Http;

use Ninhtqse\Api\System\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $router = $this->app->make(Router::class);
        parent::boot($router);
        $this->map($router);
    }

    public function map(Router $router)
    {
        $config = $this->app['config']['components'];
        $middleware = $config['protection_middleware'];
        $basicMiddleware = $config['protection_basic_middleware'];
        
        $highLevelParts = array_map(function ($namespace) {
            return glob(sprintf('%s%s*', $namespace, DIRECTORY_SEPARATOR), GLOB_ONLYDIR);
        }, $config['namespaces']);
        
        foreach ($highLevelParts as $part => $partComponents) {
            $custom = $middleware;
            if($part == 'App'){
                $custom = 'auth:web';
            }
            foreach ($partComponents as $componentRoot) {
                $component = substr($componentRoot, strrpos($componentRoot, DIRECTORY_SEPARATOR) + 1);
                
                $namespace = sprintf(
                    '%s\\%s\\Controllers',
                    $part,
                    $component
                );

                $fileNames = [
                    'routes' => 2,
                    'routes_protected' => 2,
                    'routes_public' => 0,
                    'routes_basic' => 1,
                ];

                foreach ($fileNames as $fileName => $protected) {
                    $path = sprintf('%s/%s.php', $componentRoot, $fileName);

                    if (!file_exists($path)) {
                        continue;
                    }

                    $router->group([
                        'middleware' => ($protected === 2) ? $custom : ($protected === 1 ? $basicMiddleware : []),
                        'namespace'  => $namespace,
                        'prefix'     => $config['prefix'],
                    ], function ($router) use ($path) {
                        require $path;
                    });
                }
            }
        }
    }
}
