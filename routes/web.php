<?php

use App\Http\Controllers\Backoffice\AbsentReportController;
use App\Http\Controllers\Backoffice\AnnouncementController as BackofficeAnnouncementController;
use App\Http\Controllers\Backoffice\CrashReportController;
use App\Http\Controllers\Backoffice\DockingReportController;
use App\Http\Controllers\Backoffice\BBMReportController;
use App\Http\Controllers\Backoffice\InspectionReportController;
use App\Http\Controllers\Backoffice\OilReportController;
use App\Http\Controllers\Backoffice\InventoryReportController;
use App\Http\Controllers\Backoffice\LeaveApprovalController;
use App\Http\Controllers\Backoffice\LeaveReportController;
use App\Http\Controllers\Backoffice\MasterUserController;
use App\Http\Controllers\Backoffice\MasterDivisonController;
use App\Http\Controllers\Backoffice\MasterShipController;
use App\Http\Controllers\Backoffice\MasterMeachineController;
use App\Http\Controllers\Backoffice\MasterUnitController;
use App\Http\Controllers\Backoffice\MasterItemController;
use App\Http\Controllers\Backoffice\MasterHolidayController;
use App\Http\Controllers\Backoffice\RequestItemReportController;
use App\Http\Controllers\Backoffice\SalaryFluctuationReportController;
use App\Http\Controllers\Backoffice\SettingAbsentController;
use App\Http\Controllers\Backoffice\SettingLeaveController;
use App\Http\Controllers\Backoffice\SettingOfficeController;
use App\Http\Controllers\Client\StockInventoryShipController;
use App\Http\Controllers\Backoffice\StockReportController;
use App\Http\Controllers\Backoffice\TopRangkingReportController;
use App\Http\Controllers\Client\AbsentController;
use App\Http\Controllers\Client\AnnouncementController;
use App\Http\Controllers\Client\BBMController;
use App\Http\Controllers\Client\InspectionController;
use App\Http\Controllers\Client\NotificationController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\LeaveController;
use App\Http\Controllers\Client\OilChangeController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\RoadLetterController;
use App\Http\Controllers\Client\StockInventoryOfficeController;
use App\Http\Controllers\FCMController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

ini_set('max_execution_time', 100000000000000); // 120 seconds


Auth::routes();

Route::get('/no-internet', function () {
    return view('no-internet');
})->name('no-internet');

Route::prefix('fcm')->group(function () {
    Route::patch('/fcm-token', [FCMController::class, 'updateToken'])->name('fcmToken');
});


