<?php

declare(strict_types=1);

namespace RoiCore\Domain\Interfaces;

use RoiCore\Domain\ValueObjects\GeoPoint;
use RoiCore\Domain\ValueObjects\WeatherData;

interface IWeatherService
{
    public function getCurrentWeather(GeoPoint $location): WeatherData;
}
