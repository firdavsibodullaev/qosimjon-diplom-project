<?php

namespace App\DTOs\Factory;

use App\DTOs\BaseFilterDTO;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public string $sorter = 'id',
        public bool   $list = false
    )
    {
        parent::__construct($this->sorter);
    }
}
