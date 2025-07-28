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
        return DB::table('time_entries as t')
            ->select([
                't.id as id',
                DB::raw("CONCAT(u.first_name, ' ', u.last_name) as employee"),
                'u.position as position',
                DB::raw("TIMESTAMPDIFF(YEAR, u.date_of_birth, CURDATE()) as age"),
                DB::raw("IFNULL(CONCAT(m.first_name, ' ', m.last_name), 'Sem Gestor') as manager"),
                DB::raw("CONCAT(t.entry_date, ' ', t.entry_time) as time")
            ])
            ->join('users as u', 'u.id', '=', 't.user_id')
            ->leftJoin('users as m', 'u.manager_id', '=', 'm.id')
            ->when($request->input('date_start') && $request->input('date_end'), function ($query) use ($request) {
                $dateStart = Carbon::parse(preg_replace('/\s*\(.*\)$/', '', $request->input('date_start')))
                    ->timezone('America/Sao_Paulo')
                    ->format('Y-m-d');

                $dateEnd = Carbon::parse(preg_replace('/\s*\(.*\)$/', '', $request->input('date_end')))
                    ->timezone('America/Sao_Paulo')
                    ->format('Y-m-d');

                return $query->whereBetween('t.entry_date', [$dateStart, $dateEnd]);
            })
            ->when($request->input('user_id') != 0, function ($query) use ($request) {
                return $query->where('t.user_id', $request->input('user_id'));
            })
            ->when(!$request->input('user_id') && $request->input('user_id') != null && $request->input('user_id') != 0, function ($query) {
                return $query->where('t.user_id', Auth::user()->id);
            })
            ->orderBy('t.entry_date', 'desc')
            ->orderBy('t.entry_time', 'desc')
            ->get();
    }
}