<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MasterHoliday;
use Illuminate\Support\Facades\Validator;

class MasterHolidayController extends Controller
{
    public function index()
    {
        $search = request('search');

        $masterHolidays = MasterHoliday::latest()
            ->where('name', 'LIKE', "%$search%")
            ->paginate(10);

            $masterHolidays->appends(request()->query());


        return view('backoffice.pages.master.holiday.index', compact(
            'search',
            'masterHolidays'
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
                ->with('error', 'Hari libur baru gagal dibuat');
        }

        MasterHoliday::create(request()->all());

        return redirect()->back()->with('success', 'Hari libur baru berhasil dibuat');
    }

    public function update($uuid)
    {
        $holiday = MasterHoliday::where('uuid', $uuid)->first();

        if (!$holiday) {
            return redirect()->back()->with('error', 'Hari libur tidak ditemukan');
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
                ->with('error', 'Hari libur gagal diubah');
        }

        $holiday->update(request()->except('_method', '_token'));

        return redirect()->back()->with('success', 'Hari libur berhasil diubah');
    }

    public function delete($uuid)
    {
        $holiday = MasterHoliday::where('uuid', $uuid)->first();

        if (!$holiday) {
            return redirect()->back()->with('error', 'Hari libur gagal dihapus');
        }

        $holiday->delete();

        return redirect()->back()->with('success', 'Hari libur berhasil dihapus');
    }
}
