<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Traits\Pagination;
use App\Services\TimeEntryService;
use App\Http\Resources\TimeEntryCollection;

class TimeEntryController extends Controller
{
    use Pagination;

    protected $timeEntryService;

    public function __construct(
        TimeEntryService $timeEntryService
    ){
        $this->timeEntryService = $timeEntryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try{
            $timeEntries = $this->timeEntryService->all($request);
            
            return response()->json(new TimeEntryCollection($timeEntries), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(), 
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->timeEntryService->store();
            return response()->json('', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(), 
                'status' => $e->getCode()
            ], $e->getCode());
        }
    }
}
