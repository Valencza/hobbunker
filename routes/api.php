<?php

use App\Http\Controllers\API\Backoffice\RoadLetterController;
use App\Http\Controllers\API\Backoffice\StockInventoryOfficeController;
use App\Http\Controllers\API\Backoffice\StockInventoryShipController;
use App\Http\Controllers\FCMController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('stock-inventory-office')->group(function () {
    Route::get('check-item-code', [StockInventoryOfficeController::class, 'checkItemCode'])->name('api.stock-inventory-office.check-item-code');
});

Route::prefix('stock-inventory-ship')->group(function () {
    Route::get('check-item-code', [StockInventoryShipController::class, 'checkItemCode'])->name('api.stock-inventory-ship.check-item-code');
    Route::get('check-oil-code', [StockInventoryShipController::class, 'checkOilCode'])->name('api.stock-inventory-ship.check-oil-code');
    Route::get('check-inventory-code', [StockInventoryShipController::class, 'checkInventoryCode'])->name('api.stock-inventory-ship.check-inventory-code');
});

Route::prefix('road-letter')->group(function () {
    Route::get('check-code', [RoadLetterController::class, 'checkCode'])->name('api.road-letter.check-code');
});

Route::prefix('fcm')->group(function () {
    Route::post('/send-notification', [FCMController::class, 'sendNotification'])->name('sendNotification');
});
