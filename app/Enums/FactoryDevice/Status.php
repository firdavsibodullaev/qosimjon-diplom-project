<?php

namespace App\Enums\FactoryDevice;

use App\Traits\InteractsWithEnumValues;

enum Status: string
{
    use InteractsWithEnumValues;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
