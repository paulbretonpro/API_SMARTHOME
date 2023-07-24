<?php

namespace App\Http\Controllers;

use App\Filters\WeatherFilter;
use App\Http\Requests\WeatherIndexRequest;
use App\Repositories\WeatherRepository;

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
}
