<?php

namespace App\DTOs\Factory;

use App\Enums\Factory\FactoryType;

class FactoryPayloadDTO
{
    public function __construct(
        public string      $name,
        public int         $number,
        public FactoryType $type
    )
    {
    }
}
