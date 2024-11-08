<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MasterShip;
use Illuminate\Support\Facades\Validator;

class MasterShipController extends Controller
{
    public function index()
    {
        $search = request('search');

        $masterShips = MasterShip::latest()
            ->where('name', 'LIKE', "%$search%")
            ->paginate(10);

            $masterShips->appends(request()->query());


        return view('backoffice.pages.master.ship.index', compact(
            'search',
            'masterShips'
        ));
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'image' => 'required|mimes:jpg,jpeg,png,webp|max:100000',
            'name' => 'required',
            'type' => 'required',
            'capacity' => 'required',
        ], [
            'image.required' => 'Gambar harus diisi',
            'image.mimes' => 'Gambar harus sesuai format (jpg, jpeg, png)',
            'image.max' => 'Gambar harus sesuai maksimal 100MB',
            'name.required' => 'Nama harus diisi',
            'type.required' => 'Tipe harus diisi',
            'capacity.required' => 'Kapasitas mulai harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Kapal baru gagal dibuat');
        }


        $file = request()->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/ship'), $fileName);

        $request = request()->all();
        $request['image'] = "images/ship/$fileName";

        MasterShip::create($request);

        return redirect()->back()->with('success', 'Kapal baru berhasil dibuat');
    }

    public function update($uuid)
    {
        $ship = MasterShip::where('uuid', $uuid)->first();

        if (!$ship) {
            return redirect()->back()->with('error', 'Kapal tidak ditemukan');
        }

        $validator = Validator::make(request()->all(), [
            'image' => 'mimes:jpg,jpeg,png,webp|max:100000',
            'name' => 'required',
            'type' => 'required',
            'capacity' => 'required',
        ], [
            'image.mimes' => 'Gambar harus sesuai format (jpg, jpeg, png)',
            'image.max' => 'Gambar harus sesuai maksimal 100MB',
            'name.required' => 'Nama harus diisi',
            'type.required' => 'Tipe harus diisi',
            'capacity.required' => 'Kapasitas harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Kapal gagal diubah');
        }

        $requestData = request()->except('_method', '_token');

        if (request()->hasFile('image')) {
            $file = request()->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/ship'), $fileName);
            $requestData['image'] = "images/ship/$fileName";

            if (file_exists(public_path($ship->image))) {
                unlink(public_path($ship->image));
            }
        }

        $ship->update($requestData);

        return redirect()->back()->with('success', 'Kapal berhasil diubah');
    }

    public function delete($uuid)
    {
        $ship = MasterShip::where('uuid', $uuid)->first();

        if (!$ship) {
            return redirect()->back()->with('error', 'Kapal gagal dihapus');
        }

        $ship->delete();

        return redirect()->back()->with('success', 'Kapal berhasil dihapus');
    }
}
