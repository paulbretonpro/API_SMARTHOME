<?php

namespace App\Traits;

use Carbon\Carbon;

trait DatetimeTrait
{
    public function getDatetime(Carbon $datetime = new Carbon())
    {
        $datetime->setMicrosecond(0);
        $datetime->setSecond(0);
        $datetime->setMinute(0);

        return $datetime;
    }
}
