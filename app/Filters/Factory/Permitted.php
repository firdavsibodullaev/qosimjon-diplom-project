<?php

namespace App\Filters\Factory;

use App\DTOs\BaseFilterDTO;
use App\DTOs\Factory\FilterDTO;
use App\Enums\Factory\FactoryType;
use App\Enums\Role\Role;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class Permitted extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */
        $user = $filters->user?->load('factoryFloors.factoryRelation');

        $role = Role::tryFrom($user?->getRoleNames()->first());

        /** @var FactoryType|null $type */
        $type = $user->factoryFloors->first()?->factoryRelation->type;

        $builder->when(
            value: $user && $role->is(Role::DIRECTOR) && $type?->is(FactoryType::GIVING_FOR_TEST),
            callback: fn(Builder $builder) => $builder->whereKey($user->factoryFloors->pluck('factory_id')->unique())
        );
    }
}
