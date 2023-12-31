<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ExportRepository
{
    public function getDataForExport()
    {

        $data = DB::table('weather')
            ->join('sensor', 'sensor.datetime', '=', 'weather.datetime')
            ->join('captor', 'captor.datetime', '=', 'weather.datetime')
            ->where('captor.consumption', '>=', 100)
            ->select('sensor.temperature as temperature_in', 'weather.temperature as temperature_out', 'sensor.humidity as humidity_in', 'weather.humidity as humidity_out', 'weather.wind_speed', 'weather.feels_like', 'captor.consumption')
            ->orderBy('weather.datetime', 'desc')
            ->get();

        return $data;
    }
}
