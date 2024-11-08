<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Absent;
use App\Models\MasterAnnouncement;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $masterAnnouncements = MasterAnnouncement::latest()
            ->limit(5)
            ->get();
        $countMasterAnnouncements = MasterAnnouncement::count();

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        $startDate = Carbon::create($currentYear, $currentMonth, 1);
        $endDate = Carbon::create($currentYear, $currentMonth, $currentDay);

        $totalDates = $endDate->diffInDays($startDate) + 1;

        $absents = Absent::where('master_user_uuid', Auth::user()->uuid)
            ->where('status', '!=', 'tidak hadir')
            ->where('status', '!=', 'cuti')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        $H = Absent::where('master_user_uuid', Auth::user()->uuid)
            ->where('status',  'hadir')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();
        $L = Absent::where('master_user_uuid', Auth::user()->uuid)
            ->where('status',  'terlambat')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();
        $A = Absent::where('master_user_uuid', Auth::user()->uuid)
            ->where('status',  'tidak hadir')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        $percentage = ($H * 1) + ($L * 0.5) + ($A * 0);
        $percentage = $percentage / Carbon::now()->daysInMonth;
        $percentage = $percentage * 100;
        $percentage = round($percentage);

        return view('client.pages.home.index', compact(
            'user',
            'masterAnnouncements',
            'countMasterAnnouncements',
            'percentage',
        ));
    }
}
