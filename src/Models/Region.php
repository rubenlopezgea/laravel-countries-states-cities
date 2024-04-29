<?php

namespace RubenLopezGea\LaravelCountriesStatesCities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RubenLopezGea\LaravelCountriesStatesCities\Traits\HasConfigurableTableName;

class Region extends Model
{
    use HasConfigurableTableName;
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'translations',
        'flag',
        'wikiDataId',
    ];

    protected $casts = [
        'translations' => 'json',
        'flag' => 'boolean',
    ];

    public function subregions(): HasMany
    {
        return $this->hasMany(Subregion::class);
    }

    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }
}
