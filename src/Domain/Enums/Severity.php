<?php

declare(strict_types=1);

namespace RoiCore\Domain\Enums;

enum Severity: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;

    public function labelThai(): string
    {
        return match ($this) {
            self::LOW => 'ระดับต่ำ',
            self::MEDIUM => 'ระดับปานกลาง',
            self::HIGH => 'ระดับวิกฤต',
        };
    }
}
