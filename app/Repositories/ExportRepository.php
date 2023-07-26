<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ExportRepository
{
    public function getDataForExport()
    {

        $data = DB::table('weather')
            ->join('sensor', 'weather.datetime', '=', 'sensor.datetime')
            ->select('weather.datetime',  'sensor.temperature as temperature_in', 'weather.temperature as temperature_out', 'sensor.humidity as humidity_in', 'weather.humidity as humidity_out', 'weather.wind_speed', 'weather.feels_like')
            ->orderBy('weather.datetime', 'asc')
            ->get();

        return $data;
    }
}
