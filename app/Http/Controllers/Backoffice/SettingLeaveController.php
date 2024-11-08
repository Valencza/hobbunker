<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\SettingLeave;

class SettingLeaveController extends Controller
{
    public function index()
    {
        $settingLeave = SettingLeave::first();

        return view('backoffice.pages.setting.leave.index', compact(
            'settingLeave'
        ));
    }

    public function update()
    {
        $settingLeave = SettingLeave::first();

        if (!$settingLeave) {
            return redirect()->back()->with('error', 'Pengaturan cuti tidak ditemukan');
        }

        $validator = Validator::make(request()->all(), [
            'balance_year' => 'required',
        ], [
            'balance_year.required' => 'Saldo cuti harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Pengaturan cuti gagal diubah');
        }

        $settingLeave->update(request()->except('_method', '_token'));

        return redirect()->back()->with('success', 'Pengaturan cuti berhasil diubah');
    }
}
