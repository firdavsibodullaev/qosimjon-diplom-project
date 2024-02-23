<?php

namespace App\DTOs\Device;

use Illuminate\Support\Collection;

class DevicePayloadDTO
{
    public function __construct(
        public string     $name,
        public Collection $attributes
    )
    {
        $this->attributes->ensure(DeviceAttributesDTO::class);
    }
}
