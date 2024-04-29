<?php

declare(strict_types=1);

namespace RubenLopezGea\LaravelCountriesStatesCities\Providers;

use Illuminate\Support\ServiceProvider;
use RubenLopezGea\LaravelCountriesStatesCities\Console\Commands\MigrateCountriesStatesCitiesTables;

final class LaravelCountriesStatesCitiesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/countries-states-cities.php' => config_path('countries-states-cities.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands(commands: [
                MigrateCountriesStatesCitiesTables::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/countries-states-cities.php',
            'countries-states-cities'
        );
    }
}
