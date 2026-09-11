<?php

namespace AMovil\Modules\Shared\Infrastructure;

use Illuminate\Support\ServiceProvider;

class LaravelModulesProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \AMovil\Modules\PsoModules\Domain\PsoModuleRepository::class,
            \AMovil\Modules\PsoModules\Infrastructure\EloquentPsoModuleRepository::class
        );
    }
}
