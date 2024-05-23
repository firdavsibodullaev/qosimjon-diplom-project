<?php

namespace App\Enums\FactoryDevice;

use App\Traits\InteractsWithEnums;

enum Position: string
{
    use InteractsWithEnums;

    case WAREHOUSE = 'warehouse';
    case ON_PLACE = 'on-place';
    case CHECKING = 'checking';
}
