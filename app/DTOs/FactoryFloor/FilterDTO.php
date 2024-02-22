<?php

namespace App\DTOs\FactoryFloor;

use App\DTOs\BaseFilterDTO;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public string $sorter = 'number',
        public ?int   $factory_id = null,
        public bool   $list = false
    )
    {
    }
}
