<?php

namespace App\DTOs\FactoryDevice;

use App\Enums\FactoryDevice\Position;
use App\Enums\FactoryDevice\Status;

class DevicePayloadDTO
{
    public function __construct(
        public int      $device_id,
        public int      $number,
        public int      $factory_id,
        public Status   $status,
        public Position $position,
        public int      $check_every_time,
        public ?int     $factory_floor_id = null,
    )
    {
    }
}
