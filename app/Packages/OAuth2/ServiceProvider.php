<?php

namespace App\Packages\OAuth2;

use App\Packages\OAuth2\Middleware\OAuth2Middleware;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/package/config.php', 'oauth2');

        /** @var Router $router */
        $router = $this->app['router'];

        // Roles & permission middlewares
        $router->aliasMiddleware('oauth2', OAuth2Middleware::class);
    }

    public function boot(): void
    {
        $config = $this->app->make(Repository::class);

        if ($config->get('oauth2.auto_register_routes')) {
            $this->loadRoutesFrom(__DIR__.'/package/routes.php');
        }

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/package/migrations');
        }
    }
}
