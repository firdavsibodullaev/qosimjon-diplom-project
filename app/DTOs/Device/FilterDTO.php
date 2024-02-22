<?php

namespace App\DTOs\Device;

use App\DTOs\BaseFilterDTO;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(string $sorter = 'id')
    {
        parent::__construct($sorter);
    }
}
