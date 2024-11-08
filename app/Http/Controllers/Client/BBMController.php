<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MasterItem;
use App\Models\MasterMeachine;
use App\Models\MasterShip;
use App\Models\BBM;
use App\Models\StockHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BBMController extends Controller
{
    public function index()
    {

        $search =  request('search');

        $BBMChanges = BBM::latest()
            ->where('master_ship_uuid', Auth::user()->master_ship_uuid)
            ->paginate(10);


            $BBMChanges->appends(request()->query());


        $BBMStock = StockHistory::whereHas('masterItem', function ($query) {
            $query->where('slug', 'LIKE', '%bbm%');
        })
            ->where('master_ship_uuid', Auth::user()->master_ship_uuid)
            ->select('master_item_uuid', DB::raw('MAX(master_item_uuid) as latest_id'))
            ->groupBy('master_item_uuid')
            ->pluck('latest_id');

        $totalBBMStock = StockHistory::whereIn('master_item_uuid', $BBMStock)->sum('total');

        $BBMUnit =  'Liter';

        $latestBBM = BBM::latest()
            ->where('master_ship_uuid', Auth::user()->master_ship_uuid)
            ->first();

        $nextChangeDate = null;

        if ($latestBBM) {
            $nextChangeDate = Carbon::parse($latestBBM->created_at)->addHours($latestBBM->hour)->format('d M Y');
        }

        return view('client.pages.bbm.index', compact(
            'search',
            'BBMChanges',
            'BBMStock',
            'BBMUnit',
            'latestBBM',
            'nextChangeDate',
            'totalBBMStock'
        ));
    }

    public function create()
    {
        $date = Carbon::now()->toDateString();

        $masterShips = MasterShip::latest()
            ->get();

        $bbm = MasterItem::where('slug', 'bbm')
            ->first();

        $BBMUnit =  $bbm ? $bbm->masterUnit->name : '-';

        $masterMeachines = MasterMeachine::latest()
        ->get();

        return view('client.pages.bbm.create', compact(
            'date',
            'masterShips',
            'BBMUnit',
            'masterMeachines'
        ));
    }

    public function store()
    {
        $request = request()->all();

        BBM::create([
            'master_user_uuid' => Auth::user()->uuid,
            'master_ship_uuid' => $request['master_ship_uuid'],
            'master_meachine_uuid' => $request['master_meachine_uuid'],
            'start' => $request['start'],
            'stop' => $request['stop'],
            'total' => $request['total'],
            'description' => $request['description'],
        ]);


        // foreach ($request['code'] as $item) {
        //     $requestItemDetailStock = RequestItemDetailStock::where('code', $item)
        //         ->first();

        //     $masterItem = MasterItem::find($requestItemDetailStock->requestItemDetail->master_item_uuid);

        //     $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
        //         ->where('master_ship_uuid', $request['master_ship_uuid'])
        //         ->where('master_item_uuid', $masterItem->uuid)
        //         ->where('position', $requestItemDetailStock->requestItemDetail->position)
        //         ->latest()
        //         ->first();

        //     if ($stockHistoryToday) {
        //         if ($stockHistoryToday->status != 'keluar') {
        //             StockHistory::create([
        //                 'master_item_uuid' => $masterItem->uuid,
        //                 'master_ship_uuid' => $request['master_ship_uuid'],
        //                 'status' => 'keluar',
        //                 'position' => $requestItemDetailStock->requestItemDetail->position,
        //                 'qty' => 1,
        //                 'total' => $stockHistoryToday->total  - 1
        //             ]);
        //         }

        //         if ($stockHistoryToday->status == 'keluar') {
        //             $stockHistoryToday->qty += 1;
        //             $stockHistoryToday->total -= 1;
        //             $stockHistoryToday->save();
        //         }
        //     } else {
        //         $stockHistory = StockHistory::where('master_ship_uuid', $request['master_ship_uuid'])
        //             ->where('master_item_uuid', $masterItem->uuid)
        //             ->where('position', $requestItemDetailStock->requestItemDetail->position)
        //             ->latest()
        //             ->first();

        //         if ($stockHistory) {
        //             if ($stockHistory->status != 'keluar') {
        //                 StockHistory::create([
        //                     'master_item_uuid' => $masterItem->uuid,
        //                     'master_ship_uuid' => $request['master_ship_uuid'],
        //                     'status' => 'keluar',
        //                     'position' => $requestItemDetailStock->requestItemDetail->position,
        //                     'qty' => 1,
        //                     'total' => $stockHistory->total  - 1
        //                 ]);
        //             }

        //             if ($stockHistory->status == 'keluar') {
        //                 StockHistory::create([
        //                     'master_item_uuid' => $masterItem->uuid,
        //                     'master_ship_uuid' => $request['master_ship_uuid'],
        //                     'status' => 'keluar',
        //                     'position' => $requestItemDetailStock->requestItemDetail->position,
        //                     'qty' => 1,
        //                     'total' => $stockHistory->total - 1
        //                 ]);
        //             }
        //         } else {
        //             StockHistory::create([
        //                 'master_item_uuid' => $masterItem->uuid,
        //                 'master_ship_uuid' => $request['master_ship_uuid'],
        //                 'status' => 'keluar',
        //                 'position' => $requestItemDetailStock->requestItemDetail->position,
        //                 'qty' => 1,
        //                 'total' => 1
        //             ]);
        //         }
        //     }

        //     $requestItemDetailStock->used = true;

        //     $requestItemDetailStock->save();
        // }

        return redirect()->route('bbm')->with('success', 'Pegantian bbm Berhasil');
    }

    public function detail($uuid)
    {
        $bbmChange = BBM::find($uuid);

        return view('client.pages.bbm.detail', compact('bbmChange'));
    }
}
