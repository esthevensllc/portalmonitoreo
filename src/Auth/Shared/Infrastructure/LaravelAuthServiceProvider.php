<?php

namespace AMovil\Auth\Shared\Infrastructure;

use Illuminate\Support\ServiceProvider;

class LaravelAuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Access control
        $this->app->bind(\AMovil\Auth\AccessControl\Domain\AuthService::class, function($app){
            return new \AMovil\Auth\AccessControl\Infrastructure\Services\AuthCentral(
                env('CAS_URL_AUTH'),
                env('CAS_SESSION_NAME'),
                env('CAS_API_KEY'),
                env('CAS_APP_SECRET'),
                env('CAS_REDIRECT_TO'),
                new \AMovil\Shared\Infrastructure\Session\LaravelSession()
            );
        });
    }
}