Route::middleware('auth')->group(function () {
    Route::get('', [HomeController::class, 'index'])->name('home');

    Route::get('notification', [NotificationController::class, 'index'])->name('notification');

    Route::prefix('announcement')->group(function () {
        Route::get('', [AnnouncementController::class, 'index'])->name('announcement.index');
        Route::get('{uuid}', [AnnouncementController::class, 'detail'])->name('announcement.detail');
    });

    Route::prefix('profile')->group(function () {
        Route::get('', [ProfileController::class, 'index'])->name('profile');
        Route::get('photo', [ProfileController::class, 'photo'])->name('profile.photo');
        Route::post('upload', [ProfileController::class, 'upload'])->name('profile.upload');
    });

    Route::prefix('absent')->group(function () {
        Route::get('', [AbsentController::class, 'index'])->name('absent');
        Route::get('history', [AbsentController::class, 'history'])->name('absent.history');
        Route::get('checkinout', [AbsentController::class, 'checkinout'])->name('absent.checkinout');
        Route::get('recognition', [AbsentController::class, 'recognition'])->name('absent.recognition');
        Route::get('upload', [AbsentController::class, 'upload'])->name('absent.upload');
        Route::post('upload-process', [AbsentController::class, 'uploadProcess'])->name('absent.upload.process');
        Route::post('process', [AbsentController::class, 'process'])->name('absent.process');
    });

    Route::prefix('leave')->group(function () {
        Route::get('', [LeaveController::class, 'index'])->name('leave');
        Route::get('list', [LeaveController::class, 'list'])->name('leave.list');
        Route::get('detail/{uuid}', [LeaveController::class, 'detail'])->name('leave.detail');
        Route::post('', [LeaveController::class, 'store'])->name('leave.store');
        Route::post('accept/{uuid}', [LeaveController::class, 'accept'])->name('leave.accept');
        Route::post('reject/{uuid}', [LeaveController::class, 'reject'])->name('leave.reject');
    });

    Route::prefix('stock-inventory-office')->group(function () {
        Route::get('', [StockInventoryOfficeController::class, 'index'])->name('stock-inventory-office');
        Route::prefix('stock')->group(function () {
            Route::get('', [StockInventoryOfficeController::class, 'stock'])->name('stock-inventory-office.stock');
            Route::get('create', [StockInventoryOfficeController::class, 'stockCreate'])->name('stock-inventory-office.stock.create');
            Route::get('barcode', [StockInventoryOfficeController::class, 'stockBarcode'])->name('stock-inventory-office.stock.barcode');
            Route::post('', [StockInventoryOfficeController::class, 'stockStore'])->name('stock-inventory-office.stock.store');
            Route::get('export', [StockInventoryOfficeController::class, 'stockExport'])->name('stock-inventory-office.stock.export');
            Route::get('detail/{uuid}', [StockInventoryOfficeController::class, 'stockDetail'])->name('stock-inventory-office.stock.detail');
            Route::get('scan/{uuid}', [StockInventoryOfficeController::class, 'stockScan'])->name('stock-inventory-office.stock.scan');
            Route::post('update/{uuid}', [StockInventoryOfficeController::class, 'stockUpdate'])->name('stock-inventory-office.stock.update');
            Route::post('use/{uuid}', [StockInventoryOfficeController::class, 'stockUse'])->name('stock-inventory-office.stock.use');
        });
        Route::prefix('inventory')->group(function () {
            Route::get('', [StockInventoryOfficeController::class, 'inventory'])->name('stock-inventory-office.inventory');
            Route::get('create', [StockInventoryOfficeController::class, 'inventoryCreate'])->name('stock-inventory-office.inventory.create');
            Route::get('barcode', [StockInventoryOfficeController::class, 'inventoryBarcode'])->name('stock-inventory-office.inventory.barcode');
            Route::post('', [StockInventoryOfficeController::class, 'inventoryStore'])->name('stock-inventory-office.inventory.store');
            Route::get('export', [StockInventoryOfficeController::class, 'inventoryExport'])->name('stock-inventory-office.inventory.export');
            Route::get('detail/{uuid}', [StockInventoryOfficeController::class, 'inventoryDetail'])->name('stock-inventory-office.inventory.detail');
            Route::get('scan/{uuid}', [StockInventoryOfficeController::class, 'inventoryScan'])->name('stock-inventory-office.inventory.scan');
            Route::post('update/{uuid}', [StockInventoryOfficeController::class, 'inventoryUpdate'])->name('stock-inventory-office.inventory.update');
            Route::put('update/expired-at/{uuid}', [StockInventoryOfficeController::class, 'inventoryUpdateExpiredAt'])->name('stock-inventory-office.inventory.update.expired_at');
            Route::post('use/{uuid}', [StockInventoryOfficeController::class, 'inventoryUse'])->name('stock-inventory-office.inventory.use');
        });
        Route::prefix('request-item')->group(function () {
            Route::get('', [StockInventoryOfficeController::class, 'requestItem'])->name('stock-inventory-office.request-item');
            Route::get('detail/{uuid}', [StockInventoryOfficeController::class, 'requestItemDetail'])->name('stock-inventory-office.request-item.detail');
            Route::get('create', [StockInventoryOfficeController::class, 'requestItemCreate'])->name('stock-inventory-office.request-item.create');
            Route::get('scan/{uuid}', [StockInventoryOfficeController::class, 'requestItemScan'])->name('stock-inventory-office.request-item.scan');
            Route::post('', [StockInventoryOfficeController::class, 'requestItemStore'])->name('stock-inventory-office.request-item.store');
            Route::post('confirm', [StockInventoryOfficeController::class, 'requestItemConfirm'])->name('stock-inventory-office.request-item.confirm');
            Route::post('send', [StockInventoryOfficeController::class, 'requestItemSend'])->name('stock-inventory-office.request-item.send');
            Route::post('master-item', [StockInventoryOfficeController::class, 'requestItemMasterItem'])->name('stock-inventory-office.request-item.master-item');
        });
    });

    Route::prefix('stock-inventory-ship')->group(function () {
        Route::get('', [StockInventoryShipController::class, 'index'])->name('stock-inventory-ship');
        Route::get('detail/{uuid}', [StockInventoryShipController::class, 'detail'])->name('stock-inventory-ship.detail');
        Route::prefix('stock-opname/{uuid}')->group(function () {
            Route::get('', [StockInventoryShipController::class, 'stockOpname'])->name('stock-inventory-ship.stock-opname');
            Route::get('export-meachine', [StockInventoryShipController::class, 'stockOpnameExportMeachine'])->name('stock-inventory-ship.stock-opname.export-meachine');
            Route::get('export-deck', [StockInventoryShipController::class, 'stockOpnameExportDeck'])->name('stock-inventory-ship.stock-opname.export-deck');
        });
        Route::prefix('ship-inventory/{uuid}')->group(function () {
            Route::get('', [StockInventoryShipController::class, 'shipInventory'])->name('stock-inventory-ship.ship-inventory');
            Route::get('export-meachine', [StockInventoryShipController::class, 'shipInventoryExportMeachine'])->name('stock-inventory-ship.ship-inventory.export-meachine');
            Route::get('export-deck', [StockInventoryShipController::class, 'shipInventoryExportDeck'])->name('stock-inventory-ship.ship-inventory.export-deck');
        });
        Route::prefix('drop-item/{uuid}')->group(function () {
            Route::get('', [StockInventoryShipController::class, 'dropItem'])->name('stock-inventory-ship.drop-item');
            Route::get('detail/{dropItemUuid}', [StockInventoryShipController::class, 'dropItemDetail'])->name('stock-inventory-ship.drop-item.detail');
            Route::get('create', [StockInventoryShipController::class, 'dropItemCreate'])->name('stock-inventory-ship.drop-item.create');
            Route::post('store', [StockInventoryShipController::class, 'dropItemStore'])->name('stock-inventory-ship.drop-item.store');
        });
        Route::prefix('request-item/{uuid}/{type}')->group(function () {
            Route::get('', [StockInventoryShipController::class, 'requestItem'])->name('stock-inventory-ship.request-item');
            Route::get('create', [StockInventoryShipController::class, 'requestItemCreate'])->name('stock-inventory-ship.request-item.create');
            Route::post('', [StockInventoryShipController::class, 'requestItemStore'])->name('stock-inventory-ship.request-item.store');
            Route::get('detail', [StockInventoryShipController::class, 'requestItemDetail'])->name('stock-inventory-ship.request-item.detail');
            Route::get('check', [StockInventoryShipController::class, 'requestItemCheck'])->name('stock-inventory-office.request-item.check');
            Route::put('update', [StockInventoryShipController::class, 'requestItemUpdate'])->name('stock-inventory-office.request-item.update');
        });
        Route::prefix('used-item/{uuid}')->group(function () {
            Route::get('', [StockInventoryShipController::class, 'usedItem'])->name('stock-inventory-ship.used-item');
            Route::get('create', [StockInventoryShipController::class, 'usedItemCreate'])->name('stock-inventory-ship.used-item.create');
            Route::post('', [StockInventoryShipController::class, 'usedItemStore'])->name('stock-inventory-ship.used-item.store');
            Route::get('detail', [StockInventoryShipController::class, 'usedItemDetail'])->name('stock-inventory-ship.used-item.detail');
        });
        Route::prefix('crash-report/{uuid}')->group(function () {
            Route::get('', [StockInventoryShipController::class, 'crashReport'])->name('stock-inventory-ship.crash-report');
            Route::get('create', [StockInventoryShipController::class, 'crashReportCreate'])->name('stock-inventory-ship.crash-report.create');
            Route::post('', [StockInventoryShipController::class, 'crashReportStore'])->name('stock-inventory-ship.crash-report.store');
            Route::get('detail/{crashReportUuid}', [StockInventoryShipController::class, 'crashReportDetail'])->name('stock-inventory-ship.crash-report.detail');
            Route::put('update', [StockInventoryShipController::class, 'crashReportupdate'])->name('stock-inventory-ship.crash-report.update');
            Route::put('done', [StockInventoryShipController::class, 'crashReportDone'])->name('stock-inventory-ship.crash-report.done');
        });
        Route::prefix('docking/{uuid}')->group(function () {
            Route::get('', [StockInventoryShipController::class, 'docking'])->name('stock-inventory-ship.docking');
            Route::get('create', [StockInventoryShipController::class, 'dockingCreate'])->name('stock-inventory-ship.docking.create');
            Route::post('', [StockInventoryShipController::class, 'dockingStore'])->name('stock-inventory-ship.docking.store');
            Route::get('detail/{dockingUuid}', [StockInventoryShipController::class, 'dockingDetail'])->name('stock-inventory-ship.docking.detail');
        });
        Route::prefix('stock')->group(function () {
            Route::get('', [StockInventoryShipController::class, 'stock'])->name('stock-inventory-ship.stock');
            Route::get('create', [StockInventoryShipController::class, 'stockCreate'])->name('stock-inventory-ship.stock.create');
            Route::get('barcode', [StockInventoryShipController::class, 'stockBarcode'])->name('stock-inventory-ship.stock.barcode');
            Route::post('', [StockInventoryShipController::class, 'stockStore'])->name('stock-inventory-ship.stock.store');
        });
        Route::prefix('inventory')->group(function () {
            Route::get('', [StockInventoryShipController::class, 'inventory'])->name('stock-inventory-ship.inventory');
            Route::get('create', [StockInventoryShipController::class, 'inventoryCreate'])->name('stock-inventory-ship.inventory.create');
            Route::get('barcode', [StockInventoryShipController::class, 'inventoryBarcode'])->name('stock-inventory-ship.inventory.barcode');
            Route::post('', [StockInventoryShipController::class, 'inventoryStore'])->name('stock-inventory-ship.inventory.store');
        });
    });

    Route::prefix('oil-change')->group(function () {
        Route::get('', [OilChangeController::class, 'index'])->name('oil-change');
        Route::get('create', [OilChangeController::class, 'create'])->name('oil-change.create');
        Route::get('detail/{uuid}', [OilChangeController::class, 'detail'])->name('oil-change.detail');
        Route::post('', [OilChangeController::class, 'store'])->name('oil-change.store');
    });

    Route::prefix('bbm')->group(function () {
        Route::get('', [BBMController::class, 'index'])->name('bbm');
        Route::get('create', [BBMController::class, 'create'])->name('bbm.create');
        Route::get('detail/{uuid}', [BBMController::class, 'detail'])->name('bbm.detail');
        Route::post('', [BBMController::class, 'store'])->name('bbm.store');
    });

    
    Route::prefix('inspection')->group(function () {
        Route::get('', [InspectionController::class, 'index'])->name('inspection');
        Route::get('create', [InspectionController::class, 'create'])->name('inspection.create');
        Route::post('store', [InspectionController::class, 'store'])->name('inspection.store');
    });

    Route::prefix('road-letter')->group(function () {
        Route::get('', [RoadLetterController::class, 'index'])->name('road-letter');
        Route::get('detail/{uuid}', [RoadLetterController::class, 'detail'])->name('road-letter.detail');
        Route::put('update/{uuid}', [RoadLetterController::class, 'update'])->name('road-letter.update');
        Route::get('pdf/{uuid}', [RoadLetterController::class, 'pdf'])->name('road-letter.pdf');
    });

    Route::prefix('backoffice')->group(function () {
        Route::prefix('master-user')->group(function () {
            Route::get('', [MasterUserController::class, 'index'])->name('master-user');
            Route::post('', [MasterUserController::class, 'store'])->name('master-user.store');
            Route::put('{uuid}', [MasterUserController::class, 'update'])->name('master-user.update');
            Route::delete('{uuid}', [MasterUserController::class, 'delete'])->name('master-user.delete');
            Route::get('export', [MasterUserController::class, 'export'])->name('master-user.export');
            Route::get('pdf', [MasterUserController::class, 'pdf'])->name('master-user.pdf');
        });

        Route::prefix('master-division')->group(function () {
            Route::get('', [MasterDivisonController::class, 'index'])->name('master-division');
            Route::post('', [MasterDivisonController::class, 'store'])->name('master-division.store');
            Route::put('{uuid}', [MasterDivisonController::class, 'update'])->name('master-division.update');
            Route::delete('{uuid}', [MasterDivisonController::class, 'delete'])->name('master-division.delete');
        });

        Route::prefix('master-ship')->group(function () {
            Route::get('', [MasterShipController::class, 'index'])->name('master-ship');
            Route::post('', [MasterShipController::class, 'store'])->name('master-ship.store');
            Route::put('{uuid}', [MasterShipController::class, 'update'])->name('master-ship.update');
            Route::delete('{uuid}', [MasterShipController::class, 'delete'])->name('master-ship.delete');
        });

        Route::prefix('master-meachine')->group(function () {
            Route::get('', [MasterMeachineController::class, 'index'])->name('master-meachine');
            Route::post('', [MasterMeachineController::class, 'store'])->name('master-meachine.store');
            Route::put('{uuid}', [MasterMeachineController::class, 'update'])->name('master-meachine.update');
            Route::delete('{uuid}', [MasterMeachineController::class, 'delete'])->name('master-meachine.delete');
        });


        Route::prefix('master-unit')->group(function () {
            Route::get('', [MasterUnitController::class, 'index'])->name('master-unit');
            Route::post('', [MasterUnitController::class, 'store'])->name('master-unit.store');
            Route::put('{uuid}', [MasterUnitController::class, 'update'])->name('master-unit.update');
            Route::delete('{uuid}', [MasterUnitController::class, 'delete'])->name('master-unit.delete');
        });

        Route::prefix('master-item')->group(function () {
            Route::get('', [MasterItemController::class, 'index'])->name('master-item');
            Route::post('', [MasterItemController::class, 'store'])->name('master-item.store');
            Route::put('{uuid}', [MasterItemController::class, 'update'])->name('master-item.update');
            Route::delete('{uuid}', [MasterItemController::class, 'delete'])->name('master-item.delete');
        });

        Route::prefix('master-holiday')->group(function () {
            Route::get('', [MasterHolidayController::class, 'index'])->name('master-holiday');
            Route::post('', [MasterHolidayController::class, 'store'])->name('master-holiday.store');
            Route::put('{uuid}', [MasterHolidayController::class, 'update'])->name('master-holiday.update');
            Route::delete('{uuid}', [MasterHolidayController::class, 'delete'])->name('master-holiday.delete');
        });

        Route::prefix('setting-office')->group(function () {
            Route::get('', [SettingOfficeController::class, 'index'])->name('setting-office');
            Route::put('', [SettingOfficeController::class, 'update'])->name('setting-office.update');
        });

        Route::prefix('setting-absent')->group(function () {
            Route::get('', [SettingAbsentController::class, 'index'])->name('setting-absent');
            Route::put('', [SettingAbsentController::class, 'update'])->name('setting-absent.update');
        });

        Route::prefix('setting-leave')->group(function () {
            Route::get('', [SettingLeaveController::class, 'index'])->name('setting-leave');
            Route::put('', [SettingLeaveController::class, 'update'])->name('setting-leave.update');
        });

        Route::prefix('absent-report')->group(function () {
            Route::get('', [AbsentReportController::class, 'index'])->name('absent-report');
            Route::get('pdf', [AbsentReportController::class, 'pdf'])->name('absent-report.pdf');
            Route::get('detail/{uuid}', [AbsentReportController::class, 'detail'])->name('absent-report.detail');
            Route::get('export/{uuid}', [AbsentReportController::class, 'export'])->name('absent-report.export');
            Route::put('update/{uuid}', [AbsentReportController::class, 'update'])->name('absent-report.update');
        });

        Route::get('top-rangking-report', [TopRangkingReportController::class, 'index'])->name('top-rangking-report');

        Route::prefix('leave-report')->group(function () {
            Route::get('', [LeaveReportController::class, 'index'])->name('leave-report');
            Route::get('detail/{uuid}', [LeaveReportController::class, 'detail'])->name('leave-report.detail');
            Route::get('export', [LeaveReportController::class, 'export'])->name('leave-report.export');
            Route::get('export/detail/{uuid}', [LeaveReportController::class, 'exportDetail'])->name('leave-report.export-detail');
            Route::get('pdf', [LeaveReportController::class, 'pdf'])->name('leave-report.pdf');
            Route::get('pdfDetail/{uuid}', [LeaveReportController::class, 'pdfDetail'])->name('leave-report.pdf-detail');
        });

        Route::get('salary-fluctuation-report', [SalaryFluctuationReportController::class, 'index'])->name('salary-fluctuation-report');

        Route::prefix('leave-approval')->group(function () {
            Route::get('', [LeaveApprovalController::class, 'index'])->name('leave-approval');
            Route::post('accept/{uuid}', [LeaveApprovalController::class, 'accept'])->name('leave-approval.accept');
            Route::post('reject/{uuid}', [LeaveApprovalController::class, 'reject'])->name('leave-approval.reject');
        });

        Route::prefix('announcement')->group(function () {
            Route::get('', [BackofficeAnnouncementController::class, 'index'])->name('backoffice.announcement');
            Route::post('', [BackofficeAnnouncementController::class, 'store'])->name('backoffice.announcement.store');
            Route::put('{uuid}', [BackofficeAnnouncementController::class, 'update'])->name('backoffice.announcement.update');
            Route::delete('{uuid}', [BackofficeAnnouncementController::class, 'delete'])->name('backoffice.announcement.delete');
        });

        Route::prefix('stock-report')->group(function () {
            Route::get('', [StockReportController::class, 'index'])->name('stock-report');
            Route::get('detail/{uuid}', [StockReportController::class, 'detail'])->name('stock-report.detail');
        });

        Route::prefix('inventory-report')->group(function () {
            Route::get('', [InventoryReportController::class, 'index'])->name('inventory-report');
            Route::get('detail/{uuid}', [InventoryReportController::class, 'detail'])->name('inventory-report.detail');
        });

        Route::prefix('crash-report')->group(function () {
            Route::get('', [CrashReportController::class, 'index'])->name('crash-report');
            Route::get('detail/{uuid}', [CrashReportController::class, 'detail'])->name('crash-report.detail');
            Route::put('update/{uuid}', [CrashReportController::class, 'update'])->name('crash-report.update');
        });


        Route::prefix('docking-report')->group(function () {
            Route::get('', [DockingReportController::class, 'index'])->name('docking-report');
            Route::get('detail/{uuid}', [DockingReportController::class, 'detail'])->name('docking-report.detail');
        });

        Route::prefix('bbm-report')->group(function () {
            Route::get('', [BBMReportController::class, 'index'])->name('bbm-report');
        });

        Route::prefix('oil-report')->group(function () {
            Route::get('', [OilReportController::class, 'index'])->name('oil-report');
        });

        
        Route::prefix('inspection-report')->group(function () {
            Route::get('', [InspectionReportController::class, 'index'])->name('inspection-report');
            Route::get('detail/{uuid}', [InspectionReportController::class, 'detail'])->name('inspection-report.detail');
        });

        Route::prefix('request-item-report')->group(function () {
            Route::get('', [RequestItemReportController::class, 'index'])->name('request-item-report');
            Route::get('detail/{uuid}', [RequestItemReportController::class, 'detail'])->name('request-item-report.detail');
        });
    });
});

Route::get('test', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
});
