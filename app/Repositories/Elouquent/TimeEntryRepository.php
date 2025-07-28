<?php

namespace App\Repositories\Elouquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\TimeEntry;

class TimeEntryRepository extends AbstractRepository
{
    protected $model = TimeEntry::class;

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    protected function resolveModel()
    {
        return app($this->model);
    }

    public function allTimeEntryUser(Request $request): object
    {
        return DB::table('time_entries')
            ->select(
                'users.first_name as user',
                'time_entries.entry_date as date',
                DB::raw('GROUP_CONCAT(time_entries.entry_time ORDER BY entry_time SEPARATOR ", ") as times')
            )
            ->join('users', 'users.id', '=', 'time_entries.user_id')
            ->when($request->input('date_start') && $request->input('date_end'), function ($query) use ($request) {
                // Remove o conteúdo entre parênteses
                $dateJsLimpo = preg_replace('/\s*\(.*\)$/', '', $request->input('date_start'));

                // Agora podes fazer o parse com Carbon
                $dateStart = Carbon::parse($dateJsLimpo)
                    ->timezone('America/Sao_Paulo')
                    ->format('Y-m-d');

                // Remove o conteúdo entre parênteses
                $dateJsLimpo = preg_replace('/\s*\(.*\)$/', '', $request->input('date_end'));

                // Agora podes fazer o parse com Carbon
                $dateEnd = Carbon::parse($dateJsLimpo)
                    ->timezone('America/Sao_Paulo')
                    ->format('Y-m-d');
                
                
                return $query->whereBetween('time_entries.entry_date', [
                    $dateStart,
                    $dateEnd
                ]);
            })
            ->when($request->input('user_id') != 0, function ($query) use ($request) {
                return $query->where('time_entries.user_id', $request->input('user_id'));
            })
            ->when(!$request->input('user_id'), function ($query) use ($request) {
                return $query->where('time_entries.user_id', Auth::user()->id);
            })
            ->groupBy('users.first_name', 'users.last_name', 'time_entries.entry_date')
            ->orderBy('time_entries.entry_date', 'desc')
            ->get();
    }
}