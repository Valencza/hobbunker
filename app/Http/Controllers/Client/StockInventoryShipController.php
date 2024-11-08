<?php

namespace App\Http\Controllers\Client;

use App\Exports\InventoryDeckExport;
use App\Exports\InventoryMeachineExport;
use App\Exports\StockDeckExport;
use App\Exports\StockMeachineExport;
use App\Http\Controllers\Controller;
use App\Models\CrashReport;
use App\Models\CrashReportDetail;
use App\Models\CrashReportDetailPhoto;
use App\Models\Docking;
use App\Models\DockingDetail;
use App\Models\DropItem;
use App\Models\DropItemDetail;
use App\Models\FixReport;
use App\Models\FixReportPhoto;
use App\Models\MasterItem;
use App\Models\MasterShip;
use App\Models\MasterUnit;
use App\Models\OfficeStock;
use App\Models\RequestItem;
use App\Models\RequestItemDetail;
use App\Models\RequestItemDetailStock;
use App\Models\RequestItemDocument;
use App\Models\RoadLetter;
use App\Models\StockHistory;
use App\Models\UsedItem;
use App\Models\UsedItemDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class StockInventoryShipController extends Controller
{
    public function index()
    {
        $search = request('search');

        $masterShips = MasterShip::latest()
            ->where('name', 'LIKE', "%$search%")
            ->paginate(10);

            $masterShips->appends(request()->query());


        return view('client.pages.stock-inventory-ship.index', compact(
            'search',
            'masterShips'
        ));
    }

    public function detail($uuid)
    {
        $masterShip = MasterShip::find($uuid);

        $lastEntries = StockHistory::select('uuid', 'master_item_uuid', 'total', 'created_at')
            ->where('master_ship_uuid', $uuid)
            ->whereHas('masterItem', function ($query) {
                $query->where('type', 'inventaris');
            })
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY master_item_uuid ORDER BY created_at DESC) as row_num')
            ->toBase();

        $lastEntriesQuery = DB::table(DB::raw("({$lastEntries->toSql()}) as sub"))
            ->mergeBindings($lastEntries)
            ->where('sub.row_num', 1);

        $totalInventory = DB::table(DB::raw("({$lastEntriesQuery->toSql()}) as last_entries"))
            ->mergeBindings($lastEntriesQuery)
            ->sum('last_entries.total');

        $lastEntries = StockHistory::select('uuid', 'master_item_uuid', 'total', 'created_at')
            ->where('master_ship_uuid', $uuid)
            ->whereHas('masterItem', function ($query) {
                $query->where('type', 'stok');
            })
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY master_item_uuid ORDER BY created_at DESC) as row_num')
            ->toBase();

        $lastEntriesQuery = DB::table(DB::raw("({$lastEntries->toSql()}) as sub"))
            ->mergeBindings($lastEntries)
            ->where('sub.row_num', 1);

        $totalStock = DB::table(DB::raw("({$lastEntriesQuery->toSql()}) as last_entries"))
            ->mergeBindings($lastEntriesQuery)
            ->sum('last_entries.total');

        $totalRequest = RequestItem::where('master_ship_uuid', $uuid)
            ->where('category', 'permintaan')
            ->count();

        $totalDown = DropItem::where('master_ship_uuid', $uuid)
            ->count();

        $requestItems = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('master_ship_uuid', $uuid)
            ->limit(5)
            ->get();

        return view('client.pages.stock-inventory-ship.detail', compact(
            'masterShip',
            'totalInventory',
            'totalStock',
            'totalRequest',
            'totalDown',
            'requestItems'
        ));
    }

    public function stockOpname($uuid)
    {
        $masterShip = MasterShip::find($uuid);
        $searchMeachine = request('search_meachine');
        $searchDeck = request('search_deck');


        $stocksMeachine =  MasterItem::latest()
            ->whereHas('stockHistory', function ($query) {
                $query->where('position', 'mesin');
            })
            ->paginate(10);

            $stocksMeachine->appends(request()->query());


        foreach ($stocksMeachine as $stockMeachine) {
            $stockMeachine->stockHistory = StockHistory::where('position', 'mesin')
                ->where('master_item_uuid', $stockMeachine->uuid)
                ->latest()
                ->first();
        }

        $stocksDeck =  MasterItem::latest()
            ->whereHas('stockHistory', function ($query) {
                $query->where('position', 'dek');
            })
            ->where('type', 'stok')
            ->paginate(10);

            $stocksDeck->appends(request()->query());


        foreach ($stocksDeck as $stockDeck) {
            $stockDeck->stockHistory = StockHistory::where('position', 'dek')
                ->where('master_item_uuid', $stockDeck->uuid)
                ->latest()
                ->first();
        }

        return view('client.pages.stock-inventory-ship.stock-opname.index', compact(
            'masterShip',
            'searchMeachine',
            'searchDeck',
            'stocksDeck',
            'stocksMeachine'
        ));
    }

    public function stockOpnameExportMeachine($uuid)
    {
        $masterShip = MasterShip::find($uuid);

        $masterItems =  MasterItem::latest()
            ->where('type', 'stok')
            ->whereHas('stockHistory', function ($query) use ($uuid) {
                $query->where('master_ship_uuid', $uuid);
                $query->where('position', 'mesin');
            })
            ->get();

        return Excel::download(new StockMeachineExport($masterItems), "Laporan Stok Mesin Kapal $masterShip->name.xlsx");
    }

    public function stockOpnameExportDeck($uuid)
    {
        $masterShip = MasterShip::find($uuid);

        $masterItems =  MasterItem::latest()
            ->where('type', 'stok')
            ->whereHas('stockHistory', function ($query) use ($uuid) {
                $query->where('master_ship_uuid', $uuid);
                $query->where('position', 'dek');
            })
            ->get();

        return Excel::download(new StockDeckExport($masterItems), "Laporan Stok Dek Kapal $masterShip->name.xlsx");
    }

    public function shipInventory($uuid)
    {
        $masterShip = MasterShip::find($uuid);
        $searchMeachine = request('search_meachine');
        $searchDeck = request('search_deck');

        $stocksMeachine =  MasterItem::latest()
            ->whereHas('stockHistory', function ($query) {
                $query->where('position', 'mesin');
            })
            ->where('type', 'inventaris')
            ->paginate(10);

            $stocksMeachine->appends(request()->query());


        foreach ($stocksMeachine as $stockMeachine) {
            $stockMeachine->stockHistory = StockHistory::where('position', 'mesin')
                ->where('master_item_uuid', $stockMeachine->uuid)
                ->latest()
                ->first();
        }

        $stocksDeck =  MasterItem::latest()
            ->whereHas('stockHistory', function ($query) {
                $query->where('position', 'dek');
            })
            ->where('type', 'inventaris')
            ->paginate(10);

            $stocksDeck->appends(request()->query());
            

        foreach ($stocksDeck as $stockDeck) {
            $stockDeck->stockHistory = StockHistory::where('position', 'dek')
                ->where('master_item_uuid', $stockDeck->uuid)
                ->latest()
                ->first();
        }

        return view('client.pages.stock-inventory-ship.ship-inventory.index', compact(
            'masterShip',
            'searchMeachine',
            'searchDeck',
            'stocksDeck',
            'stocksMeachine'
        ));
    }

    public function shipInventoryExportMeachine($uuid)
    {
        $masterShip = MasterShip::find($uuid);

        $masterItems =  MasterItem::latest()
            ->where('type', 'inventaris')
            ->whereHas('stockHistory', function ($query) use ($uuid) {
                $query->where('master_ship_uuid', $uuid);
                $query->where('position', 'mesin');
            })
            ->get();

        return Excel::download(new InventoryMeachineExport($masterItems), "Laporan Inventaris Mesin Kapal $masterShip->name.xlsx");
    }

    public function shipInventoryExportDeck($uuid)
    {
        $masterShip = MasterShip::find($uuid);

        $masterItems =  MasterItem::latest()
            ->where('type', 'inventaris')
            ->whereHas('stockHistory', function ($query) use ($uuid) {
                $query->where('master_ship_uuid', $uuid);
                $query->where('position', 'dek');
            })
            ->get();

        return Excel::download(new InventoryDeckExport($masterItems), "Laporan Inventaris Dek Kapal $masterShip->name.xlsx");
    }

    public function dropItem($uuid)
    {
        $dropItems = DropItem::where('master_ship_uuid', $uuid)
            ->paginate(10);

            $dropItems->appends(request()->query());


        return view('client.pages.stock-inventory-ship.drop-item.index', compact(
            'uuid',
            'dropItems',
        ));
    }

    public function dropItemDetail($uuid, $dropItemUuid)
    {
        $dropItem = DropItem::find($dropItemUuid);

        return view('client.pages.stock-inventory-ship.drop-item.detail', compact(
            'dropItem'
        ));
    }

    public function dropItemCreate($uuid)
    {

        $masterShips = MasterShip::latest()
            ->get();
        $masterShip = MasterShip::find($uuid);
        $date = Carbon::now()->toDateString();

        return view('client.pages.stock-inventory-ship.drop-item.create', compact(
            'uuid',
            'masterShips',
            'masterShip',
            'date'
        ));
    }

    public function dropItemStore($uuid)
    {
        $request = request()->except('_token');

        $dropItem = DropItem::create([
            'master_user_uuid' => Auth::user()->uuid,
            'master_ship_uuid' => $uuid,
            'date' => $request['date'],
            'title' => $request['title'],
        ]);

        foreach ($request['code'] as $index => $code) {
            $requestItemDetailStock = RequestItemDetailStock::where('code', $code)
                ->first();

            DropItemDetail::create([
                'master_ship_uuid' => $request['master_ship_uuid'][$index],
                'drop_item_uuid' => $dropItem->uuid,
                'request_item_detail_stock_uuid' => $requestItemDetailStock->uuid,
            ]);
        }

        $masterItem = MasterItem::find($requestItemDetailStock->requestItemDetail->master_item_uuid);

        $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
            ->where('master_ship_uuid', $uuid)
            ->where('master_item_uuid', $masterItem->uuid)
            ->where('position', $requestItemDetailStock->requestItemDetail->position)
            ->latest()
            ->first();

        if ($stockHistoryToday) {
            if ($stockHistoryToday->status != 'keluar') {
                StockHistory::create([
                    'master_item_uuid' => $masterItem->uuid,
                    'master_ship_uuid' => $uuid,
                    'status' => 'keluar',
                    'position' => $requestItemDetailStock->requestItemDetail->position,
                    'qty' => 1,
                    'total' => $stockHistoryToday->total  - 1
                ]);
            }

            if ($stockHistoryToday->status == 'keluar') {
                $stockHistoryToday->qty += 1;
                $stockHistoryToday->total -= 1;
                $stockHistoryToday->save();
            }
        } else {
            $stockHistory = StockHistory::where('master_ship_uuid', $uuid)
                ->where('master_item_uuid', $masterItem->uuid)
                ->where('position', $requestItemDetailStock->requestItemDetail->position)
                ->latest()
                ->first();

            if ($stockHistory) {
                if ($stockHistory->status != 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $masterItem->uuid,
                        'master_ship_uuid' => $uuid,
                        'status' => 'keluar',
                        'position' => $requestItemDetailStock->requestItemDetail->position,
                        'qty' => 1,
                        'total' => $stockHistory->total  - 1
                    ]);
                }

                if ($stockHistory->status == 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $masterItem->uuid,
                        'master_ship_uuid' => $uuid,
                        'status' => 'keluar',
                        'position' => $requestItemDetailStock->requestItemDetail->position,
                        'qty' => 1,
                        'total' => $stockHistory->total - 1
                    ]);
                }
            } else {
                StockHistory::create([
                    'master_item_uuid' => $masterItem->uuid,
                    'master_ship_uuid' => $uuid,
                    'status' => 'keluar',
                    'position' => $requestItemDetailStock->requestItemDetail->position,
                    'qty' => 1,
                    'total' => 1
                ]);
            }
        }

        return redirect()->route('stock-inventory-ship.drop-item', $uuid)->with('success', 'Pengiriman baru berhasil dibuat');
    }

    public function requestItem($uuid, $type)
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);

        $requestItemsNew = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('status', 'baru')
            ->where('type', $type)
            ->where('master_ship_uuid', $uuid)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);
        $requestItemsConfirmed = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('status', 'terkonfirmasi')
            ->where('type', $type)
            ->where('master_ship_uuid', $uuid)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);
        $requestItemsReady = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('status', 'siap dikirim')
            ->where('type', $type)
            ->where('master_ship_uuid', $uuid)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);
        $requestItemsDelivered = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('status', 'selesai')
            ->where('type', $type)
            ->where('master_ship_uuid', $uuid)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);
        $requestItemsRejected = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('status', 'ditolak')
            ->where('type', $type)
            ->where('master_ship_uuid', $uuid)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);

            
            $requestItemsNew->appends(request()->query());
            $requestItemsConfirmed->appends(request()->query());
            $requestItemsReady->appends(request()->query());
            $requestItemsDelivered->appends(request()->query());
            $requestItemsRejected->appends(request()->query());


        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view('client.pages.stock-inventory-ship.request-item.index', compact(
            'uuid',
            'type',
            'requestItemsNew',
            'requestItemsConfirmed',
            'requestItemsReady',
            'requestItemsDelivered',
            'requestItemsRejected',
            'startDate',
            'endDate'
        ));
    }

    public function requestItemCreate($uuid, $type)
    {
        $date = Carbon::now()->toDateString();
        $masterShip = MasterShip::find($uuid);
        $masterItems = MasterItem::latest()
            ->with('masterUnit')
            ->where('type', $type)
            ->get();

        return view('client.pages.stock-inventory-ship.request-item.create', compact(
            'type',
            'date',
            'masterShip',
            'masterItems'
        ));
    }

    public function requestItemStore($uuid, $type)
    {
        $request = request()->except('_token');


        $requestItem = RequestItem::create([
            'master_user_uuid' => Auth::user()->uuid,
            'type' => $type,
            'master_ship_uuid' => $uuid,
            'number' => 'R-' . strtoupper(substr(Str::uuid(), 0, 5)),
        ]);

        foreach ($request['master_item'] as $key => $item) {
            $qty = intval($request['qty'][$key]);

            $image = null;

            if (isset(request()->file('image')[$key])) {
                $image =  request()->file('image')[$key];
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/request-items'), $fileName);
                $image = "images/request-items/$fileName";
            }

            $check = MasterItem::find($item);

            RequestItemDetail::create([
                'request_item_uuid' => $requestItem->uuid,
                'master_item_uuid' =>  $check ? $item : 'new~' . $item . '~' . $request['unit'][$key] . '~' . $request['merk'][$key],
                'qty' => $qty,
                'position' => $request['position'][$key],
                'description' => $request['description'][$key],
                'image' => $image,
            ]);
        }

        return redirect()->route('stock-inventory-ship.request-item', [$uuid, $type])->with('success', 'Pengiriman baru berhasil dibuat');
    }

    public function requestItemDetail($uuid)
    {
        $requestItem = RequestItem::find($uuid);

        return view('client.pages.stock-inventory-ship.request-item.detail', compact(
            'requestItem',
        ));
    }

    public function requestItemCheck($uuid)
    {
        $requestItem = RequestItem::find($uuid);

        return view(
            'client.pages.stock-inventory-ship.request-item.check',
            compact('requestItem')
        );
    }

    public function requestItemUpdate($uuid)
    {
        $request = request()->all();

        $requestItem = RequestItem::find($uuid);
        $requestItem->status = 'selesai';
        $requestItem->description = $request['description'];
        $requestItem->save();

        if (request()->hasFile('image')) {
            foreach (request()->file('image') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/request-items'), $fileName);
                $request['file'] = "images/request-items/$fileName";

                RequestItemDocument::create([
                    'request_item_uuid' =>  $uuid,
                    'type' =>  'barang',
                    'file' =>   $request['file'],
                ]);
            }
        }

        if (request()->hasFile('road_letter')) {
            $fileName = time() . '_' . request()->file('road_letter')->getClientOriginalName();
            $file->move(public_path('images/request-items'), $fileName);
            $request['file'] = "images/request-items/$fileName";

            RequestItemDocument::create([
                'request_item_uuid' =>  $uuid,
                'type' =>  'surat jalan',
                'file' =>   $request['file'],
            ]);
        }

        RoadLetter::where('request_item_uuid', $uuid)
            ->update([
                'scanned' => true,
                'status' => 'selesai'
            ]);

        $requestItemDetails = RequestItemDetail::where('request_item_uuid', $uuid)
            ->get();

        foreach ($requestItemDetails as $requestItemDetail) {
            $requestItemDetailStocks = RequestItemDetailStock::where('request_item_detail_uuid', $requestItemDetail->uuid)
                ->get();

            foreach ($requestItemDetailStocks as $requestItemDetailStock) {
                OfficeStock::where('code', $requestItemDetailStock->code)
                    ->update([
                        'used' => true
                    ]);

                $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
                    ->whereNull('master_ship_uuid')
                    ->where('master_item_uuid', $requestItemDetail->master_item_uuid)
                    ->whereNull('position')
                    ->latest()
                    ->first();

                if ($stockHistoryToday) {
                    if ($stockHistoryToday->status != 'keluar') {
                        StockHistory::create([
                            'master_item_uuid' => $requestItemDetail->master_item_uuid,
                            'status' => 'keluar',
                            'qty' => 1,
                            'total' => $stockHistoryToday->total - 1
                        ]);
                    }

                    if ($stockHistoryToday->status == 'keluar') {
                        $stockHistoryToday->qty += 1;
                        $stockHistoryToday->total -= 1;
                        $stockHistoryToday->save();
                    }
                } else {
                    $stockHistory = StockHistory::whereNull('master_ship_uuid')
                        ->where('master_item_uuid', $requestItemDetail->master_item_uuid)
                        ->whereNull('position')
                        ->latest()
                        ->first();

                    if ($stockHistory) {
                        if ($stockHistory->status != 'keluar') {
                            StockHistory::create([
                                'master_item_uuid' => $requestItemDetail->master_item_uuid,
                                'status' => 'keluar',
                                'qty' => 1,
                                'total' => $stockHistory->total  - 1
                            ]);
                        }

                        if ($stockHistory->status == 'keluar') {
                            StockHistory::create([
                                'master_item_uuid' => $requestItemDetail->master_item_uuid,
                                'status' => 'keluar',
                                'qty' => 1,
                                'total' => $stockHistory->total - 1
                            ]);
                        }
                    } else {
                        StockHistory::create([
                            'master_item_uuid' => $requestItemDetail->master_item_uuid,
                            'status' => 'keluar',
                            'qty' => 1,
                            'total' => 1
                        ]);
                    }
                }

                $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
                    ->where('master_ship_uuid', Auth::user()->master_ship_uuid)
                    ->where('master_item_uuid', $requestItemDetail->master_item_uuid)
                    ->where('position', $requestItemDetail->position)
                    ->latest()
                    ->first();

                if ($stockHistoryToday) {
                    if ($stockHistoryToday->status != 'masuk') {
                        StockHistory::create([
                            'master_ship_uuid' => Auth::user()->master_ship_uuid,
                            'master_item_uuid' => $requestItemDetail->master_item_uuid,
                            'status' => 'masuk',
                            'position' => $requestItemDetail->position,
                            'qty' => 1,
                            'total' => $stockHistoryToday->total + 1
                        ]);
                    }

                    if ($stockHistoryToday->status == 'masuk') {
                        $stockHistoryToday->qty += 1;
                        $stockHistoryToday->total += 1;
                        $stockHistoryToday->save();
                    }
                } else {
                    $stockHistory = StockHistory::whereNull('master_ship_uuid')
                        ->where('master_ship_uuid', Auth::user()->master_ship_uuid)
                        ->where('master_item_uuid', $requestItemDetail->master_item_uuid)
                        ->latest()
                        ->first();

                    if ($stockHistory) {
                        if ($stockHistory->status != 'masuk') {
                            StockHistory::create([
                                'master_ship_uuid' => Auth::user()->master_ship_uuid,
                                'master_item_uuid' => $requestItemDetail->master_item_uuid,
                                'position' => $requestItemDetail->position,
                                'status' => 'masuk',
                                'qty' => 1,
                                'total' => $stockHistory->total + 1
                            ]);
                        }

                        if ($stockHistory->status == 'masuk') {
                            StockHistory::create([
                                'master_ship_uuid' => Auth::user()->master_ship_uuid,
                                'master_item_uuid' => $requestItemDetail->master_item_uuid,
                                'position' => $requestItemDetail->position,
                                'status' => 'masuk',
                                'qty' => 1,
                                'total' => $stockHistory->total + 1
                            ]);
                        }
                    } else {
                        StockHistory::create([
                            'master_ship_uuid' => Auth::user()->master_ship_uuid,
                            'master_item_uuid' => $requestItemDetail->master_item_uuid,
                            'position' => $requestItemDetail->position,
                            'status' => 'masuk',
                            'qty' => 1,
                            'total' => 1
                        ]);
                    }
                }
            }
        }

        return redirect()->route('stock-inventory-ship.request-item', [$requestItem->master_ship_uuid, $requestItem->type])->with('success', 'Pengiriman berhasil diselesaikan');
    }

    public function usedItem($uuid)
    {
        $usedItems = UsedItem::latest()
            ->where('master_ship_uuid', $uuid)
            ->paginate(10);

            $usedItems->appends(request()->query());


        return view('client.pages.stock-inventory-ship.used-item.index', compact(
            'uuid',
            'usedItems',
        ));
    }

    public function usedItemCreate($uuid)
    {
        $usedItems = UsedItem::latest()
            ->where('master_ship_uuid', $uuid)
            ->paginate(10);

            $usedItems->appends(request()->query());

            
        return view('client.pages.stock-inventory-ship.used-item.create', compact(
            'uuid',
            'usedItems',
        ));
    }

    public function usedItemStore($uuid)
    {
        $request = request()->except('_token');

        $usedItem = UsedItem::create([
            'master_ship_uuid' => $uuid,
            'date' => $request['date'],
            'title' => $request['title'],
            'description' => $request['description']
        ]);

        foreach ($request['code'] as $item) {
            $requestItemDetailStock = RequestItemDetailStock::where('code', $item)
                ->first();

            $masterItem = MasterItem::find($requestItemDetailStock->requestItemDetail->master_item_uuid);

            $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
                ->where('master_ship_uuid', $uuid)
                ->where('master_item_uuid', $masterItem->uuid)
                ->where('position', $requestItemDetailStock->requestItemDetail->position)
                ->latest()
                ->first();

            if ($stockHistoryToday) {
                if ($stockHistoryToday->status != 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $masterItem->uuid,
                        'master_ship_uuid' => $uuid,
                        'status' => 'keluar',
                        'position' => $requestItemDetailStock->requestItemDetail->position,
                        'qty' => 1,
                        'total' => $stockHistoryToday->total  - 1
                    ]);
                }

                if ($stockHistoryToday->status == 'keluar') {
                    $stockHistoryToday->qty += 1;
                    $stockHistoryToday->total -= 1;
                    $stockHistoryToday->save();
                }
            } else {
                $stockHistory = StockHistory::where('master_ship_uuid', $uuid)
                    ->where('master_item_uuid', $masterItem->uuid)
                    ->where('position', $requestItemDetailStock->requestItemDetail->position)
                    ->latest()
                    ->first();

                if ($stockHistory) {
                    if ($stockHistory->status != 'keluar') {
                        StockHistory::create([
                            'master_item_uuid' => $masterItem->uuid,
                            'master_ship_uuid' => $uuid,
                            'status' => 'keluar',
                            'position' => $requestItemDetailStock->requestItemDetail->position,
                            'qty' => 1,
                            'total' => $stockHistory->total  - 1
                        ]);
                    }

                    if ($stockHistory->status == 'keluar') {
                        StockHistory::create([
                            'master_item_uuid' => $masterItem->uuid,
                            'master_ship_uuid' => $uuid,
                            'status' => 'keluar',
                            'position' => $requestItemDetailStock->requestItemDetail->position,
                            'qty' => 1,
                            'total' => $stockHistory->total - 1
                        ]);
                    }
                } else {
                    StockHistory::create([
                        'master_item_uuid' => $masterItem->uuid,
                        'master_ship_uuid' => $uuid,
                        'status' => 'keluar',
                        'position' => $requestItemDetailStock->requestItemDetail->position,
                        'qty' => 1,
                        'total' => 1
                    ]);
                }
            }

            $requestItemDetailStock->used = true;

            $requestItemDetailStock->save();

            UsedItemDetail::create([
                'used_item_uuid' => $usedItem->uuid,
                'request_item_detail_stock_uuid' => $requestItemDetailStock->uuid,
            ]);
        }

        return redirect()->route('stock-inventory-ship.used-item', $uuid)->with('success', 'Pemakaian baru berhasil dibuat');
    }

    public function usedItemDetail($uuid)
    {
        $usedItem = UsedItem::find($uuid);

        return view('client.pages.stock-inventory-ship.used-item.detail', compact(
            'usedItem'
        ));
    }

    public function crashReport($uuid)
    {
        $crashReportsNew = CrashReport::where('master_ship_uuid', $uuid)
            ->latest()
            ->where('status', 'baru')
            ->paginate(10);
        $crashReportsProcess = CrashReport::where('master_ship_uuid', $uuid)
            ->latest()
            ->where('status', 'proses')
            ->paginate(10);
        $crashReportsDone = CrashReport::where('master_ship_uuid', $uuid)
            ->latest()
            ->where('status', 'selesai')
            ->paginate(10);

            $crashReportsNew->appends(request()->query());
            $crashReportsProcess->appends(request()->query());
            $crashReportsDone->appends(request()->query());


        return view('client.pages.stock-inventory-ship.crash-report.index', compact(
            'uuid',
            'crashReportsNew',
            'crashReportsProcess',
            'crashReportsDone'
        ));
    }


    public function crashReportCreate($uuid)
    {
        $masterShip = MasterShip::find($uuid);
        $date = Carbon::now()->toDateString();

        return view('client.pages.stock-inventory-ship.crash-report.create', compact(
            'uuid',
            'masterShip',
            'date'
        ));
    }

    public function crashReportStore($uuid)
    {
        $request = request()->all();

        $crashReport = CrashReport::create([
            'master_ship_uuid' => $uuid,
            'master_user_uuid' => $request['master_user_uuid'],
            'title' => $request['title'],
            'name_position' => $request['name_position'],
        ]);

        foreach ($request['position'] as $index => $position) {
            $crashReportDetail = CrashReportDetail::create([
                'crash_report_uuid' => $crashReport->uuid,
                'position' => $position,
                'object' => $request['object'][$index],
                'description' => $request['description'][$index],
                'reason' => $request['reason'][$index] ?? null,
            ]);

            foreach (request()->file('image')[$index] as $image) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/crash-reports'), $fileName);

                CrashReportDetailPhoto::create([
                    'crash_report_detail_uuid' => $crashReportDetail->uuid,
                    'image' => "images/crash-reports/$fileName"
                ]);
            }
        }

        return redirect()->route('stock-inventory-ship.crash-report', $uuid)->with('success', 'Laporan Kerusakan Berhasil Dibuat');
    }

    public function crashReportDetail($uuid, $crashReportUuid)
    {
        $crashReport =  CrashReport::find($crashReportUuid);

        return view('client.pages.stock-inventory-ship.crash-report.detail', compact(
            'uuid',
            'crashReport'
        ));
    }

    public function crashReportUpdate($uuid)
    {
        CrashReportDetail::where('uuid', $uuid)
            ->update([
                'status' => 'selesai'
            ]);

        $fixReport = FixReport::create([
            'crash_report_detail_uuid' => $uuid,
            'description' => request('description')
        ]);

        foreach (request()->file('photo') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/crash_reports'), $fileName);
            $request['file'] = "images/crash_reports/$fileName";

            FixReportPhoto::create([
                'fix_report_uuid' =>  $fixReport['uuid'],
                'image' =>  $request['file'],
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Laporan perbaikan berhasil dibuat');
    }

    public function crashReportDone($uuid)
    {
        CrashReport::where('uuid', $uuid)
            ->update([
                'status' => 'selesai'
            ]);

        return redirect()
            ->route('stock-inventory-ship.crash-report', Auth::user()->master_ship_uuid)
            ->with('success', 'Kerusakan berhasil diselesaikan');
    }

    public function docking($uuid)
    {
        $dockings = Docking::where('master_ship_uuid', $uuid)
            ->latest()
            ->paginate(10);
            $dockings->appends(request()->query());

            

        $latestDocking = Docking::latest()
            ->where('master_ship_uuid', $uuid)
            ->first();

        $nextDockingDate = null;

        if ($latestDocking) {
            $nextDockingDate = Carbon::parse($latestDocking->created_at)->addYears(5)->format('d M Y');
        }

        return view(
            'client.pages.stock-inventory-ship.docking.index',
            compact(
                'uuid',
                'dockings',
                'nextDockingDate'
            )
        );
    }

    public function dockingCreate($uuid)
    {
        $dockings = Docking::where('master_ship_uuid', $uuid)
            ->paginate(10);

            $dockings->appends(request()->query());


        return view(
            'client.pages.stock-inventory-ship.docking.create',
            compact(
                'uuid',
                'dockings'
            )
        );
    }

    public function dockingStore($uuid)
    {
        $request = request()->all();

        $docking = Docking::create([
            'master_ship_uuid' => $uuid,
            'title' => $request['title'],
            'description' => $request['description'],
            'type' => $request['type'],
            'cost' => $request['cost'],
        ]);

        if (request()->hasFile('administrasi')) {
            foreach (request()->file('administrasi') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/dockings'), $fileName);
                $request['file'] = "images/dockings/$fileName";

                DockingDetail::create([
                    'docking_uuid' =>  $docking['uuid'],
                    'type' =>  'administrasi',
                    'file' =>   $request['file'],
                ]);
            }
        }

        if (request()->hasFile('laporan')) {
            foreach (request()->file('laporan') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/dockings'), $fileName);
                $request['file'] = "images/dockings/$fileName";

                DockingDetail::create([
                    'docking_uuid' =>  $docking['uuid'],
                    'type' =>  'laporan',
                    'file' =>   $request['file'],
                ]);
            }
        }


        if (request()->hasFile('sertifikat')) {
            foreach (request()->file('sertifikat') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/dockings'), $fileName);
                $request['file'] = "images/dockings/$fileName";

                DockingDetail::create([
                    'docking_uuid' =>  $docking['uuid'],
                    'type' =>  'sertifikat',
                    'file' =>   $request['file'],
                ]);
            }
        }

        if (request()->hasFile('penawaran_1')) {
            foreach (request()->file('penawaran_1') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/dockings'), $fileName);
                $request['file'] = "images/dockings/$fileName";

                DockingDetail::create([
                    'docking_uuid' =>  $docking['uuid'],
                    'type' =>  'penawaran_1',
                    'file' =>   $request['file'],
                ]);
            }
        }

        if (request()->hasFile('penawaran_2')) {
            foreach (request()->file('penawaran_2') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/dockings'), $fileName);
                $request['file'] = "images/dockings/$fileName";

                DockingDetail::create([
                    'docking_uuid' =>  $docking['uuid'],
                    'type' =>  'penawaran_2',
                    'file' =>   $request['file'],
                ]);
            }
        }

        if (request()->hasFile('penawaran_3')) {
            foreach (request()->file('penawaran_3') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/dockings'), $fileName);
                $request['file'] = "images/dockings/$fileName";

                DockingDetail::create([
                    'docking_uuid' =>  $docking['uuid'],
                    'type' =>  'penawaran_3',
                    'file' =>   $request['file'],
                ]);
            }
        }

        if (request()->hasFile('perbaikan')) {
            foreach (request()->file('perbaikan') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/dockings'), $fileName);
                $request['file'] = "images/dockings/$fileName";

                DockingDetail::create([
                    'docking_uuid' =>  $docking['uuid'],
                    'type' =>  'perbaikan',
                    'file' =>   $request['file'],
                ]);
            }
        }

        return redirect()->route('stock-inventory-ship.docking', $uuid)->with('success', 'Riwayat docking berhasil dibuat');
    }

    public function dockingDetail($uuid, $dockingUuid)
    {
        $docking = Docking::find($dockingUuid);

        $dockingAdmninistrations = DockingDetail::where('docking_uuid', $dockingUuid)
            ->where('type', 'administrasi')
            ->get();

        $dockingReports = DockingDetail::where('docking_uuid', $dockingUuid)
            ->where('type', 'laporan')
            ->get();


        $dockingCertificates = DockingDetail::where('docking_uuid', $dockingUuid)
            ->where('type', 'sertifikat')
            ->get();

        $dockingOffers1 = DockingDetail::where('docking_uuid', $dockingUuid)
            ->where('type', 'penawaran_1')
            ->get();


        $dockingOffers2 = DockingDetail::where('docking_uuid', $dockingUuid)
            ->where('type', 'penawaran_2')
            ->get();

        $dockingOffers3 = DockingDetail::where('docking_uuid', $dockingUuid)
            ->where('type', 'penawaran_3')
            ->get();

        $dockingFixs = DockingDetail::where('docking_uuid', $dockingUuid)
            ->where('type', 'perbaikan')
            ->get();

        return view(
            'client.pages.stock-inventory-ship.docking.detail',
            compact(
                'uuid',
                'docking',
                'dockingAdmninistrations',
                'dockingReports',
                'dockingCertificates',
                'dockingOffers1',
                'dockingOffers2',
                'dockingOffers3',
                'dockingFixs',
            )
        );
    }

    public function stockCreate()
    {
        $date = Carbon::now()->toDateString();

        $masterItems = MasterItem::latest()
            ->where('type', 'stok')
            ->with('masterUnit')
            ->get();

        return view('client.pages.stock-inventory-ship.stock.create', compact(
            'date',
            'masterItems'
        ));
    }

    public function stockBarcode()
    {
        return view('client.pages.stock-inventory-ship.stock.barcode');
    }

    public function stockStore()
    {
        $request = request()->all();
        $user = Auth::user();

        foreach ($request['code'] as $index => $codes) {
            foreach ($codes as $index2 => $code) {

                $masterItem = MasterItem::find($request['uuid'][$index][$index2]);

                if(!$masterItem){
                    $masterUnit  = MasterUnit::whereRaw('LOWER(name) = ?', [strtolower($request['unit'][$index][$index2])])
                    ->first();
        
                    if (!$masterUnit) {
                        $masterUnit = MasterUnit::create([
                            'name' => $request['unit'][$index][$index2],
                        ]);
                    }

                    MasterItem::create([
                        'uuid' => $request['uuid'][$index][$index2],
                        'master_unit_uuid' => $masterUnit->uuid,
                        'slug' => Str::slug($request['name'][$index][$index2]),
                        'name' => Str::slug($request['name'][$index][$index2]),
                        'merk' => Str::slug($request['merk'][$index][$index2]),
                        'type' => 'stok',
                    ]);
                }

                $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
                    ->where('master_ship_uuid', $user->master_ship_uuid)
                    ->where('position', $request['position'][$index])
                    ->where('master_item_uuid', $request['uuid'][$index][$index2])
                    ->latest()
                    ->first();

                if ($stockHistoryToday) {
                    if ($stockHistoryToday->status != 'masuk') {
                        StockHistory::create([
                            'master_ship_uuid' => $user->master_ship_uuid,
                            'position' => $request['position'][$index],
                            'master_item_uuid' => $request['uuid'][$index][$index2],
                            'status' => 'masuk',
                            'qty' => 1,
                            'total' => $stockHistoryToday->total + 1
                        ]);
                    }

                    if ($stockHistoryToday->status == 'masuk') {
                        $stockHistoryToday->qty += 1;
                        $stockHistoryToday->total += 1;
                        $stockHistoryToday->save();
                    }
                } else {
                    $stockHistory = StockHistory::where('master_ship_uuid', $user->master_ship_uuid)
                        ->where('position', $request['position'][$index])
                        ->where('master_item_uuid', $request['uuid'][$index][$index2])
                        ->latest()
                        ->first();

                    if ($stockHistory) {
                        if ($stockHistory->status != 'masuk') {
                            StockHistory::create([
                                'master_ship_uuid' => $user->master_ship_uuid,
                                'position' => $request['position'][$index],
                                'master_item_uuid' => $request['uuid'][$index][$index2],
                                'status' => 'masuk',
                                'qty' => 1,
                                'total' => $stockHistory->total + 1
                            ]);
                        }

                        if ($stockHistory->status == 'masuk') {
                            StockHistory::create([
                                'master_ship_uuid' => $user->master_ship_uuid,
                                'position' => $request['position'][$index],
                                'master_item_uuid' => $request['uuid'][$index][$index2],
                                'status' => 'masuk',
                                'qty' => 1,
                                'total' => $stockHistory->total + 1
                            ]);
                        }
                    } else {
                        StockHistory::create([
                            'master_ship_uuid' => $user->master_ship_uuid,
                            'position' => $request['position'][$index],
                            'master_item_uuid' => $request['uuid'][$index][$index2],
                            'status' => 'masuk',
                            'qty' => 1,
                            'total' => 1
                        ]);
                    }
                }

                $requestItem  = RequestItem::create([
                    'master_user_uuid' => $user->uuid,
                    'master_ship_uuid' => $user->master_ship_uuid,
                    'number' => 'R-' . strtoupper(substr(Str::uuid(), 0, 5)),
                    'type' => 'stok',
                    'category' => 'penambahan',
                ]);

                $requestItemDetail = RequestItemDetail::create([
                    'request_item_uuid' => $requestItem->uuid,
                    'master_item_uuid' => $request['uuid'][$index][$index2],
                    'qty' => $request['qty'][$index],
                    'position' => $request['position'][$index],
                ]);

                RequestItemDetailStock::create([
                    'request_item_detail_uuid' => $requestItemDetail->uuid,
                    'code' => $request['code'][$index][$index2],
                    'barcode' => $request['barcode'][$index][$index2],
                ]);
            }
        }

        return redirect()->route('stock-inventory-ship.stock-opname', $user->master_ship_uuid)->with('success', 'Stok kapal berhasil ditambahkan');
    }

    public function inventoryCreate()
    {
        $date = Carbon::now()->toDateString();

        $masterItems = MasterItem::latest()
            ->where('type', 'inventaris')
            ->with('masterUnit')
            ->get();

        return view('client.pages.stock-inventory-ship.inventory.create', compact(
            'date',
            'masterItems'
        ));
    }

    public function inventoryBarcode()
    {
        return view('client.pages.stock-inventory-ship.inventory.barcode');
    }

    public function inventoryStore()
    {
        $request = request()->all();
        $user = Auth::user();

        foreach ($request['code'] as $index => $codes) {
            foreach ($codes as $index2 => $code) {
                $masterItem = MasterItem::find($request['uuid'][$index][$index2]);

                if(!$masterItem){
                    $masterUnit  = MasterUnit::whereRaw('LOWER(name) = ?', [strtolower($request['unit'][$index][$index2])])
                    ->first();
        
                    if (!$masterUnit) {
                        $masterUnit = MasterUnit::create([
                            'name' => $request['unit'][$index][$index2],
                        ]);
                    }

                    MasterItem::create([
                        'uuid' => $request['uuid'][$index][$index2],
                        'master_unit_uuid' => $masterUnit->uuid,
                        'slug' => Str::slug($request['name'][$index][$index2]),
                        'name' => Str::slug($request['name'][$index][$index2]),
                        'merk' => Str::slug($request['merk'][$index][$index2]),
                        'type' => 'inventaris',
                    ]);
                }

                $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
                    ->where('master_ship_uuid', $user->master_ship_uuid)
                    ->where('position', $request['position'][$index])
                    ->where('master_item_uuid', $request['uuid'][$index][$index2])
                    ->latest()
                    ->first();

                if ($stockHistoryToday) {
                    if ($stockHistoryToday->status != 'masuk') {
                        StockHistory::create([
                            'master_ship_uuid' => $user->master_ship_uuid,
                            'position' => $request['position'][$index],
                            'master_item_uuid' => $request['uuid'][$index][$index2],
                            'status' => 'masuk',
                            'qty' => 1,
                            'total' => $stockHistoryToday->total + 1
                        ]);
                    }

                    if ($stockHistoryToday->status == 'masuk') {
                        $stockHistoryToday->qty += 1;
                        $stockHistoryToday->total += 1;
                        $stockHistoryToday->save();
                    }
                } else {
                    $stockHistory = StockHistory::where('master_ship_uuid', $user->master_ship_uuid)
                        ->where('position', $request['position'][$index])
                        ->where('master_item_uuid', $request['uuid'][$index][$index2])
                        ->latest()
                        ->first();

                    if ($stockHistory) {
                        if ($stockHistory->status != 'masuk') {
                            StockHistory::create([
                                'master_ship_uuid' => $user->master_ship_uuid,
                                'position' => $request['position'][$index],
                                'master_item_uuid' => $request['uuid'][$index][$index2],
                                'status' => 'masuk',
                                'qty' => 1,
                                'total' => $stockHistory->total + 1
                            ]);
                        }

                        if ($stockHistory->status == 'masuk') {
                            StockHistory::create([
                                'master_ship_uuid' => $user->master_ship_uuid,
                                'position' => $request['position'][$index],
                                'master_item_uuid' => $request['uuid'][$index][$index2],
                                'status' => 'masuk',
                                'qty' => 1,
                                'total' => $stockHistory->total + 1
                            ]);
                        }
                    } else {
                        StockHistory::create([
                            'master_ship_uuid' => $user->master_ship_uuid,
                            'position' => $request['position'][$index],
                            'master_item_uuid' => $request['uuid'][$index][$index2],
                            'status' => 'masuk',
                            'qty' => 1,
                            'total' => 1
                        ]);
                    }
                }

                $requestItem  = RequestItem::create([
                    'master_user_uuid' => $user->uuid,
                    'master_ship_uuid' => $user->master_ship_uuid,
                    'number' => 'R-' . strtoupper(substr(Str::uuid(), 0, 5)),
                    'type' => 'inventaris',
                    'category' => 'penambahan',
                ]);

                $requestItemDetail = RequestItemDetail::create([
                    'request_item_uuid' => $requestItem->uuid,
                    'master_item_uuid' => $request['uuid'][$index][$index2],
                    'qty' => $request['qty'][$index],
                    'position' => $request['position'][$index],
                ]);

                RequestItemDetailStock::create([
                    'request_item_detail_uuid' => $requestItemDetail->uuid,
                    'code' => $request['code'][$index][$index2],
                    'barcode' => $request['barcode'][$index][$index2],
                ]);
            }
        }

        return redirect()->route('stock-inventory-ship.ship-inventory', $user->master_ship_uuid)->with('success', 'Inventaris kapal berhasil ditambahkan');
    }
}
