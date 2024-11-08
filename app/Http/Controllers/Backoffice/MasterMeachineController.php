<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MasterMeachine;
use Illuminate\Support\Facades\Validator;

class MasterMeachineController extends Controller
{
    public function index()
    {
        $search = request('search');

        $masterMeachines = MasterMeachine::latest()
            ->where('name', 'LIKE', "%$search%")
            ->paginate(10);

            $masterMeachines->appends(request()->query());


        return view('backoffice.pages.master.meachine.index', compact(
            'search',
            'masterMeachines'
        ));
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'hours' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
            'hours.required' => 'Jam harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Mesin baru gagal dibuat');
        }

        MasterMeachine::create(request()->all());

        return redirect()->back()->with('success', 'Mesin baru berhasil dibuat');
    }

    public function update($uuid)
    {
        $meachine = MasterMeachine::where('uuid', $uuid)->first();

        if (!$meachine) {
            return redirect()->back()->with('error', 'Mesin tidak ditemukan');
        }

        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'hours' => 'required',
        ], [
            'name.required' => 'Nama harus diisi',
            'hours.required' => 'Jam harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Mesin gagal diubah');
        }

        $meachine->update(request()->except('_method', '_token'));

        return redirect()->back()->with('success', 'Mesin berhasil diubah');
    }

    public function delete($uuid)
    {
        $meachine = MasterMeachine::where('uuid', $uuid)->first();

        if (!$meachine) {
            return redirect()->back()->with('error', 'Mesin gagal dihapus');
        }

        $meachine->delete();

        return redirect()->back()->with('success', 'Mesin berhasil dihapus');
    }
}
