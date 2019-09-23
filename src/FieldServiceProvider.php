<?php

namespace KuznetsovZfort\NovaFreshdeskButtons;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/nova-freshdesk-buttons.php' => config_path('nova-freshdesk-buttons.php'),
        ], 'nova-freshdesk-buttons');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-freshdesk-buttons', __DIR__.'/../dist/js/field.js');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/nova-freshdesk-buttons.php', 'nova-freshdesk-buttons');
        Route::middleware(['nova'])
            ->prefix('kuznetsov-zfort/nova-freshdesk-buttons')
            ->group(__DIR__ . '/../routes/api.php');
    }
}
