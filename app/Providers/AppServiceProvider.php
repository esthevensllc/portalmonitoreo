<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make(\AMovil\Shared\Infrastructure\LaravelSharedServiceProvider::class, ['app' => $this->app])->register();
        $this->app->make(\AMovil\Modules\Shared\Infrastructure\LaravelModulesProvider::class, ['app' => $this->app])->register();
        $this->app->make(\AMovil\Auth\Shared\Infrastructure\LaravelAuthServiceProvider::class, ['app' => $this->app])->register();
        $this->app->make(\AMovil\Fija\Shared\Infrastructure\FijaServiceProvider::class, ['app' => $this->app])->register();
        $this->app->make(\AMovil\FactibilidadVentas\Shared\Infrastructure\Services\FactibilidadVentasServiceProvider::class, ['app' => $this->app])->register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view){
            $view_name = str_replace('.', '-', $view->getName());
            view()->share('view_name', $view_name);
        });

        \URL::forceRootUrl(config('app.url'));  
    }
}
