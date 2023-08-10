<?php

namespace App\Console\Commands;

use App\DTO\SensorDTO;
use App\DTO\WeatherDTO;
use App\Models\Sensor;
use App\Models\Weather;
use App\Traits\DatetimeTrait;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncData extends Command
{
    use DatetimeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data when something goes wrong';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $client = new Client();

        // API wheather URL
        $homeApiUrl = 'http://192.168.50.179:8123/api/history/period/';
        $token = "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJhMWY0OGIyN2IxYzU0MzY1ODI1ZTAxNGQ2N2IyNDBkZiIsImlhdCI6MTY5MTA1OTU2OCwiZXhwIjoyMDA2NDE5NTY4fQ.tkJ9d5ikgoAlENPVhRnBsjONiG9ncBa5g6DWeCZVL9M";

        $backupWeatherUrl = 'http://100.90.22.103:8123/api/history/period/';
        $backupToken = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJhOTg0ODcwMzlhY2Q0OGVhYTVhMzY2YWE2YTBiMzdjZSIsImlhdCI6MTY5MTQ4NDc3OSwiZXhwIjoyMDA2ODQ0Nzc5fQ.Btagfxjh7jRvqoS_LXKL3mtsFuCmvZbTpr-GzPJPeGo';

        $backupSensorUrl = 'http://192.168.50.205/api/sync';

        $now = (new Carbon())->toIso8601String();

        try {
            $lastWeatherRecord = Weather::orderBy('datetime', 'desc')->first();
            $startDate = (new Carbon($lastWeatherRecord->datetime))->toIso8601String();
            dump($startDate);
            dump($now);

            $response = $client->get($backupWeatherUrl . $startDate, [
                'headers' => [
                    'Authorization' => $backupToken
                ],
                'query' => [
                    'filter_entity_id' => "weather.forecast_home",
                    'end_time' => $now,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            foreach ($data[0] as $key => $value) {
                try {
                    $result = $value['attributes'];
                    $weatherDTO = new WeatherDTO($result["temperature"], $result["humidity"], $result["temperature"], $result["wind_speed"], $this->getDatetime(new Carbon($value['last_changed'])));

                    Weather::create([
                        'humidity' => $weatherDTO->humidity,
                        'temperature' => $weatherDTO->temperature,
                        'feels_like' => $weatherDTO->feels_like,
                        'wind_speed' => $weatherDTO->wind_speed,
                        'datetime' => $weatherDTO->datetime
                    ]);
                    $this->info('[APP] create weather ' . $weatherDTO->datetime);
                } catch (Exception $e) {
                    Log::error('Error when save captor: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the API call.
            // For example, log the error or retry the job later.
            Log::error('Weather API request failed: ' . $e->getMessage());
        }

        // Sync backup SENSOR
        try {
            $response = $client->get($backupSensorUrl);

            $data = json_decode($response->getBody(), true);

            foreach ($data as $key => $value) {
                try {
                    $sensorDTO = new SensorDTO($value["temperature"], $value["humidity"], $result["temperature"], $result["wind_speed"], new Carbon($value['datetime']));

                    Sensor::create([
                        'humidity' => $sensorDTO->humidity,
                        'temperature' => $sensorDTO->temperature,
                        'datetime' => $sensorDTO->datetime
                    ]);
                    $this->info('[APP] create sensor ' . $sensorDTO->datetime);
                } catch (Exception $e) {
                    Log::error('Error when save captor: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the API call.
            // For example, log the error or retry the job later.
            Log::error('Sensor API request failed: ' . $e->getMessage());
        }
    }
}
