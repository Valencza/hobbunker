<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\RoadLetter;
use App\Models\SettingOffice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use  PDF;

class RoadLetterController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : Carbon::create($currentYear, $currentMonth, $currentDay);

        $roadLettersNew = RoadLetter::latest()
            ->where('status', 'baru')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);

        $roadLettersConfirmed = RoadLetter::latest()
            ->where('status', 'terkonfirmasi')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);

        $roadLettersDone = RoadLetter::latest()
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->paginate(10);


            $roadLettersNew->appends(request()->query());

            $roadLettersConfirmed->appends(request()->query());

            $roadLettersDone->appends(request()->query());

            
        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        return view(
            'client.pages.road-letter.index',
            compact(
                'roadLettersNew',
                'roadLettersConfirmed',
                'roadLettersDone',
                'startDate',
                'endDate'
            )
        );
    }

    public function detail($uuid)
    {
        $roadLetter = RoadLetter::find($uuid);

        return view(
            'client.pages.road-letter.detail',
            compact(
                'roadLetter',
            )
        );
    }

    public function update($uuid)
    {
        RoadLetter::where('uuid', $uuid)
            ->update([
                'approver_master_user_uuid' => Auth::user()->uuid,
                'status' => 'terkonfirmasi'
            ]);

        return redirect()->route('road-letter')->with('success', 'Surat jalan berhasil dikonfirmasi');
    }

    public function pdf($uuid)
    {
        $roadLetter =  RoadLetter::find($uuid);
        $settingOffice = SettingOffice::first();

        // return view('client.pages.road-letter.pdf', compact('roadLetter', 'settingOffice'));

        $pdf = PDF::loadView('client.pages.road-letter.pdf', compact('roadLetter', 'settingOffice'));

        return $pdf->download("$roadLetter->number.pdf");
    }
}
