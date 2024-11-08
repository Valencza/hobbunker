<?php

namespace App\Http\Controllers\Client;

use App\Exports\OfficeInventoryExport;
use App\Exports\OfficeStockExport;
use App\Http\Controllers\Controller;
use App\Models\MasterItem;
use App\Models\MasterShip;
use App\Models\MasterUnit;
use App\Models\OfficeStock;
use App\Models\RequestItem;
use App\Models\RequestItemDetail;
use App\Models\RequestItemDetailStock;
use App\Models\RoadLetter;
use App\Models\StockHistory;
use App\Models\UsedItem;
use App\Models\UsedItemDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class StockInventoryOfficeController extends Controller
{
    public function index()
    {
        $lastEntries = StockHistory::select('uuid', 'master_item_uuid', 'total', 'created_at')
            ->whereNull('master_ship_uuid')
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
            ->whereNull('master_ship_uuid')
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

        $totalRequest = RequestItem::where('category', 'permintaan')
            ->count();

        $totalDown = RequestItem::where('status', 'selesai')
            ->where('category', 'permintaan')
            ->count();

        $requestItems = RequestItem::latest()
            ->where('category', 'permintaan')
            ->limit(5)
            ->get();

        return view('client.pages.stock-inventory-office.index', compact(
            'totalInventory',
            'totalStock',
            'totalRequest',
            'totalDown',
            'requestItems'
        ));
    }

    public function stock()
    {
        $search = request('search');

        $masterItems = MasterItem::latest()
            ->where('type', 'stok')
            ->where('name', 'LIKE', "%$search%")
            ->whereHas('stockHistory', function ($query) {
                $query->whereNull('master_ship_uuid');
            })
            ->paginate(10);

            $masterItems->appends(request()->query());

        return view('client.pages.stock-inventory-office.stock.index', compact(
            'search',
            'masterItems'
        ));
    }

    public function stockCreate()
    {
        $date = Carbon::now()->toDateString();

        $masterItems = MasterItem::latest()
            ->where('type', 'stok')
            ->with('masterUnit')
            ->get();

        return view('client.pages.stock-inventory-office.stock.create', compact(
            'date',
            'masterItems'
        ));
    }

    public function stockBarcode()
    {
        return view('client.pages.stock-inventory-office.stock.barcode');
    }

    public function stockStore()
    {
        $request = request()->all();

        if($request['code']){
            foreach ($request['code'] as $index => $codes) {
                foreach ($codes as $index2 => $code) {
                    $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
                        ->whereNull('master_ship_uuid')
                        ->where('master_item_uuid', $request['uuid'][$index][$index2])
                        ->latest()
                        ->first();
    
                    if ($stockHistoryToday) {
                        if ($stockHistoryToday->status != 'masuk') {
                            StockHistory::create([
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
                        $stockHistory = StockHistory::whereNull('master_ship_uuid')
                            ->where('master_item_uuid', $request['uuid'][$index][$index2])
                            ->latest()
                            ->first();
    
                        if ($stockHistory) {
                            if ($stockHistory->status != 'masuk') {
                                StockHistory::create([
                                    'master_item_uuid' => $request['uuid'][$index][$index2],
                                    'status' => 'masuk',
                                    'qty' => 1,
                                    'total' => $stockHistory->total + 1
                                ]);
                            }
    
                            if ($stockHistory->status == 'masuk') {
                                StockHistory::create([
                                    'master_item_uuid' => $request['uuid'][$index][$index2],
                                    'status' => 'masuk',
                                    'qty' => 1,
                                    'total' => $stockHistory->total + 1
                                ]);
                            }
                        } else {
                            StockHistory::create([
                                'master_item_uuid' => $request['uuid'][$index][$index2],
                                'status' => 'masuk',
                                'qty' => 1,
                                'total' => 1
                            ]);
                        }
                    }
    
                    OfficeStock::create([
                        'master_item_uuid' => $request['uuid'][$index][$index2],
                        'code' => $request['code'][$index][$index2],
                        'barcode' => $request['barcode'][$index][$index2],
                    ]);
                }
            }
        }

        return redirect()->route('stock-inventory-office.stock')->with('success', 'Stok kantor berhasil ditambahkan');
    }

    public function stockExport()
    {
        $masterItems = MasterItem::latest()
            ->where('type', 'stok')
            ->get();

        foreach ($masterItems as $masterItem) {
            $stockHistory  = StockHistory::where('master_item_uuid', $masterItem->uuid)
                ->whereNull('master_ship_uuid')
                ->latest()
                ->first();

            $masterItem->stock = $stockHistory ? $stockHistory->total : 0;
        }

        $masterItems = $masterItems->filter(function ($item) {
            return $item->stock > 0;
        });

        return Excel::download(new OfficeStockExport($masterItems), "Laporan Stok Kantor.xlsx");
    }

    public function stockDetail($uuid)
    {
        $search = request('search');

        $masterItem = MasterItem::find($uuid);
        $officeStocks =  OfficeStock::latest()
            ->where('master_item_uuid', $uuid)
            ->paginate(10);
        $officeStocksAll = OfficeStock::latest()
            ->where('master_item_uuid', $uuid)
            ->get();
        $stockHistories = StockHistory::latest()
            ->where('master_item_uuid', $uuid)
            ->paginate(10);

        $officeStocks->appends(request()->query());

        $stockHistories->appends(request()->query());

        $usedItems = UsedItem::whereNull('master_ship_uuid')
        ->where('master_item_uuid', $masterItem->uuid)
        ->paginate(10);


        return view('client.pages.stock-inventory-office.stock.detail', compact(
            'search',
            'masterItem',
            'officeStocks',
            'stockHistories',
            'officeStocksAll',
            'usedItems'
        ));
    }

    public function stockScan($uuid)
    {
        $officeStock = OfficeStock::find($uuid);

        if (!$officeStock) return redirect()->route('stock-inventory-office.stock');

        return view('client.pages.stock-inventory-office.stock.scan', compact(
            'officeStock'
        ));
    }

    public function stockUpdate($uuid)
    {
        $codes  = explode(',', request('codes'));
        $total = count($codes);


        foreach ($codes as $code) {
            OfficeStock::where('code', $code)
                ->delete();
        }

        $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
            ->whereNull('master_ship_uuid')
            ->where('master_item_uuid', $uuid)
            ->whereNull('position')
            ->latest()
            ->first();

        if ($stockHistoryToday) {
            if ($stockHistoryToday->status != 'keluar') {
                StockHistory::create([
                    'master_item_uuid' => $uuid,
                    'status' => 'keluar',
                    'qty' => $total,
                    'total' => $stockHistoryToday->total - $total
                ]);
            }

            if ($stockHistoryToday->status == 'keluar') {
                $stockHistoryToday->qty += $total;
                $stockHistoryToday->total -= $total;
                $stockHistoryToday->save();
            }
        } else {
            $stockHistory = StockHistory::whereNull('master_ship_uuid')
                ->whereNull('master_ship_uuid')
                ->where('master_item_uuid', $uuid)
                ->whereNull('position')
                ->latest()
                ->first();

            if ($stockHistory) {
                if ($stockHistory->status != 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $uuid,
                        'status' => 'keluar',
                        'qty' => $total,
                        'total' => $stockHistory->total  - $total
                    ]);
                }

                if ($stockHistory->status == 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $uuid,
                        'status' => 'keluar',
                        'qty' =>  $total,
                        'total' => $stockHistory->total -  $total
                    ]);
                }
            } else {
                StockHistory::create([
                    'master_item_uuid' => $uuid,
                    'status' => 'keluar',
                    'qty' => $total,
                    'total' => $total
                ]);
            }
        }

        return redirect()->back()->with('success', 'Stok berhasil dipakai');
    }

    public function stockUse($uuid)
    {
        $codes  = explode(',', request('codes'));
        $total = count($codes);

        $usedItem = UsedItem::create([
            'master_item_uuid' => $uuid,
            'title' => request('title'),
            'description' => request('description') 
        ]);

        foreach ($codes as $code) {
            OfficeStock::where('code', $code)
                ->delete();

            UsedItemDetail::create([
                'used_item_uuid' => $usedItem->uuid,
                'code' => $code,
            ]);
        }

        $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
            ->whereNull('master_ship_uuid')
            ->where('master_item_uuid', $uuid)
            ->whereNull('position')
            ->latest()
            ->first();

        if ($stockHistoryToday) {
            if ($stockHistoryToday->status != 'keluar') {
                StockHistory::create([
                    'master_item_uuid' => $uuid,
                    'status' => 'keluar',
                    'qty' => $total,
                    'total' => $stockHistoryToday->total - $total
                ]);
            }

            if ($stockHistoryToday->status == 'keluar') {
                $stockHistoryToday->qty += $total;
                $stockHistoryToday->total -= $total;
                $stockHistoryToday->save();
            }
        } else {
            $stockHistory = StockHistory::whereNull('master_ship_uuid')
                ->whereNull('master_ship_uuid')
                ->where('master_item_uuid', $uuid)
                ->whereNull('position')
                ->latest()
                ->first();

            if ($stockHistory) {
                if ($stockHistory->status != 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $uuid,
                        'status' => 'keluar',
                        'qty' => $total,
                        'total' => $stockHistory->total  - $total
                    ]);
                }

                if ($stockHistory->status == 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $uuid,
                        'status' => 'keluar',
                        'qty' =>  $total,
                        'total' => $stockHistory->total -  $total
                    ]);
                }
            } else {
                StockHistory::create([
                    'master_item_uuid' => $uuid,
                    'status' => 'keluar',
                    'qty' => $total,
                    'total' => $total
                ]);
            }
        }

        return redirect()->back()->with('success', 'Stok berhasil dipakai');
    }

    public function inventory()
    {
        $search = request('search');

        $masterItems = MasterItem::latest()
            ->where('type', 'inventaris')
            ->where('name', 'LIKE', "%$search%")
            ->whereHas('stockHistory', function ($query) {
                $query->whereNull('master_ship_uuid');
            })
            ->paginate(10);

            $masterItems->appends(request()->query());

        return view('client.pages.stock-inventory-office.inventory.index', compact(
            'search',
            'masterItems'
        ));
    }

    public function inventoryCreate()
    {
        $date = Carbon::now()->toDateString();

        $masterItems = MasterItem::latest()
            ->where('type', 'inventaris')
            ->with('masterUnit')
            ->get();

        return view('client.pages.stock-inventory-office.inventory.create', compact(
            'date',
            'masterItems'
        ));
    }

    public function inventoryBarcode()
    {
        return view('client.pages.stock-inventory-office.inventory.barcode');
    }

    public function inventoryStore()
    {
        $request = request()->all();

        foreach ($request['code'] as $index => $codes) {
            foreach ($codes as $index2 => $code) {
                $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
                    ->whereNull('master_ship_uuid')
                    ->where('master_item_uuid', $request['uuid'][$index][$index2])
                    ->latest()
                    ->first();

                if ($stockHistoryToday) {
                    if ($stockHistoryToday->status != 'masuk') {
                        StockHistory::create([
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
                    $stockHistory = StockHistory::whereNull('master_ship_uuid')
                        ->where('master_item_uuid', $request['uuid'][$index][$index2])
                        ->latest()
                        ->first();

                    if ($stockHistory) {
                        if ($stockHistory->status != 'masuk') {
                            StockHistory::create([
                                'master_item_uuid' => $request['uuid'][$index][$index2],
                                'status' => 'masuk',
                                'qty' => 1,
                                'total' => $stockHistory->total + 1
                            ]);
                        }

                        if ($stockHistory->status == 'masuk') {
                            StockHistory::create([
                                'master_item_uuid' => $request['uuid'][$index][$index2],
                                'status' => 'masuk',
                                'qty' => 1,
                                'total' => $stockHistory->total + 1
                            ]);
                        }
                    } else {
                        StockHistory::create([
                            'master_item_uuid' => $request['uuid'][$index][$index2],
                            'status' => 'masuk',
                            'qty' => 1,
                            'total' => 1
                        ]);
                    }
                }

                OfficeStock::create([
                    'master_item_uuid' => $request['uuid'][$index][$index2],
                    'code' => $request['code'][$index][$index2],
                    'barcode' => $request['barcode'][$index][$index2],
                    'expired_at' => $request['expired_at'][$index]
                ]);
            }
        }

        return redirect()->route('stock-inventory-office.inventory')->with('success', 'Inventaris kantor berhasil ditambahkan');
    }

    public function inventoryExport()
    {
        $masterItems = MasterItem::latest()
            ->where('type', 'inventaris')
            ->get();

        foreach ($masterItems as $masterItem) {
            $stockHistory  = StockHistory::where('master_item_uuid', $masterItem->uuid)
                ->whereNull('master_ship_uuid')
                ->latest()
                ->first();

            $masterItem->stock = $stockHistory ? $stockHistory->total : 0;
        }

        $masterItems = $masterItems->filter(function ($item) {
            return $item->stock > 0;
        });

        return Excel::download(new OfficeInventoryExport($masterItems), "Laporan Inventaris Kantor.xlsx");
    }

    public function inventoryDetail($uuid)
    {
        $search = request('search');

        $masterItem = MasterItem::find($uuid);

        $officeStocksAll = OfficeStock::latest()
            ->where('master_item_uuid', $uuid)
            ->get();

        $officeStocks =  OfficeStock::latest()
            ->where('master_item_uuid', $uuid)
            ->where('used', 0)
            ->paginate(10);

        $stockHistories = StockHistory::where('master_item_uuid', $uuid)
            ->whereNull('master_ship_uuid')
            ->paginate(10);

            $officeStocks->appends(request()->query());

            $stockHistories->appends(request()->query());

        $usedItems = UsedItem::whereNull('master_ship_uuid')
            ->where('master_item_uuid', $masterItem->uuid)
            ->paginate(10);


        return view('client.pages.stock-inventory-office.inventory.detail', compact(
            'search',
            'masterItem',
            'officeStocksAll',  
            'officeStocks',
            'stockHistories',
            'usedItems'
        ));
    }

    public function inventoryScan($uuid)
    {
        $officeStock = OfficeStock::find($uuid);

        if (!$officeStock) return redirect()->route('stock-inventory-office.inventory');

        return view('client.pages.stock-inventory-office.inventory.scan', compact(
            'officeStock'
        ));
    }

    public function inventoryUpdate($uuid)
    {
        $codes  = explode(',', request('codes'));
        $total = count($codes);

        foreach ($codes as $code) {
            OfficeStock::where('code', $code)
                ->delete();
        }

        $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
            ->whereNull('master_ship_uuid')
            ->where('master_item_uuid', $uuid)
            ->whereNull('position')
            ->latest()
            ->first();

        if ($stockHistoryToday) {
            if ($stockHistoryToday->status != 'keluar') {
                StockHistory::create([
                    'master_item_uuid' => $uuid,
                    'status' => 'keluar',
                    'qty' => $total,
                    'total' => $stockHistoryToday->total - $total
                ]);
            }

            if ($stockHistoryToday->status == 'keluar') {
                $stockHistoryToday->qty += $total;
                $stockHistoryToday->total -= $total;
                $stockHistoryToday->save();
            }
        } else {
            $stockHistory = StockHistory::whereNull('master_ship_uuid')
                ->whereNull('master_ship_uuid')
                ->where('master_item_uuid', $uuid)
                ->whereNull('position')
                ->latest()
                ->first();

            if ($stockHistory) {
                if ($stockHistory->status != 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $uuid,
                        'status' => 'keluar',
                        'qty' => $total,
                        'total' => $stockHistory->total  - $total
                    ]);
                }

                if ($stockHistory->status == 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $uuid,
                        'status' => 'keluar',
                        'qty' =>  $total,
                        'total' => $stockHistory->total -  $total
                    ]);
                }
            } else {
                StockHistory::create([
                    'master_item_uuid' => $uuid,
                    'status' => 'keluar',
                    'qty' => $total,
                    'total' => $total
                ]);
            }
        }

        return redirect()->back()->with('success', 'Inventaris berhasil dihapus');
    }

    public function inventoryUpdateExpiredAt($uuid)
    {
        OfficeStock::where('uuid', $uuid)
            ->update([
                'expired_at' => request('expired_at')
            ]);

        return redirect()->back()->with('success', 'Inventaris berhasil diperbarui');
    }

    public function InventoryUse($uuid)
    {
        $codes  = explode(',', request('codes'));
        $total = count($codes);

        $usedItem = UsedItem::create([
            'master_item_uuid' => $uuid,
            'title' => request('title'),
            'description' => request('description') 
        ]);

        foreach ($codes as $code) {
            OfficeStock::where('code', $code)
                ->delete();

            UsedItemDetail::create([
                'used_item_uuid' => $usedItem->uuid,
                'code' => $code,
            ]);
        }

        $stockHistoryToday = StockHistory::whereDate('created_at', Carbon::today())
            ->whereNull('master_ship_uuid')
            ->where('master_item_uuid', $uuid)
            ->whereNull('position')
            ->latest()
            ->first();

        if ($stockHistoryToday) {
            if ($stockHistoryToday->status != 'keluar') {
                StockHistory::create([
                    'master_item_uuid' => $uuid,
                    'status' => 'keluar',
                    'qty' => $total,
                    'total' => $stockHistoryToday->total - $total
                ]);
            }

            if ($stockHistoryToday->status == 'keluar') {
                $stockHistoryToday->qty += $total;
                $stockHistoryToday->total -= $total;
                $stockHistoryToday->save();
            }
        } else {
            $stockHistory = StockHistory::whereNull('master_ship_uuid')
                ->whereNull('master_ship_uuid')
                ->where('master_item_uuid', $uuid)
                ->whereNull('position')
                ->latest()
                ->first();

            if ($stockHistory) {
                if ($stockHistory->status != 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $uuid,
                        'status' => 'keluar',
                        'qty' => $total,
                        'total' => $stockHistory->total  - $total
                    ]);
                }

                if ($stockHistory->status == 'keluar') {
                    StockHistory::create([
                        'master_item_uuid' => $uuid,
                        'status' => 'keluar',
                        'qty' =>  $total,
                        'total' => $stockHistory->total -  $total
                    ]);
                }
            } else {
                StockHistory::create([
                    'master_item_uuid' => $uuid,
                    'status' => 'keluar',
                    'qty' => $total,
                    'total' => $total
                ]);
            }
        }

        return redirect()->back()->with('success', 'Inventaris berhasil dipakai');
    }


    public function requestItem()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);


        $requestItemsNew = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('status', 'baru')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);
        $requestItemsConfirmed = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('status', 'terkonfirmasi')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);
        $requestItemsReady = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('status', 'siap dikirim')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);
        $requestItemsDelivered = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);
        $requestItemsRejected = RequestItem::latest()
            ->where('category', 'permintaan')
            ->where('status', 'ditolak')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);

            $requestItemsNew->appends(request()->query());


            $requestItemsConfirmed->appends(request()->query());


            $requestItemsReady->appends(request()->query());


            $requestItemsDelivered->appends(request()->query());


            $requestItemsRejected->appends(request()->query());


        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view('client.pages.stock-inventory-office.request-item.index', compact(
            'startDate',
            'endDate',
            'requestItemsNew',
            'requestItemsConfirmed',
            'requestItemsReady',
            'requestItemsDelivered',
            'requestItemsRejected'
        ));
    }

    public function requestItemDetail($uuid)
    {
        $requestItem = RequestItem::with('requestItemDetails.masterItem.masterUnit')
            ->find($uuid);

        if (!$requestItem) return redirect()->route('stock-inventory-office.request-item');

        return view('client.pages.stock-inventory-office.request-item.detail', compact(
            'requestItem'
        ));
    }

    public function requestItemCreate()
    {
        $type = request('type') ?? 'stok';

        $date = Carbon::now()->toDateString();
        $masterShips = MasterShip::latest()
            ->get();
        $masterItems = MasterItem::latest()
            ->where('type', $type)
            ->with('masterUnit')
            ->get();

        return view('client.pages.stock-inventory-office.request-item.create', compact(
            'date',
            'masterShips',
            'masterItems',
            'type'
        ));
    }

    public function requestItemScan($uuid)
    {
        $requestItem = RequestItem::find($uuid);

        return view(
            'client.pages.stock-inventory-office.request-item.scan',
            compact(
                'uuid',
                'requestItem'
            )
        );
    }

    public function requestItemStore()
    {
        $request = request()->except('_token');

        $requestItem = RequestItem::create([
            'master_user_uuid' => Auth::user()->uuid,
            'type' => $request['type_select'],
            'master_ship_uuid' => $request['master_ship_uuid'],
            'number' => 'R-' . strtoupper(substr(Str::uuid(), 0, 5)),
            'status' => 'terkonfirmasi'
        ]);

        foreach ($request['master_item'] as $key => $item) {
            $qty = intval($request['qty'][$key]);

            RequestItemDetail::create([
                'request_item_uuid' => $requestItem->uuid,
                'master_item_uuid' => $item,
                'qty' => $qty,
                'position' => $request['position'][$key],
            ]);
        }

        return redirect()->route('stock-inventory-office.request-item')->with('success', 'Pengiriman baru berhasil dibuat');
    }

    public function requestItemConfirm()
    {
        $request = request()->all();

        $status = 'ditolak';

        foreach ($request['qty'] as $index => $item) {
            $requestItemDetail = RequestItemDetail::find($request['request-item-detail-uuid'][$index]);

            if($requestItemDetail){
                if ($request['reject'][$index] == 1) {
                    $requestItemDetail->reject = 1;
                } else {
                    $status = 'terkonfirmasi';
                }
    
                $requestItemDetail->qty = $item;
                $requestItemDetail->reject_description = $request['reject_description'][$index];
    
                $requestItemDetail->save();
            }
        }

        RequestItem::where('uuid', $request['uuid'])
            ->update([
                'status' => $status
            ]);

        return redirect()->route('stock-inventory-office')->with('success', 'Konfirmasi permintaan barang berhasil');
    }

    public function requestItemSend()
    {
        $request = request()->all();

        RequestItem::where('uuid', $request['uuid'])
            ->update([
                'status' => 'siap dikirim'
            ]);

        if (isset($request['code'])) {
            foreach ($request['code'] as $code) {
                $officeStock = OfficeStock::where('code', $code)
                    ->first();

                $officeStock->scanned = true;
                $officeStock->save();

                $masterItem = $officeStock->masterItem;

                $requestItemDetail = RequestItemDetail::where('request_item_uuid', $request['uuid'])
                    ->where('master_item_uuid', $masterItem->uuid)
                    ->first();

                RequestItemDetailStock::create([
                    'request_item_detail_uuid' => $requestItemDetail->uuid,
                    'code' => $code,
                    'barcode' => $officeStock->barcode,
                ]);
            }
        }

        RoadLetter::create([
            'request_item_uuid' => $request['uuid'],
            'number' => $request['number'],
            'barcode' => $request['barcode'],
        ]);

        return redirect()->route('stock-inventory-office')->with('success', 'Permintaan barang siap dikirim');
    }

    public function requestItemMasterItem()
    {
        $request = request()->all();

        $requestItemDetail = RequestItemDetail::find($request['uuid']);

        $explode = explode('~', $requestItemDetail->master_item_uuid);
        $name = $explode[1];
        $unit = $explode[2];
        $merk = $explode[3];

        $masterUnit  = MasterUnit::whereRaw('LOWER(name) = ?', [strtolower($unit)])
            ->first();

        if (!$masterUnit) {
            $masterUnit = MasterUnit::create([
                'name' => $unit,
            ]);
        }

        $masterItem  = MasterItem::whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->whereRaw('LOWER(name) = ?', [strtolower($merk)])
            ->first();

        if (!$masterItem) {
            $masterItem = MasterItem::create([
                'master_unit_uuid' => $masterUnit->uuid,
                'slug' => Str::slug($name),
                'name' => $name,
                'merk' => $merk,
                'type' => $requestItemDetail->requestItem->type,
            ]);
        }

        $requestItemDetail->master_item_uuid = $masterItem->uuid;

        $requestItemDetail->save();

        return redirect()->back()->with('success', 'Master item berhasil ditambahkan');
    }
}
