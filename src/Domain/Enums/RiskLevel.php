<?php

declare(strict_types=1);

namespace RoiCore\Domain\Enums;

enum RiskLevel: string
{
    case LOW = 'LOW';
    case MEDIUM = 'MEDIUM';
    case HIGH = 'HIGH';
    case CRITICAL = 'CRITICAL';

    public function labelThai(): string
    {
        return match ($this) {
            self::LOW => 'ความเสี่ยงต่ำ',
            self::MEDIUM => 'ความเสี่ยงปานกลาง',
            self::HIGH => 'ความเสี่ยงสูง',
            self::CRITICAL => 'ความเสี่ยงวิกฤต',
        };
    }
}
