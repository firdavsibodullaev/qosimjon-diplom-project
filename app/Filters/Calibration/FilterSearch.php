<?php

namespace App\Filters\Calibration;

use App\DTOs\Application\FilterDTO;
use App\DTOs\BaseFilterDTO;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class FilterSearch extends BaseFilter
{
    public function filter(Builder $builder, BaseFilterDTO $filters): void
    {
        /** @var FilterDTO $filters */

        $builder->when(
            value: $filters->q,
            callback: fn(Builder $builder) => $builder->where('id', '=', $this->getFilterValue($filters->q))
        );
    }

    private function getFilterValue(string $q)
    {
        $q = filter_var($q, FILTER_VALIDATE_INT);

        if ($q === false) {
            return 0;
        }

        return $q;
    }
}
