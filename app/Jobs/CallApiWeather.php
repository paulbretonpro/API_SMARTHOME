<?php

namespace App\Jobs;

use App\DTO\WeatherDTO;
use App\Models\Weather;
use App\Traits\DatetimeTrait;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CallApiWeather implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, DatetimeTrait;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // API endpoint URL
        $apiUrl = 'https://api.api-ninjas.com/v1/weather';

        $apiKey = 'vlq7dkvbzK2lPyKj4Dzr8A==VIEjzOKKidA6NUUm';
        $city = 'hanoi';

        $client = new Client();

        try {
            $response = $client->get($apiUrl, [
                'headers' => [
                    'X-Api-Key' => $apiKey
                ],
                'query' => [
                    'city' => $city,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            $weatherDTO = new WeatherDTO($data["feels_like"], $data["humidity"], $data["temp"], $data["wind_speed"], $this->getDatetime());

            Weather::create([
                'humidity' => $weatherDTO->humidity,
                'temperature' => $weatherDTO->temperature,
                'feels_like' => $weatherDTO->feels_like,
                'wind_speed' => $weatherDTO->wind_speed,
                'datetime' => $weatherDTO->datetime
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the API call.
            // For example, log the error or retry the job later.
            Log::error('Weather API request failed: ' . $e->getMessage());
        }
    }
}
