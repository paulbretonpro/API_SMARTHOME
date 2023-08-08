<?php

namespace App\Jobs;

use App\DTO\CaptorDTO;
use App\Models\Captor;
use App\Traits\DatetimeTrait;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CallApiHomeAssistant implements ShouldQueue
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
        $homeAssistantUrl = "http://192.168.50.179:8123/api/states/sensor.78e36dc092e0_power";
        $token = "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJhMWY0OGIyN2IxYzU0MzY1ODI1ZTAxNGQ2N2IyNDBkZiIsImlhdCI6MTY5MTA1OTU2OCwiZXhwIjoyMDA2NDE5NTY4fQ.tkJ9d5ikgoAlENPVhRnBsjONiG9ncBa5g6DWeCZVL9M";

        $client = new Client();

        try {
            $response = $client->get($homeAssistantUrl, [
                'headers' => [
                    'Authorization' => $token,
                    "content-type" => "application/json"
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            $captorDTO = new CaptorDTO($data['state'], $this->getDatetime());

            Captor::create([
                'consumption' => $captorDTO->consumption,
                'datetime' => $captorDTO->datetime
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the API call.
            // For example, log the error or retry the job later.
            Log::error('Home assistant API request failed: ' . $e->getMessage());
        }
    }
}
