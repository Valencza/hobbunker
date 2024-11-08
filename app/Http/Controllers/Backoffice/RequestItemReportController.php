<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\RequestItem;
use App\Models\MasterShip;
use Carbon\Carbon;

class RequestItemReportController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);

        $masterShips = MasterShip::latest()
            ->get();

        $masterShipUuid = request('master_ship_uuid') ?? '';

        $type = request('type') ?? '';
        $status = request('status') ?? '';

        $search = request('search');

        $requestItems = RequestItem::latest()
            ->where('type', 'LIKE', "%$type%")
            ->where('status', 'LIKE', "%$status%")
            ->where('category', 'permintaan')
            ->where('master_ship_uuid', 'LIKE', "%$masterShipUuid%")
            ->where('number', 'LIKE', "%$search%")
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);


            $requestItems->appends(request()->query());


        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view('backoffice.pages.request-item-report.index', compact(
            'startDate',
            'endDate',
            'masterShips',
            'masterShipUuid',
            'type',
            'status',
            'search',
            'requestItems'
        ));
    }

    public function detail($uuid)
    {
        $requestItem = RequestItem::find($uuid);

        $masterShipUuid = request('master_ship_uuid');

        $type = request('type');

        $startDate =  Carbon::parse(request('start_date'));
        $endDate =  Carbon::parse(request('end_date'));

        return view('backoffice.pages.request-item-report.detail', compact(
            'masterShipUuid',
            'type',
            'startDate',
            'endDate',
            'requestItem'
        ));
    }
}
