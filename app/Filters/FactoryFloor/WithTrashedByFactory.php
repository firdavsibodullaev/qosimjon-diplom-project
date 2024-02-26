<?php

namespace App\Filters\FactoryFloor;

use App\DTOs\BaseFilterDTO;
use App\DTOs\FactoryFloor\FilterDTO;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithTrashedByFactory extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */
        $builder->when(
            value: $filters->with_trashed_factory,
            callback: fn(Builder $builder) => $builder->with(
                relations: 'factoryRelation',
                callback: fn(BelongsTo $belongsTo) => $belongsTo->withTrashed()
            ),
            default: fn(Builder $builder) => $builder->with('factoryRelation')->has('factoryRelation')
        );
    }
}
