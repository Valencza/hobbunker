<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Docking;
use App\Models\DockingDetail;
use App\Models\MasterShip;
use Carbon\Carbon;

class DockingReportController extends Controller
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

        $dockings = Docking::latest()
            ->where('title', 'LIKE', "%$search%")
            ->where('master_ship_uuid', 'LIKE', "%$masterShipUuid%")
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);

            $dockings->appends(request()->query());

        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view('backoffice.pages.inventory.docking.index', compact(
            'startDate',
            'endDate',
            'masterShips',
            'masterShipUuid',
            'search',
            'dockings'
        ));
    }

    public function detail($uuid)
    {
        $docking = Docking::find($uuid);

        $dockingAdmninistrations = DockingDetail::where('docking_uuid', $uuid)
            ->where('type', 'administrasi')
            ->get();

        $dockingReports = DockingDetail::where('docking_uuid', $uuid)
            ->where('type', 'laporan')
            ->get();


        $dockingCertificates = DockingDetail::where('docking_uuid', $uuid)
            ->where('type', 'sertifikat')
            ->get();

        $dockingOffers1 = DockingDetail::where('docking_uuid', $uuid)
            ->where('type', 'penawaran_1')
            ->get();


        $dockingOffers2 = DockingDetail::where('docking_uuid', $uuid)
            ->where('type', 'penawaran_2')
            ->get();

        $dockingOffers3 = DockingDetail::where('docking_uuid', $uuid)
            ->where('type', 'penawaran_3')
            ->get();

        $dockingFixs = DockingDetail::where('docking_uuid', $uuid)
            ->where('type', 'perbaikan')
            ->get();
        return view('backoffice.pages.inventory.docking.detail', compact(
            'docking',
            'dockingAdmninistrations',
            'dockingReports',
            'dockingCertificates',
            'dockingOffers1',
            'dockingOffers2',
            'dockingOffers3',
            'dockingFixs',
        ));
    }
}
