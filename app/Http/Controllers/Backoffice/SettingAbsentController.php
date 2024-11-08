<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\SettingAbsent;

class SettingAbsentController extends Controller
{
    public function index()
    {
        $settingAbsent = SettingAbsent::first();

        return view('backoffice.pages.setting.absent.index', compact(
            'settingAbsent'
        ));
    }

    public function update()
    {
        $settingAbsent = SettingAbsent::first();

        if (!$settingAbsent) {
            return redirect()->back()->with('error', 'Pengaturan absensi tidak ditemukan');
        }

        $validator = Validator::make(request()->all(), [
            'clock_in' => 'required',
            'clock_in_limit' => 'required',
            'clock_out' => 'required',
            'clock_out_limit' => 'required'
        ], [
            'clock_in.required' => 'Jam masuk harus diisi',
            'clock_in_limit.required' => 'Batas jam masuk harus diisi',
            'clock_out.required' => 'Jam pulang harus diisi',
            'clock_out_limit.required' => 'Batas jam pulang harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Pengaturan absensi gagal diubah');
        }

        $settingAbsent->update(request()->except('_method', '_token'));

        return redirect()->back()->with('success', 'Pengaturan absensi berhasil diubah');
    }
}
