<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MasterDivision;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MasterDivisonController extends Controller
{
    public function index()
    {
        $search = request('search');

        $masterUserDivisions = User::latest()
            ->where('master_role_uuid', '6b20e26e-30e7-4c44-8c72-2e24bcf8d5e6')
            ->get();

        $masterDivisions = MasterDivision::latest()
            ->where('name', 'LIKE', "%$search%")
            ->paginate(10);

            $masterDivisions->appends(request()->query());


        return view('backoffice.pages.master.division.index', compact(
            'search',
            'masterUserDivisions',
            'masterDivisions'
        ));
    }

    public function store()
    {
        $validator = Validator::make(request()->all(),  [
            'master_user_uuid' => 'required',
            'name' => 'required',
        ], [
            'master_user_uuid.required' => 'Kepala divisi harus dipilih',
            'name.required' => 'Nama harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Divisi baru gagal dibuat');
        }

        $request = request()->all();

        MasterDivision::create($request);

        return redirect()->back()->with('success', 'Divisi baru berhasil dibuat');
    }

    public function update($uuid)
    {
        $division = MasterDivision::where('uuid', $uuid)->first();

        if (!$division) {
            return redirect()->back()->with('error', 'Divisi tidak ditemukan');
        }

        $validator = Validator::make(request()->all(),  [
            'master_user_uuid' => 'required',
            'name' => 'required',
        ], [
            'master_user_uuid.required' => 'Kepala divisi harus dipilih',
            'name.required' => 'Nama harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Divisi gagal diubah');
        }

        $request = request()->except('_method', '_token');

        $division->update($request);

        return redirect()->back()->with('success', 'Divisi berhasil diubah');
    }

    public function delete($uuid)
    {
        $division = MasterDivision::where('uuid', $uuid)->first();

        if (!$division) {
            return redirect()->back()->with('error', 'Divisi gagal dihapus');
        }

        $division->delete();

        return redirect()->back()->with('success', 'Divisi berhasil dihapus');
    }
}
