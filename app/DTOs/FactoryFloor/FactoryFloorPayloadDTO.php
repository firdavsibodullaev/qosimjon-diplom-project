<?php

namespace App\DTOs\FactoryFloor;

class FactoryFloorPayloadDTO
{
    public function __construct(
        public string $name,
        public int    $number,
        public int    $factory_id
    )
    {
    }
}
