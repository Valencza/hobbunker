<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Absent;
use App\Models\SettingAbsent;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class AbsentController extends Controller
{
    public function index()
    {
        $settingAbsent = SettingAbsent::first();

        $now = Carbon::now();
        $today = $now->translatedFormat('l j F Y');
        $clock = $now->format('H:i:s');

        $absentToday = Absent::whereDate('created_at', Carbon::today())
            ->where('master_user_uuid', Auth::user()->uuid)
            ->first();
        $absents = Absent::where('master_user_uuid', Auth::user()->uuid)->limit(5)
            ->get();
        $countAbsents = Absent::where('master_user_uuid', Auth::user()->uuid)->count();
        $countAbsentsOnTime = Absent::where('master_user_uuid', Auth::user()->uuid)->where('status', 'hadir')
            ->count();
        $countAbsentsLate = Absent::where('master_user_uuid', Auth::user()->uuid)->where('status', 'terlambat')
            ->count();
        $countAbsentsNotPresent = Absent::where('master_user_uuid', Auth::user()->uuid)->where('status', 'tidak hadir')
            ->count();
        $countAbsentsLeave = Absent::where('master_user_uuid', Auth::user()->uuid)->where('status', 'cuti')
            ->count();

        return view('client.pages.absent.index', compact(
            'settingAbsent',
            'today',
            'clock',
            'absentToday',
            'absents',
            'countAbsents',
            'countAbsentsOnTime',
            'countAbsentsLate',
            'countAbsentsNotPresent',
            'countAbsentsLeave'
        ));
    }

    public function history()
    {
        $absents = Absent::where('master_user_uuid', Auth::user()->uuid)
            ->latest()
            ->get();

        return view('client.pages.absent.history', compact(
            'absents',
        ));
    }

    public function checkinout()
    {
        $settingAbsent = SettingAbsent::first();

        $absentToday = Absent::whereDate('created_at', Carbon::today())
            ->where('master_user_uuid', Auth::user()->uuid)
            ->first();

        $now = Carbon::now();
        $today = $now->translatedFormat('l j F Y');
        $clock = $now->format('H:i:s');

        $status = $absentToday ? 'checkout' : 'checkin';

        return view('client.pages.absent.checkinout', compact(
            'settingAbsent',
            'status',
            'absentToday',
            'today',
            'clock',
        ));
    }

    public function recognition()
    {
        $user = Auth::user();

        return view('client.pages.absent.recognition', compact('user'));
    }


    public function upload()
    {
        $user = Auth::user();

        return view('client.pages.absent.upload', compact('user'));
    }

    public function uploadProcess()
    {
        $file = request()->file('photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/absents'), $fileName);
        $filePath = "images/absents/$fileName";

        if (request('absent_status') == 'checkin') {
            Absent::where('uuid', request('uuid'))
                ->update([
                    'checkin_photo' => $filePath
                ]);
        } else {
            Absent::where('uuid', request('uuid'))
                ->update([
                    'checkout_photo' => $filePath
                ]);
        }

        return redirect()->route('absent')->with('success', 'Absensi berhasil dilakukan');
    }

    public function process()
    {
        $settingAbsent = SettingAbsent::first();

        $absentToday = Absent::whereDate('created_at', Carbon::today())
            ->where('master_user_uuid', Auth::user()->uuid)
            ->first();

        $checkinClock = request('absent_clock');

        if (request('absent_status') == 'checkin') {
            if ($absentToday) {
                return response()->json(['success' => true, 'uuid' => $absentToday->uuid]);
            }

            $status = 'hadir';

            if ($checkinClock > $settingAbsent->clock_in_limit) {
                $status = 'terlambat';
            }

            $current_time = Carbon::now();

            $start_time = Carbon::today()->setTime(19, 0, 0);
            $end_time = Carbon::tomorrow()->setTime(7, 0, 0);

            $start_time2 = Carbon::today()->setTime(7, 0, 0);
            $end_time2 = Carbon::today()->setTime(19, 0, 0);

            if ($current_time->between($start_time, $end_time) || $current_time->between($start_time2, $end_time2)) {
                $status = 'hadir';
            }

            $absentToday = Absent::create([
                'master_user_uuid' => Auth::user()->uuid,
                'date' => Carbon::now(),
                'checkin_clock' => $checkinClock,
                'checkin_latitude' => request('absent_latitude'),
                'checkin_longitude' => request('absent_longitude'),
                'checkin_radius' => request('absent_radius'),
                'checkin_status_radius' => request('absent_status_radius'),
                'checkin_detail_job' => request('absent_detail_job'),
                'checkin_location' => request('absent_location'),
                'status' => $status
            ]);
        } else {
            if ($absentToday->checkout_clock) {
                return response()->json(['success' => true, 'uuid' => $absentToday->uuid]);
            }

            $absentToday->checkout_clock = $checkinClock;
            $absentToday->checkout_latitude = request('absent_latitude');
            $absentToday->checkout_longitude = request('absent_longitude');
            $absentToday->checkout_radius = request('absent_radius');
            $absentToday->checkout_status_radius = request('absent_status_radius');
            $absentToday->checkout_detail_job = request('absent_detail_job');
            $absentToday->checkout_location = request('absent_location');
            $absentToday->save();
        }

        return response()->json(['success' => true, 'uuid' => $absentToday->uuid]);
    }
}
