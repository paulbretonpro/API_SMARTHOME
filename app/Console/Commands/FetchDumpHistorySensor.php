<?php

namespace App\Console\Commands;

use App\DTO\SensorDTO;
use App\DTO\WeatherDTO;
use App\Models\Sensor;
use App\Models\Weather;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FetchDumpHistorySensor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all history value of sensor temperature and weather';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('[API] Start get file !');
        $dump = Storage::get('dump.csv');
        $rows = explode("\r\n", $dump); // Use double quotes to interpret \r\n as newline

        $header = str_getcsv(array_shift($rows));

        $data = [];
        foreach ($rows as $row) {
            $data[] = str_getcsv($row, ',', '"', '\\'); // Using ',' as delimiter, '"' as enclosure, and '\\' as escape character
        }

        // Combine the header with the data
        $result = [];
        foreach ($data as $row) {
            $result[] = array_combine($header, $row);
        }

        $this->info('[API] End get file !');

        $this->info('[APP] Start create sensor and weather !');
        foreach ($result as $exportData) {
            try {
                $sensorDTO = new SensorDTO($exportData['temperature_in'], $exportData['humidity_in'], new DateTime($exportData['datetime']));
                $weatherDTO = new WeatherDTO($exportData['feels_like'], $exportData['humidity_out'], $exportData['temperature_out'], $exportData['wind_speed'], new DateTime($exportData['datetime']));

                try {
                    $newSensor = Sensor::create([
                        "temperature" => $sensorDTO->temperature,
                        "humidity" => $sensorDTO->humidity,
                        "datetime" => $sensorDTO->datetime,
                    ]);
                } catch (Exception $e) {
                    $this->error('[APP] FAILED creation sensor');
                    Log::error('Error when save sensor: ' . $e->getMessage());
                }

                try {
                    $newWeather = Weather::create([
                        "temperature" => $weatherDTO->temperature,
                        "humidity" => $weatherDTO->humidity,
                        "feels_like" => $weatherDTO->feels_like,
                        "wind_speed" => $weatherDTO->wind_speed,
                        "datetime" => $weatherDTO->datetime,
                    ]);
                } catch (Exception $e) {
                    $this->error('[APP] FAILED creation weather');
                    Log::error('Error when save sensor: ' . $e->getMessage());
                }

            } catch (Exception $e) {
                $this->error('[APP] creation error');
                Log::error('Error when save captor: ' . $e->getMessage());
            }
        }
        $this->info('[APP] End fetch dump !');
    }
}
