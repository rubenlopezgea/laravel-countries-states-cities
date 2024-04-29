<?php

namespace RubenLopezGea\LaravelCountriesStatesCities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RubenLopezGea\LaravelCountriesStatesCities\Traits\HasConfigurableTableName;

class Country extends Model
{
    use HasConfigurableTableName;
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'iso3',
        'numeric_code',
        'iso2',
        'phonecode',
        'capital',
        'currency',
        'currency_name',
        'currency_symbol',
        'tld',
        'native',
        'region',
        'region_id',
        'subregion',
        'subregion_id',
        'nationality',
        'timezones',
        'translations',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'flag',
        'wikiDataId',
    ];

    protected $casts = [
        'translations' => 'json',
        'timezones' => 'json',
        'latitude' => 'float',
        'longitude' => 'float',
        'flag' => 'boolean',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function subregion(): BelongsTo
    {
        return $this->belongsTo(Subregion::class);
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function provinces(): HasMany
    {
        return $this->hasMany(State::class)->where(function ($query) {
            return $query->where('type', 'province')->orWhere('type', 'LIKE', 'autonomous city%');
        });
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
