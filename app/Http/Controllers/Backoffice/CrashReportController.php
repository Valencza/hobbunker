<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\CrashReport;
use App\Models\MasterShip;
use Carbon\Carbon;

class CrashReportController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        $defaultStartDate = Carbon::create(2024, 1, 1);  // Correctly set to January 1, 2024
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::create($defaultStartDate);
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);

        $masterShips = MasterShip::latest()
            ->get();

        $masterShipUuid = request('master_ship_uuid') ?? '';

        $search = request('search');

        $crashReports = CrashReport::latest()
            ->where('title', 'LIKE', "%$search%")
            ->where('master_ship_uuid', 'LIKE', "%$masterShipUuid%")
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);

            $crashReports->appends(request()->query());

        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view('backoffice.pages.inventory.crash-report.index', compact(
            'startDate',
            'endDate',
            'masterShips',
            'masterShipUuid',
            'search',
            'crashReports'
        ));
    }

    public function detail($uuid)
    {
        $crashReport = CrashReport::find($uuid);

        return view('backoffice.pages.inventory.crash-report.detail', compact(
            'crashReport'
        ));
    }

    public function update($uuid)
    {
        CrashReport::where('uuid', $uuid)
            ->update([
                'status' => 'proses'
            ]);

        return redirect()->route('crash-report')->with('success', 'Laporan kerusakan berhasil dikonfirmasi');
    }
}
