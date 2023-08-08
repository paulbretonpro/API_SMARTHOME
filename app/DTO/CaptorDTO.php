<?php

namespace App\DTO;

use App\Traits\DatetimeTrait;
use Carbon\Carbon;
use DateTime;

class CaptorDTO
{
    use DatetimeTrait;

    public float $consumption;
    public DateTime $datetime;

    public function __construct(string $consumption, Carbon $datetime)
    {
        $this->consumption = floatval($consumption);
        $this->datetime = $this->getDatetime($datetime);
    }
}
