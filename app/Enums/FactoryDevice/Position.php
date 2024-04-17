<?php

namespace App\Enums\FactoryDevice;

use App\Traits\InteractsWithEnumValues;

enum Position: string
{
    use InteractsWithEnumValues;

    case WAREHOUSE = 'warehouse';
    case ON_PLACE = 'on-place';
    case CHECKING = 'checking';
}
