<?php

namespace App\Filters\User;

use App\DTOs\BaseFilterDTO;
use App\DTOs\FactoryFloor\FilterDTO as FactoryFloorFilterDTO;
use App\DTOs\User\FilterDTO;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WithTrashedByFactoryFloor extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */

        $floorsFilters = new FactoryFloorFilterDTO(
            user: $filters->user,
            with_trashed_factory: $filters->with_trashed_factory
        );

        $builder->when(
            value: $filters->with_trashed_factory_floor,
            callback: fn(Builder $builder) => $builder->with(
                relations: 'factoryFloors',
                callback: fn(BelongsToMany $belongsToMany) => $belongsToMany->filter($floorsFilters)->withTrashed()
            ),
            default: fn(Builder $builder) => $builder
                ->with(
                    relations: 'factoryFloors',
                    callback: fn(BelongsToMany $belongsToMany) => $belongsToMany->filter($floorsFilters)
                )
                ->where(
                    fn(Builder $builder) => $builder
                        ->whereHas(
                            relation: 'factoryFloors',
                            callback: fn(Builder $builder) => $builder->filter($floorsFilters)
                        )
                        ->orWhereDoesntHave(
                            relation: 'factoryFloors',
                            callback: fn(Builder $builder) => $builder->withoutGlobalScope(SoftDeletingScope::class)
                        )
                )
        );
    }
}
