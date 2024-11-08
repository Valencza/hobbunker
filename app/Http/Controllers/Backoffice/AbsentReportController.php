<?php

namespace App\Http\Controllers\Backoffice;

use App\Exports\AbsentExport;
use App\Http\Controllers\Controller;
use App\Models\Absent;
use App\Models\SettingAbsent;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use  PDF;

class AbsentReportController extends Controller
{
    public function index()
    {
        $search =  request('search');

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);

        $countAbsentsOnTime = Absent::where('status', 'hadir')
            ->whereDate('created_at', Carbon::today())
            ->count();

        $countAbsentsNotYet = User::whereDoesntHave('absents', function ($query) {
            $query->whereDate('created_at', Carbon::today());
        })->count();

        $countAbsentsLate = Absent::where('status', 'terlambat')
            ->whereDate('created_at', Carbon::today())
            ->count();

        $countAbsentsLeave = User::whereHas('leaves', function ($query) {
            $query->whereDate('created_at', Carbon::today());
        })->count();

        $monday = Carbon::now()->startOfWeek();

        $countAbsentsOnTimeWeek = [];
        $countAbsentsLateWeek = [];

        for ($i = 0; $i < 6; $i++) {
            $day = $monday->copy()->addDays($i);
            $count = Absent::where('status', 'hadir')
                ->whereDate('created_at', $day)
                ->count();
            $countAbsentsOnTimeWeek[$day->format('l')] = $count;

            $count = Absent::where('status', 'terlambat')
                ->whereDate('created_at', $day)
                ->count();
            $countAbsentsLateWeek[$day->format('l')] = $count;
        }

        $absents = Absent::whereHas('masterUser', function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%");
        })
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->latest()
            ->paginate(10);

        $notAbsents = User::whereDoesntHave('absents', function ($query) {
                $query->whereDate('created_at', Carbon::today());
            })->paginate(10);

        $absents->appends(request()->query());

        $notAbsents->appends(request()->query());

        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view('backoffice.pages.hr.absent.index', compact(
            'search',
            'notAbsents',
            'absents',
            'countAbsentsOnTime',
            'countAbsentsNotYet',
            'countAbsentsLate',
            'countAbsentsLeave',
            'countAbsentsOnTimeWeek',
            'countAbsentsLateWeek',
            'startDate',
            'endDate'
        ));
    }
    public function pdf()
    {
        $search = request('search');
        $startDate = Carbon::parse(request('startDate'));
        $endDate = Carbon::parse(request('endDate'));

        $absents = Absent::whereHas('masterUser', function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%");
        })
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->latest()
            ->get();

        $notAbsents = User::whereDoesntHave('absents', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
            
        })
        ->where('name', 'LIKE', "%$search%")
        ->whereHas('masterRole', function($query){
            $query->where('name', '!=', 'Superadmin');
        })->get();

        foreach($notAbsents as $notAbsent){
            $period = CarbonPeriod::create($startDate, $endDate);

            foreach ($period as $date) {
                $hasAbsent = Absent::where('master_user_uuid', $notAbsent->uuid)
                    ->whereDate('created_at', $date->toDateString())
                    ->exists();

                if (!$hasAbsent) {
                    $notAbsent['date'] = $date->format('d M Y');
                }
            }
        }

        $startDate = $startDate->format('d M Y');
        $endDate = $endDate->format('d M Y');
        $fileName = "Laporn Absensi Karyawan ($startDate";
        if ($endDate != $startDate) {
            $fileName .= " - $endDate";
        }
        $fileName .= ').pdf';

        // return view('backoffice.pages.hr.absent.pdf', compact('absents', 'startDate', 'endDate'));

        $pdf = PDF::loadView('backoffice.pages.hr.absent.pdf', compact('absents', 'notAbsents', 'startDate', 'endDate'));
        $pdf->setOption('dpi', 96); 

        return $pdf->download($fileName);
    }

    public function detail($uuid)
    {
        $user = User::find($uuid);

        if (!$user) return redirect()->back();

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        $defaultStartDate = Carbon::create(2024, 1, 1);  // Correctly set to January 1, 2024
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::create($defaultStartDate);
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);
        $totalDates = $endDate->diffInDays($startDate) + 1;

        $absents = Absent::where('master_user_uuid', $uuid)
            ->where('status', '!=', 'tidak hadir')
            ->where('status', '!=', 'cuti')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        $H = Absent::where('master_user_uuid', $uuid)
            ->where('status',  'hadir')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();
        $L = Absent::where('master_user_uuid', $uuid)
            ->where('status',  'terlambat')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();
        $A = Absent::where('master_user_uuid', $uuid)
            ->where('status',  'tidak hadir')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        $percentage = ($H * 1) + ($L * 0.5) + ($A * 0);
        $percentage = $percentage / Carbon::now()->daysInMonth;
        $percentage = $percentage * 100;
        $percentage = round($percentage);

        $absentUser = Absent::where('master_user_uuid', $uuid)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        $absents = $absentUser->latest()
            ->paginate(10);

        $countAbsentsOnTime = $absentUser->where('status', 'hadir')
            ->count();
        $countAbsentsLate = $absentUser->where('status', 'terlambat')
            ->count();
        $countAbsentsNotPresent = $absentUser->where('status', 'tidak hadir')
            ->count();
        $countAbsentsLeave = $absentUser->where('status', 'cuti')
            ->count();

        $absents->appends(request()->query());

        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view('backoffice.pages.hr.absent.detail', compact(
            'user',
            'startDate',
            'endDate',
            'percentage',
            'absents',
            'countAbsentsOnTime',
            'countAbsentsLate',
            'countAbsentsNotPresent',
            'countAbsentsLeave'
        ));
    }


    public function export($uuid)
    {
        $user =  User::find($uuid);

        $startDate = Carbon::parse(request('start_date'));
        $endDate = Carbon::parse(request('end_date'));

        $absents = Absent::where('master_user_uuid', $uuid)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->latest()
            ->get();

        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return Excel::download(new AbsentExport($absents), "Laporan Absensi $user->name ($startDate - $endDate) .xlsx");
    }

    public function update($uuid)
    {
        $absent = Absent::find($uuid);
        $settingAbsent = SettingAbsent::first();

        if(request('status') == 'hadir'){
            $absent->checkin_clock = $settingAbsent->clock_in;
            $absent->checkout_clock = $settingAbsent->clock_out;
            $absent->checkin_latitude = $settingAbsent->checkin_latitude;
            $absent->checkout_latitude = $settingAbsent->checkout_latitude;
            $absent->checkin_longitude = $settingAbsent->checkin_longitude;
            $absent->checkout_longitude = $settingAbsent->checkout_longitude;
            $absent->checkin_radius = 100;
            $absent->checkout_radius = 100;
            $absent->checkin_status_radius = 'dalam radius';
            $absent->checkout_status_radius = 'dalam radius';
            $absent->checkin_location = 'Jalan Ikan Mungsing VIII, RW 04, Perak Barat, Krembangan, Surabaya, Jawa Timur, Jawa, 60177, Indonesia';
            $absent->checkout_location = 'Jalan Ikan Mungsing VIII, RW 04, Perak Barat, Krembangan, Surabaya, Jawa Timur, Jawa, 60177, Indonesia';
            $absent->checkout_status = 'checkout';
        }

        $absent->status = request('status');
        $absent->save();

        return redirect()->back()->with('success', 'Status absensi berhasil diubah');
    }
}
