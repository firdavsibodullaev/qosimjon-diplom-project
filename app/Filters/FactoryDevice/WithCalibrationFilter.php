<?php

namespace App\Filters\FactoryDevice;

use App\DTOs\BaseFilterDTO;
use App\DTOs\FactoryDevice\FilterDTO;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class WithCalibrationFilter extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */
        $builder->when(
            value: $filters->with_calibration,
            callback: fn(Builder $builder) => $builder->with(
                $filters->all_calibrations
                    ? [
                    'calibrations.applicant',
                    'calibrations.checker',
                    'calibrations.checkerFactory',
                    'calibrations.media'
                ]
                    : [
                    'lastCalibration.applicant',
                    'lastCalibration.checker',
                    'lastCalibration.checkerFactory',
                    'lastCalibration.media'
                ]
            )
        );
    }
}
