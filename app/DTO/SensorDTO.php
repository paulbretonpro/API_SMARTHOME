<?php

namespace App\DTO;

use DateTime;

class SensorDTO
{
    public function __construct(public float $temperature, public float $humidity, public DateTime $datetime)
    {
    }
}
