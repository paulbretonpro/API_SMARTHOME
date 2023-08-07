<?php

namespace App\DTO;

use DateTime;

class WeatherDTO
{
    public function __construct(public float $feels_like, public float $humidity, public float $temperature, public int $wind_speed, public DateTime $datetime)
    {
    }
}
