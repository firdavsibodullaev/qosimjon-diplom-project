<?php

namespace App\DTOs\Factory;

use App\DTOs\BaseFilterDTO;
use App\Enums\Factory\FactoryType;
use App\Models\User;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public ?User        $user = null,
        public ?string      $sorter = 'id',
        public bool         $list = false,
        public ?FactoryType $type = null
    )
    {
        parent::__construct($this->sorter);
    }
}
