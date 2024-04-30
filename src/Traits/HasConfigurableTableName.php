<?php

namespace RubenLopezGea\LaravelCountriesStatesCities\Traits;

use Illuminate\Support\Str;

trait HasConfigurableTableName
{
    private function makePrefixed($table): string
    {
        if (strpos($table, '_') !== false) {
            return $table;
        }
        $prefix = config('countries-states-cities.table_prefix', '');
        if (! empty($prefix)) {
            if (substr($prefix, -1) !== '_') {
                $prefix .= '_';
            }

            return $prefix.$table;
        }

        return $table;
    }

    public function getTable(): string
    {
        $table = $this->table ?? Str::snake(Str::pluralStudly(class_basename($this)));

        return $this->makePrefixed($table);
    }
}
