<?php

namespace App\Filters\Calibration;

use App\DTOs\Application\FilterDTO;
use App\DTOs\BaseFilterDTO;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class FilterTesterUser extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */

        $user = $filters->tester_user;

        $user?->loadMissing('factoryFloors.factoryRelation');

        $factory_id = $user?->factoryFloors->first()?->factoryRelation->id;


        $builder->when(
            value: $user && $factory_id,
            callback: fn(Builder $builder) => $builder->where($this->column, '=', $factory_id)
        );
    }
}
