<?php

namespace App\Repositories;

use App\Filters\CaptorFilter;
use App\Models\Captor;

class CaptorRepository
{
    public function getByFilters(CaptorFilter $filters)
    {
        $query = Captor::query();

        if ($filters->date_start && $filters->date_end) {
            $from = $filters->date_start . " 00:00:00";
            $to = $filters->date_end . " 23:00:00";
            $query->whereBetween('datetime', [$from, $to]);
        }
        if ($filters->date) {
            $query->whereDate('datetime', $filters->date);
        }

        return $query->orderBy('datetime', 'desc')->paginate(10);
    }
}
