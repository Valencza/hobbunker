<?php

namespace App\Http\Controllers\API\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\OfficeStock;
use App\Models\RequestItemDetail;

class StockInventoryOfficeController extends Controller
{
    public function checkItemCode()
    {
        $uuid = request('uuid');
        $code = request('code');

        $result = [
            'status' => false,
            'message' => 'Terjadi kesalahan'
        ];

        $item = OfficeStock::where('code', $code)->first();

        if (!$item) {
            $result['message'] = ['Barang tidak ditemukan'];

            return response()->json($result);
        }

        if ($item->scanned) {
            $result['message'] = ['Barang sudah discan'];

            return response()->json($result);
        }

        $masterItem = $item->masterItem;

        $check = RequestItemDetail::where('request_item_uuid', $uuid)
            ->where('master_item_uuid', $masterItem->uuid)
            ->first();

        if (!$check) {
            $result['message'] = ['Barang tidak ada dalam permintaan'];

            return response()->json($result);
        }

        $result = [
            'status' => true,
            'message' => 'Berhasil',
            'data' => [
                'name' => $masterItem->name,
                'code' => $code
            ]
        ];

        return response()->json($result);
    }
}
