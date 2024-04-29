<?php

namespace RubenLopezGea\LaravelCountriesStatesCities\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RubenLopezGea\LaravelCountriesStatesCities\Models\City;
use RubenLopezGea\LaravelCountriesStatesCities\Models\Country;
use RubenLopezGea\LaravelCountriesStatesCities\Models\Region;
use RubenLopezGea\LaravelCountriesStatesCities\Models\State;
use RubenLopezGea\LaravelCountriesStatesCities\Models\Subregion;

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
        'regions' => Region::class,
        'subregions' => Subregion::class,
        'countries' => Country::class,
        'states' => State::class,
        'cities' => City::class,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up...');
        $bar = $this->output->createProgressBar(count($this->tables));
        foreach (array_reverse($this->tables) as $table => $model) {
            $this->dropTable($table);
            $bar->advance();
        }
        $bar->finish();
        $this->line("\n");

        $this->info('Creating tables and populating them...');
        foreach ($this->tables as $table => $model) {
            $this->createTable($table);
            $this->populateTable($table, $model);
            $this->line("\n");
        }
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

    private function createTable(string $table): void
    {
        $prefixedTable = $this->makePrefixed($table);
        $method = 'createTable'.ucfirst($table);
        $this->info('Creating table '.$prefixedTable.'...');
        $this->$method($prefixedTable);
    }

    private function populateTable(string $table, string $model): void
    {
        DB::disableQueryLog();

        if (! ini_get('auto_detect_line_endings')) {
            ini_set('auto_detect_line_endings', '1');
        }
        ini_set('memory_limit', '-1');

        $filepath = __DIR__.'/../../data/csv/'.$table.'.csv';
        $lines = count(file($filepath)) - 1;

        $bar = $this->output->createProgressBar($lines);
        $headers = false;
        $file = fopen($filepath, 'r');
        while (($line = fgetcsv($file)) !== false) {
            if (! $headers) {
                $headers = $line;

                continue;
            }
            $data = array_combine($headers, $line);
            if (isset($data['timezones'])) {
                $data['timezones'] = json_decode($data['timezones']);
            }
            if (isset($data['translations'])) {
                $data['translations'] = json_decode($data['translations']);
            }
            foreach ($data as $key => $value) {
                if (empty($value) || $value === 'null') {
                    $data[$key] = null;
                }
            }
            try {
                $model::create($data);
            } catch (\Exception $e) {
                dump($data);
                $this->error($e->getMessage());
                exit();
            }
            $bar->advance();
        }
        $bar->finish();

        return;

        $data = File::get(__DIR__.'/../../data/'.$table.'.json');
        if (! $data) {
            $this->error('No data found for '.$table);

            return;
        }
        $bar = $this->output->createProgressBar(count(json_decode($data, true)));
        $data = json_decode($data, true);
        foreach ($data as $obj) {
            $model::create((array) $obj);
            $bar->advance();
        }
        $bar->finish();
    }

    private function createTableRegions(string $table): void
    {
        Schema::create($table, function ($table) {
            $table->id();
            $table->string('name', 100);
            $table->json('translations')->nullable();
            $table->boolean('flag')->default(true);
            $table->string('wikiDataId', 255)->nullable();
            $table->timestamps();
        });
    }

    private function createTableSubregions(string $table): void
    {
        Schema::create($table, function ($table) {
            $table->id();
            $table->string('name', 100);
            $table->json('translations')->nullable();
            $table->foreignId('region_id')->constrained($this->makePrefixed('regions'));
            $table->boolean('flag')->default(true);
            $table->string('wikiDataId', 255)->nullable();
            $table->timestamps();
        });
    }

    private function createTableCountries(string $table): void
    {
        Schema::create($table, function ($table) {
            $table->id();
            $table->string('name', 100);
            $table->string('iso3', 3)->nullable();
            $table->string('numeric_code', 3)->nullable();
            $table->string('iso2', 2)->nullable();
            $table->string('phonecode', 255)->nullable();
            $table->string('capital', 255)->nullable();
            $table->string('currency', 255)->nullable();
            $table->string('currency_name', 255)->nullable();
            $table->string('currency_symbol', 255)->nullable();
            $table->string('tld', 255)->nullable();
            $table->string('native', 255)->nullable();
            $table->string('region', 255)->nullable();
            $table->foreignId('region_id')->nullable()->constrained($this->makePrefixed('regions'));
            $table->string('subregion', 255)->nullable();
            $table->foreignId('subregion_id')->nullable()->constrained($this->makePrefixed('subregions'));
            $table->string('nationality', 255)->nullable();
            $table->json('timezones')->nullable();
            $table->json('translations')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('emoji', 191)->nullable();
            $table->string('emojiU', 191)->nullable();
            $table->boolean('flag')->default(true);
            $table->string('wikiDataId', 255)->nullable();
            $table->timestamps();
        });
    }

    private function createTableStates(string $table): void
    {
        Schema::create($table, function ($table) {
            $table->id();
            $table->string('name', 100);
            $table->foreignId('country_id')->constrained($this->makePrefixed('countries'));
            $table->string('country_code', 2)->nullable();
            $table->string('country_name', 255)->nullable();
            $table->string('state_code', 255)->nullable();
            $table->string('type', 191)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('flag')->default(true);
            $table->string('wikiDataId', 255)->nullable();
            $table->timestamps();
        });
    }

    private function createTableCities(string $table): void
    {
        Schema::create($table, function ($table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('state_id')->constrained($this->makePrefixed('states'));
            $table->string('state_code', 255)->nullable();
            $table->foreignId('country_id')->constrained($this->makePrefixed('countries'));
            $table->string('country_code', 2)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('flag')->default(true);
            $table->string('wikiDataId', 255)->nullable();
            $table->timestamps();
        });
    }
}
