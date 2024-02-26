<?php

namespace App\Filters\Factory;

use App\DTOs\BaseFilterDTO;
use App\DTOs\User\FilterDTO;
use App\Enums\Role\Role;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class Permitted extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */

        $user = $filters->user->load('factoryFloors.factoryRelation');
        $role = Role::tryFrom($user->getRoleNames()->first());
        $builder->when(
            value: $role->is(Role::DIRECTOR),
            callback: fn(Builder $builder) => $builder->whereKey($user->factoryFloors->pluck('factory_id'))
        );
    }
}
