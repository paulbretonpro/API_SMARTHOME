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
    /** @var date */
    public $date_start;
    /** @var date */
    public $date_end;
    /** @var date */
    public $date;
    /** @var "asc"|"desc" */
    public $orderBy;
    /** @var int */
    public $perPage;
}
