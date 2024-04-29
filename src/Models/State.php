<?php

namespace RubenLopezGea\LaravelCountriesStatesCities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RubenLopezGea\LaravelCountriesStatesCities\Traits\HasConfigurableTableName;

class State extends Model
{
    use HasConfigurableTableName;
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'country_id',
        'country_code',
        'country_name',
        'state_code',
        'type',
        'latitude',
        'longitude',
        'flag',
        'wikiDataId',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'flag' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
