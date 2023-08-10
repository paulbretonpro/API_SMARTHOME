<?php

namespace App\Console\Commands;

use App\DTO\CaptorDTO;
use App\DTO\SensorDTO;
use App\DTO\WeatherDTO;
use App\Models\Captor;
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
    private Client $client;
    private string $now;
    private string $backupHomeUrl = 'http://100.90.22.103:8123/api/history/period/';
    private string $backupToken = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJhOTg0ODcwMzlhY2Q0OGVhYTVhMzY2YWE2YTBiMzdjZSIsImlhdCI6MTY5MTQ4NDc3OSwiZXhwIjoyMDA2ODQ0Nzc5fQ.Btagfxjh7jRvqoS_LXKL3mtsFuCmvZbTpr-GzPJPeGo';
    private string $backupSensorUrl = 'http://192.168.50.205/api/sync';

    public function __construct()
    {
        parent::__construct();

        $this->client = new Client();
        $this->now = (new Carbon())->toIso8601String();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:sync
        {--since-last : Synchronise since the last record}
        {--captor : Synchronise captor backup}
        {--sensor : Synchronise sensor backup}
        {--weather : Synchronise weather backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data when something goes wrong';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $newCaptor = 0;
        $newSensor = 0;
        $newWeather = 0;

        $sinceLast = $this->option('since-last');

        if ($this->option('captor') == true) {
            $newCaptor = $this->fetchBackupCaptor($sinceLast);
        }
        if ($this->option('sensor') == true) {
            $newSensor = $this->fetchBackupSensor();
        }
        if ($this->option('weather') == true) {
            $newWeather = $this->fetchBackupWeather($sinceLast);
        }

        $this->info($newCaptor . '' . $newSensor . '' . $newWeather);
    }

    private function fetchBackupCaptor(bool $sinceLast)
    {
        $newCaptor = 0;
        // Sync backup Captor
        try {
            if ($sinceLast) {
                $lastWeatherRecord = Captor::orderBy('datetime', 'desc')->first();
                $startDate = (new Carbon($lastWeatherRecord->datetime))->toIso8601String();
            } else {
                $startDate = (new Carbon('2023-07-01T00:00:00Z'))->toIso8601String();
            }

            $response = $this->client->get($this->backupHomeUrl . $startDate, [
                'headers' => [
                    'Authorization' => $this->backupToken
                ],
                'query' => [
                    'filter_entity_id' => "sensor.78e36dc092e0_power",
                    'end_time' => $this->now,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            foreach ($data[0] as $key => $value) {
                try {
                    $captorDTO = new CaptorDTO($value['state'], new Carbon($value['last_changed']));

                    Captor::create([
                        "consumption" => $captorDTO->consumption,
                        "datetime" => $captorDTO->datetime,
                    ]);

                    $newCaptor++;

                    Log::info('[APP] create captor ' . $captorDTO->datetime);
                } catch (Exception $e) {
                    Log::error('Error when save captor: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('Captor API request failed: ' . $e->getMessage());
        }

        return $newCaptor;
    }

    private function fetchBackupSensor()
    {
        $newSensor = 0;
        // Sync backup SENSOR
        try {
            $response = $this->client->get($this->backupSensorUrl);

            $data = json_decode($response->getBody(), true);

            foreach ($data as $key => $value) {
                try {
                    $sensorDTO = new SensorDTO($value["temperature"], $value["humidity"], new Carbon($value['datetime']));

                    Sensor::create([
                        'humidity' => $sensorDTO->humidity,
                        'temperature' => $sensorDTO->temperature,
                        'datetime' => $sensorDTO->datetime
                    ]);

                    $newSensor++;

                    Log::info('[APP] create sensor ' . $sensorDTO->datetime);
                } catch (Exception $e) {
                    Log::error('Error when save captor: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('Sensor API request failed: ' . $e->getMessage());
        }

        return $newSensor;
    }

    private function fetchBackupWeather(bool $sinceLast)
    {
        $newWeather = 0;

        try {
            if ($sinceLast) {
                $lastWeatherRecord = Weather::orderBy('datetime', 'desc')->first();
                $startDate = (new Carbon($lastWeatherRecord->datetime))->toIso8601String();
            } else {
                $startDate = (new Carbon('2023-07-01T00:00:00Z'))->toIso8601String();
            }

            $response = $this->client->get($this->backupHomeUrl . $startDate, [
                'headers' => [
                    'Authorization' => $this->backupToken
                ],
                'query' => [
                    'filter_entity_id' => "weather.forecast_home",
                    'end_time' => $this->now,
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

                    $newWeather++;

                    Log::info('[APP] create weather ' . $weatherDTO->datetime);
                } catch (Exception $e) {
                    Log::error('Error when save captor: ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('Weather API request failed: ' . $e->getMessage());
        }

        return $newWeather;
    }
}
