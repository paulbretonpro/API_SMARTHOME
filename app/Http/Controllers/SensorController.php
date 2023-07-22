<?php

namespace App\Http\Controllers;

use App\Filters\SensorFilter;
use App\Http\Requests\SensorStoreRequest;
use App\Models\Sensor;
use App\Traits\DatetimeTrait;
use Exception;
use Illuminate\Http\Response;
use PhpParser\Node\Stmt\TryCatch;

class SensorController extends Controller
{
    use DatetimeTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'payload' => Sensor::all(),
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sensor $sensor)
    {
        //
    }
}
