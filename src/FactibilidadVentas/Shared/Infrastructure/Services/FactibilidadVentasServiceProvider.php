<?php

namespace AMovil\FactibilidadVentas\Shared\Infrastructure\Services;

use Illuminate\Support\ServiceProvider;

class FactibilidadVentasServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \AMovil\FactibilidadVentas\CoberturaFija\Domain\CoberturaFijaRepository::class,
            \AMovil\FactibilidadVentas\CoberturaFija\Infrastructure\EloquentCoberturaFijaRepository::class
        );

        $this->app->bind(
            \AMovil\Maps\AfectacionEnergia\Domain\AfectacionEnergiaRepository::class,
            \AMovil\Maps\AfectacionEnergia\Infrastructure\EloquentAfectacionEnergiaRepository::class
        );
        $this->app->bind(
            \AMovil\Maps\OoklaMap\Domain\OoklaMapRepository::class,
            \AMovil\Maps\OoklaMap\Infrastructure\ClickhouseOoklaMapRepository::class
        );
        $this->app->bind(
            \AMovil\Maps\OoklaMapFija\Domain\OoklaMapFijaRepository::class,
            \AMovil\Maps\OoklaMapFija\Infrastructure\EloquentOoklaMapFijaRepository::class
        );
    }
}
