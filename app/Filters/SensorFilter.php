<?php

namespace App\Filters;

use App\Models\Sensor;
use App\Filters\DataTransferObject;

class SensorFilter extends DataTransferObject
{
    /** @var float */
    public $temperature;
    /** @var float */
    public $humidity;
    /** @var date */
    public $date_start;
    /** @var date */
    public $date_end;
    /** @var date */
    public $date;
}
