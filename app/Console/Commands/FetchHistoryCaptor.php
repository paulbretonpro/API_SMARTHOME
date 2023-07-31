<?php

namespace App\Console\Commands;

use App\DTO\CaptorDTO;
use App\Models\Captor;
use Exception;
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
        $exampleResponse =
            [
                [
                    "attributes" => [
                        "friendly_name" => "Weather Temperature",
                        "unit_of_measurement" => "\u00b0C"
                    ],
                    "entity_id" => "sensor.weather_temperature",
                    "last_changed" => "2016-02-06T22:15:00+00:00",
                    "last_updated" => "2016-02-06T22:15:00+00:00",
                    "state" => "3.9"
                ],
                [
                    "last_changed" => "2016-02-06T20:20:00+00:00",
                    "state" => "2.9"
                ],
                [
                    "last_changed" => "2016-02-06T22:22:00+00:00",
                    "state" => "2.2"
                ],
                [
                    "attributes" => [
                        "friendly_name" => "Weather Temperature",
                        "unit_of_measurement" => "\u00b0C"
                    ],
                    "entity_id" => "sensor.weather_temperature",
                    "last_changed" => "2016-02-06T23:25:00+00:00",
                    "last_updated" => "2016-02-06T23:25:00+00:00",
                    "state" => "1.9"
                ]
            ];

        $this->info('[API Home assistant] End data recovery !');


        $this->info('[APP] Start create captors !');
        foreach ($exampleResponse as $captor) {
            try {
                $captorDTO = new CaptorDTO($captor['state'], $captor['last_changed']);

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
