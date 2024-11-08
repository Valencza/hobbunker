<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\MasterAnnouncement;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    public function index()
    {
        $search = request('search');

        $calendars = MasterAnnouncement::get();
        $calendars = $calendars->map(function ($calendar) {
            return [
                'title' => $calendar->title,
                'start' => $calendar->start_date,
                'end' => $calendar->end_date
            ];
        })->toJson();

        $masterAnnouncements = MasterAnnouncement::latest()
            ->where('title', 'LIKE', "%$search%")
            ->paginate(10);

        $masterAnnouncements->appends(request()->query());

        return view('backoffice.pages.hr.announcement.index', compact(
            'search',
            'calendars',
            'masterAnnouncements'
        ));
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'image' => 'required|mimes:jpg,jpeg,png|max:100000',
            'title' => 'required',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required'
        ], [
            'image.required' => 'Gambar harus diisi',
            'image.mimes' => 'Gambar harus sesuai format (jpg, jpeg, png)',
            'image.max' => 'Gambar harus sesuai maksimal 100MB',
            'title.required' => 'Judul harus diisi',
            'type.required' => 'Tipe harus diisi',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'end_date.required' => 'Tanggal selesai harus diisi',
            'description.required' => 'Deskripsi harus diisi'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Pengumuman baru gagal dibuat');
        }


        $file = request()->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/announcement'), $fileName);

        $request = request()->all();
        $request['image'] = "images/announcement/$fileName";

        MasterAnnouncement::create($request);

        return redirect()->back()->with('success', 'Pengumuman baru berhasil dibuat');
    }

    public function update($uuid)
    {
        $announcement = MasterAnnouncement::where('uuid', $uuid)->first();

        if (!$announcement) {
            return redirect()->back()->with('error', 'Pengumuman tidak ditemukan');
        }

        $validator = Validator::make(request()->all(), [
            'image' => 'nullable|mimes:jpg,jpeg,png|max:100000',
            'title' => 'required',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'description' => 'required'
        ], [
            'image.mimes' => 'Gambar harus sesuai format (jpg, jpeg, png)',
            'image.max' => 'Gambar harus sesuai maksimal 100MB',
            'title.required' => 'Judul harus diisi',
            'type.required' => 'Tipe harus diisi',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'end_date.required' => 'Tanggal selesai harus diisi',
            'description.required' => 'Deskripsi harus diisi'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Pengumuman gagal diubah');
        }

        $request = request()->except('_method', '_token');

        if (request()->hasFile('image')) {
            $file = request()->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/announcement'), $fileName);
            $request['image'] = "images/announcement/$fileName";
        }

        $announcement->update($request);

        return redirect()->back()->with('success', 'Pengumuman berhasil diubah');
    }

    public function delete($uuid)
    {
        $announcement = MasterAnnouncement::where('uuid', $uuid)->first();

        if (!$announcement) {
            return redirect()->back()->with('error', 'Pengumuman gagal dihapus');
        }

        $announcement->delete();

        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus');
    }
}
