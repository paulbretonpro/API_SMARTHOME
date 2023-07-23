<?php

namespace App\Http\Controllers;

use App\Filters\CaptorFilter;
use App\Http\Requests\CaptorStoreRequest;
use App\Models\Captor;
use App\Traits\DatetimeTrait;
use Exception;

class CaptorController extends Controller
{
    use DatetimeTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'payload' => Captor::all(),
            'status' => 200
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CaptorStoreRequest $request)
    {
        $newCaptor = CaptorFilter::fromRequest($request);

        try {
            $captor = new Captor();
            $captor->consumption = $newCaptor->consumption;
            $captor->datetime = $this->getDatetime();

            $captor->save();
            return response()->json([
                'payload' => $captor,
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
