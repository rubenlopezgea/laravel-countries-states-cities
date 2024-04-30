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

        $migrations_base = __DIR__.'/../Database/Migrations/';
        $this->publishesMigrations([
            $migrations_base.'0000_00_00_000001_create_regions_table.php' => database_path('migrations/'.now()->format('Y_m_d_His').'_create_regions_table.php'),
            $migrations_base.'0000_00_00_000002_create_subregions_table.php' => database_path('migrations/'.now()->format('Y_m_d_His').'_create_subregions_table.php'),
            $migrations_base.'0000_00_00_000003_create_countries_table.php' => database_path('migrations/'.now()->format('Y_m_d_His').'_create_countries_table.php'),
            $migrations_base.'0000_00_00_000004_create_states_table.php' => database_path('migrations/'.now()->format('Y_m_d_His').'_create_states_table.php'),
            $migrations_base.'0000_00_00_000005_create_cities_table.php' => database_path('migrations/'.now()->format('Y_m_d_His').'_create_cities_table.php'),
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
