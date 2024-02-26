<?php

namespace App\DTOs\Factory;

use App\DTOs\BaseFilterDTO;
use App\Models\User;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public ?User  $user = null,
        public string $sorter = 'id',
        public bool   $list = false
    )
    {
        parent::__construct($this->sorter);
    }
}
