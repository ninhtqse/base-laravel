<?php

namespace Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Infrastructure\Libraries\Adapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('production') || $this->app->environment('staging')) {
            $this->app['request']->server->set('HTTPS', true);
        }
        // không chạy migration mặc định trong passport
        \Laravel\Passport\Passport::ignoreMigrations();

        $this->app->singleton('Adapter', function ($app) {
            return new Adapter;
        });
    }
}
