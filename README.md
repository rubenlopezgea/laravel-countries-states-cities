# Laravel Countries, States and Cities

Creates tables and models with all available data about the world.

## Configuration

You can dad a prefix to the tables generated by this module.

Publish the config to `config/countries-states-cities.php` including `table_prefix`.

To do this you can publish the config file:

`php artisan vendor:publish  --provider="RubenLopezGea\LaravelCountriesStatesCities\Providers\LaravelCountriesStatesCitiesServiceProvider"`

## Create and populate tables

`php artisan migrate:countries-states-cities-tables`

## Use the data

You've got available the following Models

- `RubenLopezGea\LaravelCountriesStatesCities\Models\Region`
- `RubenLopezGea\LaravelCountriesStatesCities\Models\Subregion`
- `RubenLopezGea\LaravelCountriesStatesCities\Models\Country`
- `RubenLopezGea\LaravelCountriesStatesCities\Models\State`
- `RubenLopezGea\LaravelCountriesStatesCities\Models\City`