<?php

namespace App\Http\Controllers\API\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\RequestItemDetailStock;

class StockInventoryShipController extends Controller
{
    public function checkItemCode()
    {
        $code = request('code');

        $result = [
            'status' => false,
            'message' => 'Terjadi kesalahan'
        ];

        $item = RequestItemDetailStock::where('code', $code)->first();

        if (!$item) {
            $result['message'] = ['Barang tidak ditemukan'];

            return response()->json($result);
        }

        if ($item->used) {
            $result['message'] = ['Barang sudah dipakai'];

            return response()->json($result);
        }

        if ($item->requestItemDetail->requestItem->category == 'permintaan') {
            if ($item->requestItemDetail->requestItem->status != 'selesai') {
                $result['message'] = ['Barang belum dikirim'];

                return response()->json($result);
            }
        }

        $masterItem = $item->requestItemDetail->masterItem;

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

    public function checkOilCode()
    {
        $code = request('code');

        $result = [
            'status' => false,
            'message' => 'Terjadi kesalahan'
        ];

        $item = RequestItemDetailStock::where('code', $code)->first();

        if (!strpos($item->requestItemDetail->masterItem->slug, 'oli')) {
            $result['message'] = ['Bukan oli'];

            return response()->json($result);
        }

        if (!$item) {
            $result['message'] = ['Oli tidak ditemukan'];

            return response()->json($result);
        }

        if ($item->used) {
            $result['message'] = ['Oli sudah dipakai'];

            return response()->json($result);
        }

        if ($item->requestItemDetail->requestItem->category == 'permintaan') {
            if ($item->requestItemDetail->requestItem->status != 'selesai') {
                $result['message'] = ['Oli belum dikirim'];

                return response()->json($result);
            }
        }

        $masterItem = $item->requestItemDetail->masterItem;

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

    public function checkInventoryCode()
    {
        $code = request('code');

        $result = [
            'status' => false,
            'message' => 'Terjadi kesalahan'
        ];

        $item = RequestItemDetailStock::where('code', $code)->first();

        if ($item->requestItemDetail->masterItem->type != 'inventaris') {
            $result['message'] = ['Bukan inventaris'];

            return response()->json($result);
        }

        if (!$item) {
            $result['message'] = ['Inventaris tidak ditemukan'];

            return response()->json($result);
        }

        if ($item->used) {
            $result['message'] = ['Inventaris sudah diturunkan'];

            return response()->json($result);
        }

        if ($item->requestItemDetail->requestItem->category == 'permintaan') {
            if ($item->requestItemDetail->requestItem->status != 'selesai') {
                $result['message'] = ['Inventaris belum dikirim'];

                return response()->json($result);
            }
        }

        $masterItem = $item->requestItemDetail->masterItem;

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
