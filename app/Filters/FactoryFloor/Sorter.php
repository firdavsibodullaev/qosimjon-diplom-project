<?php

namespace App\Filters\FactoryFloor;

use App\DTOs\BaseFilterDTO;
use App\DTOs\FactoryFloor\FilterDTO;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class Sorter extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */
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
