<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MasterItem;
use App\Models\MasterShip;
use App\Models\StockHistory;
use Carbon\Carbon;

class StockReportController extends Controller
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

        $masterShipUuid = request('master_ship_uuid') ?? 'kantor';

        $position = request('position') ?? '';

        $search = request('search');

        $masterItems = MasterItem::latest()
            ->where('type', 'stok')
            ->whereHas('stockHistory', function ($query) use ($startDate, $endDate, $masterShipUuid, $position) {
                $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
                if ($masterShipUuid == 'kantor') {
                    $query->whereNull('master_ship_uuid');
                } else {
                    $query->where('master_ship_uuid', 'LIKE', "%$masterShipUuid%");
                    $query->where('position', 'LIKE', "%$position%");
                }
            })
            ->where('name', 'LIKE', "%$search%")
            ->paginate(10);

            $masterItems->appends(request()->query());

        $masterItems->appends(request()->query());

        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view('backoffice.pages.inventory.stock.index', compact(
            'startDate',
            'endDate',
            'masterShips',
            'masterShipUuid',
            'position',
            'search',
            'masterItems'
        ));
    }

    public function detail($uuid)
    {
        $masterItem = MasterItem::find($uuid);

        $masterShipUuid = request('master_ship_uuid');

        $position = request('position');

        $startDate =  Carbon::parse(request('start_date'));
        $endDate =  Carbon::parse(request('end_date'));

        $stocks = StockHistory::where('master_item_uuid', $uuid)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        if ($masterShipUuid == 'kantor') {
            $stocks->whereNull('master_ship_uuid');
        } else {
            $stocks->where('master_ship_uuid', 'LIKE', "%$masterShipUuid%");
            $stocks->where('position', $position);
        }

        $stocks = $stocks->paginate(10);


        return view('backoffice.pages.inventory.stock.detail', compact(
            'masterShipUuid',
            'position',
            'startDate',
            'endDate',
            'masterItem',
            'stocks'
        ));
    }
}
