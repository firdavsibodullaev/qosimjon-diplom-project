<?php

namespace App\DTOs\FactoryFloor;

use App\DTOs\BaseFilterDTO;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public string $sorter = 'number'
    )
    {
    }
}
