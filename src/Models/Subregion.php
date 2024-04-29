<?php

namespace RubenLopezGea\LaravelCountriesStatesCities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RubenLopezGea\LaravelCountriesStatesCities\Traits\HasConfigurableTableName;

class Subregion extends Model
{
    use HasConfigurableTableName;
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'translations',
        'region_id',
        'flag',
        'wikiDataId',
    ];

    protected $casts = [
        'translations' => 'json',
        'flag' => 'boolean',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }
}
