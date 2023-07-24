<?php

namespace App\Http\Controllers;

use App\Filters\SensorFilter;
use App\Http\Requests\SensorIndexRequest;
use App\Http\Requests\SensorStoreRequest;
use App\Models\Sensor;
use App\Repositories\SensorRepository;
use App\Traits\DatetimeTrait;
use Exception;

class SensorController extends Controller
{
    use DatetimeTrait;

    public function __construct(private SensorRepository $repo)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(SensorIndexRequest $request)
    {
        $filters = SensorFilter::fromRequest($request);

        $results = $this->repo->getByFilters($filters);

        return response()->json([
            'payload' => $results,
            'status' => 200
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SensorStoreRequest $request)
    {
        $newSensor = SensorFilter::fromRequest($request);

        try {
            $sensor = new Sensor;
            $sensor->humidity = $newSensor->humidity;
            $sensor->temperature = $newSensor->temperature;
            $sensor->datetime = $this->getDatetime();

            $sensor->save();
            return response()->json([
                'payload' => $sensor,
                'status' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500,
            ]);
        }
    }
}
