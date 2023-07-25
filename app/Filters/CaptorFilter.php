<?php

namespace App\Filters;

use App\Filters\DataTransferObject;

class CaptorFilter extends DataTransferObject
{
    /** @var float */
    public $consumption;
    /** @var date */
    public $datetime;
    /** @var date */
    public $date_start;
    /** @var date */
    public $date_end;
    /** @var date */
    public $date;
}
