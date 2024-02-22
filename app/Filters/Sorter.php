<?php

namespace App\Filters;

use App\DTOs\BaseFilterDTO;
use Illuminate\Database\Eloquent\Builder;

class Sorter extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        $builder->when(
            value: $filters->sorter,
            callback: fn(Builder $builder) => $builder->orderBy(
                column: str($filters->sorter)->replace('-', ''),
                direction: !str($filters->sorter)->match('/^\-/')->toString() ? 'asc' : 'desc'
            ),
            default: fn(Builder $builder) => $builder->orderBy('id')
        );
    }
}
