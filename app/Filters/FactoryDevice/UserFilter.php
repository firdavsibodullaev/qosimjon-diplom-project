<?php

namespace App\Filters\FactoryDevice;

use App\DTOs\BaseFilterDTO;
use App\DTOs\FactoryDevice\FilterDTO;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class UserFilter extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */

        $builder->when(
            value: $filters->factory_user,
            callback: function (Builder $builder) use ($filters) {

                $user = $filters->factory_user->loadMissing('factoryFloors');

                $factory_ids = $user->factoryFloors->pluck('factory_id')->unique() ?: [];
                $factory_floor_ids = $user->factoryFloors->pluck('id')->unique() ?: [];

                $builder->when(
                    value: $user->has_permission_to_all_floors,
                    callback: fn(Builder $builder) => $builder
                        ->whereHas(
                            relation: 'factory',
                            callback: fn(Builder $builder) => $builder
                                ->whereIn('factory_id', $factory_ids)
                                ->with('factory')
                        ),
                    default: fn(Builder $builder) => $builder
                        ->whereIn('factory_floor_id', $factory_floor_ids)
                        ->with('factoryFloor')
                );
            }
        );
    }
}
