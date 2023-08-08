<?php

namespace App\Console\Commands;

use App\DTO\CaptorDTO;
use App\Models\Captor;
use Carbon\Carbon;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchHistoryCaptor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'captor:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all history value of sensor energy';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('[API Home assistant] Start data recovery !');
        // API endpoint URL
        $homeAssistantUrl = "http://192.168.50.179:8123/api/history/period/2023-07-20T00:00:00+00:00";
        $token = "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJhMWY0OGIyN2IxYzU0MzY1ODI1ZTAxNGQ2N2IyNDBkZiIsImlhdCI6MTY5MTA1OTU2OCwiZXhwIjoyMDA2NDE5NTY4fQ.tkJ9d5ikgoAlENPVhRnBsjONiG9ncBa5g6DWeCZVL9M";

        $client = new Client();

        try {
            $response = $client->get($homeAssistantUrl, [
                'headers' => [
                    'Authorization' => $token,
                    "content-type" => "application/json"
                ],
                'query' => [
                    'filter_entity_id' => "sensor.78e36dc092e0_power",
                    'end_time' => '2023-08-08T23:00:00+00:00',
                    'no_attributes' => true
                ]
            ]);

            $data = json_decode($response->getBody(), true);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        $this->info('[APP] Start create captors !');
        foreach ($data[0] as $key => $value) {
            try {
                $captorDTO = new CaptorDTO($value['state'], new Carbon($value['last_changed']));

                $newCaptor = Captor::create([
                    "consumption" => $captorDTO->consumption,
                    "datetime" => $captorDTO->datetime,
                ]);
                $this->info('[APP] creation successfull ...' . $newCaptor->consumption . ' ' . $newCaptor->datetime);
            } catch (Exception $e) {
                $this->error('[APP] creation error');
                Log::error('Error when save captor: ' . $e->getMessage());
            }
        }
        $this->info('[APP] End create captors !');
    }
}
