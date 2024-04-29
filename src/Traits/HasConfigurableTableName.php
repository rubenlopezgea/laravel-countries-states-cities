<?php

namespace RubenLopezGea\LaravelCountriesStatesCities\Traits;

use Illuminate\Support\Str;

trait HasConfigurableTableName
{
    public function getTable(): string
    {
        $table = $this->table ?? Str::snake(Str::pluralStudly(class_basename($this)));

        if (strpos($table, '_') !== false) {
            return $table;
        }

        $prefix = config('countries-states-cities.table_prefix', '');
        if (! empty($prefix) && substr($prefix, -1) !== '_') {
            $prefix .= '_';
        }

        return $prefix.$table;
    }
}
