<?php

declare(strict_types=1);

namespace RoiCore\Domain\Enums;

enum ReportStatus: string
{
    case PENDING = 'PENDING';
    case VERIFIED = 'VERIFIED';
    case REJECTED = 'REJECTED';
    case RESOLVED = 'RESOLVED';

    public function labelThai(): string
    {
        return match ($this) {
            self::PENDING => 'รอการตรวจสอบ',
            self::VERIFIED => 'ตรวจสอบแล้ว',
            self::REJECTED => 'ปฏิเสธ',
            self::RESOLVED => 'สถานการณ์คลี่คลายแล้ว',
        };
    }
}
