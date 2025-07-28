<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use App\Repositories\Elouquent\TimeEntryRepository;
use App\Traits\Pagination;

class TimeEntryService
{
    use Pagination;

    protected $timeEntryRepository;

    public function __construct()
    {
        $this->timeEntryRepository = new TimeEntryRepository();
    }

    /**
     * Selecione todos os usuarios
     * @return array
    */
    public function all(Request $request): Collection|array
    {
        return $this->timeEntryRepository->allTimeEntryUser($request);
    }

    public function store(): Model
    {   
        $request = [
            'user_id' => Auth::user()->id,
            'entry_date' => now()->format('Y-m-d'),
            'entry_time' => now()->format('H:i:s'),
        ];
        
        return $this->timeEntryRepository->store($request);
    }
}
