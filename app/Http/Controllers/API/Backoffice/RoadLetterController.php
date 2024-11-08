<?php

namespace App\Http\Controllers\API\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\RoadLetter;

class RoadLetterController extends Controller
{
    public function checkCode()
    {
        $uuid = request('uuid');
        $code = request('code');

        $result = [
            'status' => false,
            'message' => 'Terjadi kesalahan'
        ];

        $item = RoadLetter::where('number', $code)
            ->where('request_item_uuid', $uuid)
            ->first();

        if (!$item) {
            $result['status'] = true;
            $result['message'] = ['Surat jalan tidak ditemukan'];

            return response()->json($result);
        }

        if ($item->scanned) {
            $result['status'] = true;
            $result['message'] = ['Surat jalan sudah discan'];

            return response()->json($result);
        }

        $result = [
            'status' => true,
            'message' => 'Berhasil',
            'data' => [
                'code' => $code
            ]
        ];

        return response()->json($result);
    }
}
