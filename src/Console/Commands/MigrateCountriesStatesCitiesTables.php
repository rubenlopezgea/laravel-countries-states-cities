<?php

namespace RubenLopezGea\LaravelCountriesStatesCities\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use RubenLopezGea\LaravelCountriesStatesCities\Database\Seeders\CitiesSeeder;
use RubenLopezGea\LaravelCountriesStatesCities\Database\Seeders\CountriesSeeder;
use RubenLopezGea\LaravelCountriesStatesCities\Database\Seeders\RegionsSeeder;
use RubenLopezGea\LaravelCountriesStatesCities\Database\Seeders\StatesSeeder;
use RubenLopezGea\LaravelCountriesStatesCities\Database\Seeders\SubregionsSeeder;

class MigrateCountriesStatesCitiesTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:countries-states-cities-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tables if doesn\'t exist and populate them';

    private $tables = [
        'regions' => [
            'migration' => '0000_00_00_000001_create_regions_table',
            'seeder' => RegionsSeeder::class,
        ],
        'subregions' => [
            'migration' => '0000_00_00_000002_create_subregions_table',
            'seeder' => SubregionsSeeder::class,
        ],
        'countries' => [
            'migration' => '0000_00_00_000003_create_countries_table',
            'seeder' => CountriesSeeder::class,
        ],
        'states' => [
            'migration' => '0000_00_00_000004_create_states_table',
            'seeder' => StatesSeeder::class,
        ],
        'cities' => [
            'migration' => '0000_00_00_000005_create_cities_table',
            'seeder' => CitiesSeeder::class,
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('This command requires at least 1.5Gb of free memory to run.');
        $this->warn('If you have less than that, you may run into memory issues.');
        $this->info('The system will try to set the memory limit to -1, but it may not work.');
        $this->warn('If you run into memory issues, try increasing the memory limit in your php.ini file.');
        $this->info('Currently you\'ve got set: '.ini_get('memory_limit').' of memory limit.');
        $this->line('');
        $this->line('');
        $this->info('Cleaning up...');
        foreach (array_reverse($this->tables) as $table => $data) {
            $this->dropTable($table);
        }

        $this->info('Creating tables and populating them...');
        $total = count($this->tables);
        $current = 0;
        foreach ($this->tables as $table => $data) {
            $current++;
            $counter = '['.$current.'/'.$total.'] ';
            $this->info($counter.$table);
            $this->createTable($table, $data);
            $this->populateTable($table, $data);
        }
        $this->line('');
        $this->info('Done!');
        $this->info('Memory used: '.(memory_get_peak_usage(true) / 1024 / 1024).' MB');
    }

    private function makePrefixed(string $table): string
    {
        if (strpos($table, '_') !== false) {
            return $table;
        }
        $prefix = config('countries-states-cities.table_prefix', '');
        if (! empty($prefix) && substr($prefix, -1) !== '_') {
            $prefix .= '_';
        }

        return $prefix.$table;
    }

    private function dropTable(string $table): void
    {
        $prefixedTable = $this->makePrefixed($table);
        if (Schema::hasTable($prefixedTable)) {
            Schema::drop($prefixedTable);
        }
    }

    private function createTable(string $table, array $data): void
    {
        $migration_file = __DIR__.'/../../Database/Migrations/'.$data['migration'].'.php';
        $migrator = new (require $migration_file)();
        $migrator->up();
    }

    private function populateTable(string $table, array $model): void
    {
        if (! ini_get('auto_detect_line_endings')) {
            ini_set('auto_detect_line_endings', '1');
        }
        ini_set('memory_limit', '-1');

        Artisan::call('db:seed', [
            '--class' => $model['seeder'],
        ]);
    }
}
