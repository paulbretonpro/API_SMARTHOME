<?php

namespace App\Repositories;

use App\Filters\CaptorFilter;
use App\Models\Captor;

class CaptorRepository
{
    public function getByFilters(CaptorFilter $filters)
    {
        $query = Captor::query();
        $perPage = 10;

        if ($filters->date_start && $filters->date_end) {
            $from = $filters->date_start;
            $to = $filters->date_end;
            $query->whereBetween('datetime', [$from, $to]);
        }
        if ($filters->date) {
            $query->whereDate('datetime', $filters->date);
        }
        if ($filters->orderBy) {
            $query->orderBy('datetime', $filters->orderBy);
        }
        if ($filters->perPage) {
            $perPage = $filters->perPage;
        }
        return $query->paginate($perPage);
    }
}
