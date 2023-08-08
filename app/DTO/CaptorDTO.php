<?php

namespace App\DTO;

use Carbon\Carbon;

class CaptorDTO
{
    public float $consumption;

    public function __construct(string $consumption, public Carbon $datetime)
    {
        $this->consumption = floatval($consumption);
    }
}
