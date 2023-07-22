<?php

namespace App\Filters;

use App\Filters\DataTransferObject;

class WeatherFilter extends DataTransferObject
{
    /** @var float */
    public $temperature;
    /** @var float */
    public $humidity;
    /** @var float */
    public $feels_like;
    /** @var float */
    public $wind_speed;
}
