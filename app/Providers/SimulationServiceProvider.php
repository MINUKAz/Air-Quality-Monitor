<?php

namespace App\Providers;

use App\Services\SimulationService;
use Illuminate\Support\ServiceProvider;

class SimulationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('simulationService', function ($app) {
            return new SimulationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
