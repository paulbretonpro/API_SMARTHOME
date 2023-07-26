<?php

namespace App\Http\Controllers;

use App\Repositories\ExportRepository;
use Exception;

class ExportController extends Controller
{

    public function __construct(private ExportRepository $repo)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->repo->getDataForExport();
        try {
            return response()->json([
                'payload' => $data,
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
