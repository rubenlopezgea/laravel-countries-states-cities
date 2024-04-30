<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use RubenLopezGea\LaravelCountriesStatesCities\Traits\HasConfigurableTableName;

return new class extends Migration
{
    use HasConfigurableTableName;

    private $table = 'subregions';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $table = $this->makePrefixed($this->table);
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->makePrefixed($this->table));
    }
};
