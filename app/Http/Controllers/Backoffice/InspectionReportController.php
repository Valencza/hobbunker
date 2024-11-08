<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\InspectionDetail;
use App\Models\MasterMeachine;
use App\Models\MasterShip;
use App\Models\Inspection;
use Carbon\Carbon;

class InspectionReportController extends Controller
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

        $inspections = Inspection::latest()
            ->where('master_ship_uuid', 'LIKE', "%$masterShipUuid%")
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);

            $inspections->appends(request()->query());

        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view('backoffice.pages.inventory.inspection.index', compact(
            'startDate',
            'endDate',
            'masterMeachines',
            'masterShips',
            'masterShipUuid',
            'masterMeachineUuid',
            'search',
            'inspections'
        ));
    }

    public function detail($uuid){
        $inspection = Inspection::find($uuid);

        $inspectionDetailsDeck = InspectionDetail::where('inspection_uuid', $uuid)
        ->where('type', 'deck')
        ->get();

        $inspectionDetailsPlatform = InspectionDetail::where('inspection_uuid', $uuid)
        ->where('type', 'platform')
        ->get();

        
        $inspectionDetailsKitchen = InspectionDetail::where('inspection_uuid', $uuid)
        ->where('type', 'kitchen')
        ->get();

        
        $inspectionDetailsMeachine = InspectionDetail::where('inspection_uuid', $uuid)
        ->where('type', 'meachine')
        ->get();

        $inspectionDetailsSafety = InspectionDetail::where('inspection_uuid', $uuid)
        ->where('type', 'safety')
        ->get();

        $inspectionDetailsNavigation = InspectionDetail::where('inspection_uuid', $uuid)
        ->where('type', 'navigation')
        ->get();

        $inspectionDetailsMedicine = InspectionDetail::where('inspection_uuid', $uuid)
        ->where('type', 'medicine')
        ->get();

        return view('backoffice.pages.inventory.inspection.detail', compact(
            'inspection',
            'inspectionDetailsDeck',
            'inspectionDetailsPlatform',
            'inspectionDetailsKitchen',
            'inspectionDetailsMeachine',
            'inspectionDetailsSafety',
            'inspectionDetailsNavigation',
            'inspectionDetailsMedicine',
        ));
    }
}
