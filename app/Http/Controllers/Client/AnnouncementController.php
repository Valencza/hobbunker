<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MasterAnnouncement;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function index()
    {
        $today = MasterAnnouncement::whereDate('start_date', Carbon::today())->get();

        $yesterday = MasterAnnouncement::whereDate('start_date', Carbon::yesterday())->get();

        $other = MasterAnnouncement::where(function ($query) {
            $query->whereDate('start_date', '!=', Carbon::today())
                ->orWhereDate('start_date', '!=', Carbon::yesterday());
        })->get();

        return view('client.pages.announcement.index', compact(
            'today',
            'yesterday',
            'other'
        ));
    }

    public function detail($uuid)
    {
        $announcement = MasterAnnouncement::find($uuid);

        if (!$announcement) return redirect()->route('announcement.index');

        return view('client.pages.announcement.detail', compact('announcement'));
    }
}
