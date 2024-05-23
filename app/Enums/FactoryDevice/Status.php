<?php

namespace App\Enums\FactoryDevice;

use App\Traits\InteractsWithEnums;

enum Status: string
{
    use InteractsWithEnums;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
