<?php

namespace App\DTO;

use Carbon\Carbon;
use DateTime;

class CaptorDTO
{
    public int $consumption;
    public DateTime $datetime;

    public function __construct($consumption, $date)
    {
        $this->consumption = intval($consumption);
        $this->datetime = $this->setHours($date);;
    }

    private function setHours(string $date): DateTime
    {
        $datetimeFormated = Carbon::createFromDate($date);
        $datetimeFormated->minutes(0);
        $datetimeFormated->seconds(0);
        $datetimeFormated->milliseconds(0);

        return $datetimeFormated;
    }
}
