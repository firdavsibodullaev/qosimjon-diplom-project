<?php

namespace App\Services;

use App\DTOs\ApplicationView\FilterDTO;
use App\Enums\Role\Role;
use App\Models\Calibration;
use Illuminate\Database\Eloquent\Builder;

class ApplicationViewService
{
    public function paginate(FilterDTO $filter)
    {
        $filter->user->load('factoryFloors.factoryRelation');
        $user = $filter->user;
        $factory_id = $user->factoryFloors->first()?->factory_id;

        return Calibration::query()
            ->when(
                $filter->user->hasRole(Role::DIRECTOR),
                fn(Builder $builder) => $builder->where('applicant_factory_id', $factory_id)
            )
            ->when(
                $filter->user->hasRole(Role::MODERATOR),
                fn(Builder $builder) => $builder->whereHas('device', fn(Builder $builder)=> $builder)
            );
    }
}
