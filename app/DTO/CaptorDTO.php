<?php

namespace App\DTO;

use Carbon\Carbon;

class CaptorDTO
{

    public function __construct(public float $consumption, public Carbon $datetime)
    {
    }
}
