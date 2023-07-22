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
}
