<?php

namespace App\DTOs\FactoryDevice;

use App\DTOs\BaseFilterDTO;
use App\Models\User;

class FilterDTO extends BaseFilterDTO
{
    public function __construct(
        public ?string $sorter = 'id',
        public int     $total = 20,
        public ?User   $factory_user = null,
        public bool    $list = false,
        public bool    $with_calibration = false,
        public bool    $all_calibrations = false,
        public bool    $checking_important = false,
    )
    {
        parent::__construct($sorter);
    }
}
