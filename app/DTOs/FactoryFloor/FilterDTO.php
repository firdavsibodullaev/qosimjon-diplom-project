<?php

namespace App\DTOs\FactoryFloor;

use App\DTOs\BaseFilterDTO;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public string $sorter = 'id',
        public ?int   $factory_id = null,
        public bool   $list = false,
        public bool   $with_trashed_factory = false
    )
    {
        parent::__construct($this->sorter);
    }
}
