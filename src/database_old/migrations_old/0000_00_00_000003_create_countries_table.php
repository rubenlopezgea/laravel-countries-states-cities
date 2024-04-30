<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use RubenLopezGea\LaravelCountriesStatesCities\Traits\HasConfigurableTableName;

return new class extends Migration
{
    use HasConfigurableTableName;

    private $table = 'countries';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $table = $this->makePrefixed($this->table);
        Schema::create($table, function ($table) {
            $table->id();
            $table->string('name', 100);
            $table->string('iso3', 3)->nullable();
            $table->string('iso2', 2)->nullable();
            $table->string('numeric_code', 3)->nullable();
            $table->string('phone_code', 255)->nullable();
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->makePrefixed($this->table));
    }
};
