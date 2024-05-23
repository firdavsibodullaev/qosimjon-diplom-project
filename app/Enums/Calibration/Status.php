<?php

namespace App\Enums\Calibration;


use App\Traits\InteractsWithEnums;

enum Status: string
{
    use InteractsWithEnums;

    case NEW = 'new';
    case PROCESS = 'process';
    case REVIEWED = 'reviewed';

    public function text(): string
    {
        return match ($this) {
            self::NEW => 'Yangi',
            self::PROCESS => 'Ko\'rib chiqish jarayonida',
            self::REVIEWED => 'Ko\'rib chiqildi',
        };
    }
}
