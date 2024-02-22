<?php

namespace App\Filters\FactoryFloor;

use App\DTOs\BaseFilterDTO;
use App\DTOs\FactoryFloor\FilterDTO;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class FilterByFactoryId extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */
        $builder->when(
            value: $filters->factory_id,
            callback: fn(Builder $builder) => $builder->where($this->column, $filters->factory_id)
        );
    }
}
