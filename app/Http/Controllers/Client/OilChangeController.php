<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MasterItem;
use App\Models\MasterMeachine;
use App\Models\MasterShip;
use App\Models\OilChange;
use App\Models\OilChangeDetail;
use App\Models\RequestItemDetailStock;
use App\Models\StockHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OilChangeController extends Controller
{
    public function index()
    {

        $search =  request('search');

        $oilChanges = OilChange::latest()
            ->where('master_ship_uuid', Auth::user()->master_ship_uuid)
            ->paginate(10);

            $oilChanges->appends(request()->query());


        $oilStock = StockHistory::whereHas('masterItem', function ($query) {
            $query->where('slug', 'LIKE', '%oli%');
        })
            ->where('master_ship_uuid', Auth::user()->master_ship_uuid)
            ->select('master_item_uuid', DB::raw('MAX(master_item_uuid) as latest_id'))
            ->groupBy('master_item_uuid')
            ->pluck('latest_id');

        $totalOilStock = StockHistory::whereIn('master_item_uuid', $oilStock)->sum('total');

        $oilUnit =  'Drum';

        $latestOilChange = OilChange::latest()
            ->where('master_ship_uuid', Auth::user()->master_ship_uuid)
            ->first();

        $nextChangeDate = null;

        if ($latestOilChange) {
            $nextChangeDate = Carbon::parse($latestOilChange->created_at)->addHours($latestOilChange->hour)->format('d M Y');
        }

        return view('client.pages.oil-change.index', compact(
            'search',
            'oilChanges',
            'oilStock',
            'oilUnit',
            'latestOilChange',
            'nextChangeDate',
            'totalOilStock'
        ));
    }

    public function create()
    {
        $date = Carbon::now()->toDateString();

        $masterShips = MasterShip::latest()
            ->get();

        $masterMeachines = MasterMeachine::latest()
            ->get();

        $oil = MasterItem::where('slug', 'oli')
            ->first();

        $oilUnit =  $oil ? $oil->masterUnit->name : '-';

        return view('client.pages.oil-change.create', compact(
            'date',
            'masterShips',
            'masterMeachines',
            'oilUnit'
        ));
    }

    public function store()
    {
        $request = request()->all();

        $oilChange = OilChange::create([
            'master_user_uuid' => Auth::user()->uuid,
            'master_ship_uuid' => $request['master_ship_uuid'],
            'section' => $request['section'],
            'master_meachine_uuid' => $request['master_meachine_uuid'],
            'hour' => MasterMeachine::find(request('master_meachine_uuid'))->hours
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

        //     OilChangeDetail::create([
        //         'oil_change_uuid' => $oilChange->uuid,
        //         'merk' => $masterItem->merk,
        //         'qty' => 1,
        //         'position' => $requestItemDetailStock->requestItemDetail->position,
        //     ]);
        // }

        return redirect()->route('oil-change')->with('success', 'Pegantian Oli Berhasil');
    }

    public function detail($uuid)
    {
        $oilChange = OilChange::find($uuid);

        return view('client.pages.oil-change.detail', compact('oilChange'));
    }
}
