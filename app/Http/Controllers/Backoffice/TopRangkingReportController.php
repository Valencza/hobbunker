<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Absent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TopRangkingReportController extends Controller
{
    public function index()
    {
        $search =  request('search');

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::create($currentYear, 1, 1);
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);
        $totalDates = $endDate->diffInDays($startDate) + 1;

        $absents = Absent::select('master_user_uuid', DB::raw('count(*) as total_absents'))
            ->groupBy('master_user_uuid')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->where('status', '!=', 'tidak hadir')
            ->where('status', '!=', 'cuti')
            ->orderByDesc('total_absents')
            ->take(5)
            ->get();

        $topUsers = [];

        foreach ($absents as $absent) {
            $user = User::find($absent->master_user_uuid);
            $user->absent_percentage = ($absent->total_absents / $totalDates) * 100;
            $topUsers[] = $user;
        }

        $users = User::where('name', 'LIKE', "%$search%")
            ->paginate(10);

            $users->appends(request()->query());

        foreach ($users as $user) {
            $absents = Absent::where('master_user_uuid', $user->uuid)
                ->where('status', '!=', 'tidak hadir')
                ->where('status', '!=', 'cuti')
                ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                ->count();

            $user->absent_percentage = ($absents / $totalDates) * 100;
        }


        $countAbsentsOnTimeYear = [];

        for ($month = 1; $month <= 12; $month++) {
            $daysInMonth = Carbon::create($currentYear, $month)->daysInMonth;

            for ($dayOfMonth = 1; $dayOfMonth <= $daysInMonth; $dayOfMonth++) {
                $date = Carbon::create($currentYear, $month, $dayOfMonth);
                $countOnTime = Absent::where('status', 'hadir')
                    ->whereDate('created_at', $date)
                    ->count();
                $countAbsentsOnTimeYear[$date->format('F')] = $countOnTime;
            }
        }

        return view('backoffice.pages.hr.top-rangking.index', compact(
            'search',
            'startDate',
            'endDate',
            'topUsers',
            'users',
            'countAbsentsOnTimeYear'
        ));
    }
}
