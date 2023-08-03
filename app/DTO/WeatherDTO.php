<?php

namespace App\DTO;

use DateTime;

class WeatherDTO
{
    public function __construct(public int $feels_like, public int $humidity, public int $temperature, public int $wind_speed, public DateTime $datetime)
    {
    }
}
