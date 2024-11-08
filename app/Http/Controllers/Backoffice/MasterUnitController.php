<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MasterUnit;
use Illuminate\Support\Facades\Validator;

class MasterUnitController extends Controller
{
    public function index()
    {
        $search = request('search');

        $masterUnits = MasterUnit::latest()
            ->where('name', 'LIKE', "%$search%")
            ->paginate(10);


            $masterUnits->appends(request()->query());


        return view('backoffice.pages.master.unit.index', compact(
            'search',
            'masterUnits'
        ));
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Satuan baru gagal dibuat');
        }

        MasterUnit::create(request()->all());

        return redirect()->back()->with('success', 'Satuan baru berhasil dibuat');
    }

    public function update($uuid)
    {
        $unit = MasterUnit::where('uuid', $uuid)->first();

        if (!$unit) {
            return redirect()->back()->with('error', 'Satuan tidak ditemukan');
        }

        $validator = Validator::make(request()->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Satuan gagal diubah');
        }

        $unit->update(request()->except('_method', '_token'));

        return redirect()->back()->with('success', 'Satuan berhasil diubah');
    }

    public function delete($uuid)
    {
        $unit = MasterUnit::where('uuid', $uuid)->first();

        if (!$unit) {
            return redirect()->back()->with('error', 'Satuan gagal dihapus');
        }

        $unit->delete();

        return redirect()->back()->with('success', 'Satuan berhasil dihapus');
    }
}
