<?php

namespace App\Http\Controllers;

use App\Filters\WeatherFilter;
use App\Http\Requests\WeatherIndexRequest;
use App\Models\Weather;
use App\Repositories\WeatherRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{

    public function __construct(private WeatherRepository $repo)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(WeatherIndexRequest $request)
    {
        $filters = WeatherFilter::fromRequest($request);

        $results = $this->repo->getByFilters($filters);

        return response()->json([
            'payload' => $results,
            'status' => 200
        ]);
    }

    public function destroy(Request $request)
    {
        try {
            Weather::destroy($request->ids);

            return response()->json([
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error when delete Captor : ' . $e->getMessage());
        }
    }
}
