<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MasterItem;
use App\Models\MasterUnit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MasterItemController extends Controller
{
    public function index()
    {
        $search = request('search');

        $masterUnits = MasterUnit::latest()
            ->get();

        $masterItems = MasterItem::orderBy('name', 'ASC')
            ->where('name', 'LIKE', "%$search%")
            ->paginate(10);

            $masterItems->appends(request()->query());

        return view('backoffice.pages.master.item.index', compact(
            'search',
            'masterUnits',
            'masterItems'
        ));
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'master_unit_uuid' => 'required',
            'name' => 'required',
            'type' => 'required'
        ], [
            'master_unit_uuid.required' => 'Satuan harus diisi',
            'name.required' => 'Nama harus diisi',
            'type.required' => 'Tipe harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Barang baru gagal dibuat');
        }

        $request = request()->all();
        $request['slug'] = Str::slug(request('name'));

        MasterItem::create($request);

        return redirect()->back()->with('success', 'Barang baru berhasil dibuat');
    }

    public function update($uuid)
    {
        $item = MasterItem::where('uuid', $uuid)->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        $validator = Validator::make(request()->all(), [
            'master_unit_uuid' => 'required',
            'name' => 'required',
            'type' => 'required'
        ], [
            'master_unit_uuid.required' => 'Satuan harus diisi',
            'name.required' => 'Nama harus diisi',
            'type.required' => 'Tipe harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Barang gagal diubah');
        }

        $request = request()->except('_method', '_token');
        $request['slug'] = Str::slug(request('name'));

        $item->update($request);

        return redirect()->back()->with('success', 'Barang berhasil diubah');
    }

    public function delete($uuid)
    {
        $item = MasterItem::where('uuid', $uuid)->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Barang gagal dihapus');
        }

        $item->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus');
    }
}
