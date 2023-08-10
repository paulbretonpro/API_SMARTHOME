<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyncIndexRequest;
use App\Managers\SyncManager;

class SyncController extends Controller
{

    public function __construct(private SyncManager $syncManager)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(SyncIndexRequest $request)
    {
        $result = $this->syncManager->callCommand(
            $request->input('since_last'),
            $request->input('captor'),
            $request->input('sensor'),
            $request->input('weather')
        );

        return response()->json([
            'payload' => [
                'captor' => $result['captor'],
                'sensor' => $result['sensor'],
                'weather' => $result['weather']
            ],
            'status' => 200
        ]);
    }
}
