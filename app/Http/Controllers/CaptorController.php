<?php

namespace App\Http\Controllers;

use App\Filters\CaptorFilter;
use App\Http\Requests\CaptorIndexRequest;
use App\Http\Requests\CaptorStoreRequest;
use App\Models\Captor;
use App\Repositories\CaptorRepository;
use App\Traits\DatetimeTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CaptorController extends Controller
{
    use DatetimeTrait;

    public function __construct(private CaptorRepository $repo)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CaptorIndexRequest $request)
    {
        try {
            $filters = CaptorFilter::fromRequest($request);

            $results = $this->repo->getByFilters($filters);

            return response()->json([
                'payload' => $results,
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

    public function destroy(Request $request)
    {
        try {
            Captor::destroy($request->ids);

            return response()->json([
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error when delete Captor : ' . $e->getMessage());
        }
    }
}
