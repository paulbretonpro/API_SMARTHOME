<?php

namespace App\Traits;

use Carbon\Carbon;

trait DatetimeTrait
{
    public function getDatetime()
    {
        $currentTime = Carbon::now();

        $currentTime->setMicrosecond(0);
        $currentTime->setSecond(0);
        $currentTime->setMinute(0);

        return $currentTime;
    }
}
