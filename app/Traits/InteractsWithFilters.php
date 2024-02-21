<?php

namespace App\Traits;

use App\DTOs\BaseFilterDTO;
use Illuminate\Database\Eloquent\Builder;

trait InteractsWithFilters
{
    public function scopeFilter(Builder $builder, BaseFilterDTO $filters): void
    {
        foreach ($this->filters as $class => $column) {
            (new $class($column))->filter($builder, $filters);
        }
    }
}
