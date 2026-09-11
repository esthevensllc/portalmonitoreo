<?php

namespace AMovil\Fija\Shared\Infrastructure;

use Illuminate\Support\ServiceProvider;

class FijaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \AMovil\Fija\OperacionFija\Domain\FuenteFijaRepository::class,
            \AMovil\Fija\OperacionFija\Infrastructure\EloquentFuenteFijaRepository::class
        );
        $this->app->bind(
            \AMovil\Fija\OperacionFijaPolyline\Domain\FuenteFijaEnlaceRepository::class,
            \AMovil\Fija\OperacionFijaPolyline\Infrastructure\EloquentFuenteFijaEnlaceRepository::class
        );
    }
}
