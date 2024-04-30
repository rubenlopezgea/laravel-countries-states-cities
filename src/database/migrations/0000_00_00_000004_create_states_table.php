<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use RubenLopezGea\LaravelCountriesStatesCities\Traits\HasConfigurableTableName;

return new class extends Migration
{
    use HasConfigurableTableName;

    private $table = 'states';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $table = $this->makePrefixed($this->table);
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->makePrefixed($this->table));
    }
};
