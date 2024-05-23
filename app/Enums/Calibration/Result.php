<?php

namespace App\Enums\Calibration;

use App\Traits\InteractsWithEnums;

enum Result: string
{
    use InteractsWithEnums;

    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function text(): string
    {
        return match ($this) {
            self::APPROVED => 'Tasdiqlandi',
            self::REJECTED => 'Rad etildi',
        };
    }
}
