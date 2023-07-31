<?php

namespace App\Jobs;

use App\DTO\CaptorDTO;
use App\Models\Captor;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CallApiHomeAssistant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $apiUrl = '';
        $token = '';

        $client = new Client();

        try {
            $response = $client->get($apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
                'query' => [],
            ]);

            $data = json_decode($response->getBody(), true);

            $captorDTO = new CaptorDTO($data['state'], $data['last_changed']);

            $newCaptor = Captor::create([
                "consumption" => $captorDTO->consumption,
                "datetime" => $captorDTO->datetime,
            ]);
            Log::info('New captor : ' . $newCaptor->consumption . ' ' . $newCaptor->datetime);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the API call.
            // For example, log the error or retry the job later.
            Log::error('Home assistant API request failed: ' . $e->getMessage());
        }
    }
}
