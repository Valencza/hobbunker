<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MasterMeachine;
use App\Models\MasterShip;
use App\Models\OilChange;
use Carbon\Carbon;

class OilReportController extends Controller
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

        $masterMeachines = MasterMeachine::latest()
            ->get();

        $masterShipUuid = request('master_ship_uuid') ?? '';
        $masterMeachineUuid = request('master_meachine_uuid') ?? '';

        $search = request('search');

        $oils = OilChange::latest()
            ->where('master_ship_uuid', 'LIKE', "%$masterShipUuid%")
            ->where('master_meachine_uuid', 'LIKE', "%$masterMeachineUuid%")
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);

            $oils->appends(request()->query());

        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view('backoffice.pages.inventory.oil.index', compact(
            'startDate',
            'endDate',
            'masterMeachines',
            'masterShips',
            'masterShipUuid',
            'masterMeachineUuid',
            'search',
            'oils'
        ));
    }
}
