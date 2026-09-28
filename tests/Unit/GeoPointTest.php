<?php

declare(strict_types=1);

namespace RoiCore\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class GeoPointTest extends TestCase
{
    public function testValidCoordinatesCanBeInstantiated(): void
    {
        $point = new GeoPoint(13.7563, 100.5018);

        $this->assertSame(13.7563, $point->latitude);
        $this->assertSame(100.5018, $point->longitude);
    }

    public function testInvalidLatitudeThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new GeoPoint(95.0, 100.0);
    }

    public function testInvalidLongitudeThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new GeoPoint(13.0, 185.0);
    }

    public function testDistanceCalculation(): void
    {
        $bangkok = new GeoPoint(13.7563, 100.5018);
        $nonthaburi = new GeoPoint(13.8591, 100.5217);

        $distance = $bangkok->distanceTo($nonthaburi);

        $this->assertGreaterThan(10000, $distance); // ~11-12 km
        $this->assertLessThan(15000, $distance);
    }
}
