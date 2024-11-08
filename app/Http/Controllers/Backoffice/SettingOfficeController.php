<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\SettingOffice;
use Illuminate\Support\Facades\Validator;

class SettingOfficeController extends Controller
{
    public function index()
    {
        $settingOffice = SettingOffice::first();

        return view('backoffice.pages.setting.office.index', compact(
            'settingOffice'
        ));
    }

    public function update()
    {
        $settingOffice = SettingOffice::first();

        if (!$settingOffice) {
            return redirect()->back()->with('error', 'Pengaturan kantor tidak ditemukan');
        }

        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'shortname' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ], [
            'name.required' => 'Nama harus diisi',
            'shortname.required' => 'Singkatan harus diisi',
            'address.required' => 'Alamat harus diisi',
            'latitude.required' => 'Latitude harus diisi',
            'longitude.required' => 'Longitude harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Pengaturan kantor gagal diubah');
        }

        $settingOffice->update(request()->except('_method', '_token'));

        return redirect()->back()->with('success', 'Pengaturan kantor berhasil diubah');
    }
}
