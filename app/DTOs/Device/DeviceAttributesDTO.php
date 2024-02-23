<?php

namespace App\DTOs\Device;

class DeviceAttributesDTO
{
    public function __construct(
        public string|int $attribute,
        public string     $measurement_unit,
        public string|int $value,
    )
    {
    }
}
