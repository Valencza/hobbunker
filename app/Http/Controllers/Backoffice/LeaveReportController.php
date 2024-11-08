<?php

namespace App\Http\Controllers\Backoffice;

use App\Exports\UserDetailLeaveExport;
use App\Exports\UserLeaveExport;
use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\SettingLeave;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use  PDF;

class LeaveReportController extends Controller
{
    public function index()
    {
        $search =  request('search');
        $startDate = request('startDate');
        $endDate = request('endDate');

        $currentYear = Carbon::now()->year;
        $leaveYear = Leave::whereYear('created_at', $currentYear);

        $countLeaveBalanceYear = SettingLeave::first()
            ->balance_year;

        $countLeavesRequest = $leaveYear->count();

        $countLeavesAccepted = $leaveYear->where('status', 'disetujui')
            ->count();

        $countLeavesRejected = $leaveYear->where('status', 'ditolak')
            ->count();


        $countLeavesRequestYear = [];
        $countLeavesAcceptedYear = [];
        $countLeavesRejectedYear = [];

        for ($month = 1; $month <= 12; $month++) {
            $daysInMonth = Carbon::create($currentYear, $month)->daysInMonth;

            for ($dayOfMonth = 1; $dayOfMonth <= $daysInMonth; $dayOfMonth++) {
                $date = Carbon::create($currentYear, $month, $dayOfMonth);
                $countRequest = Leave::whereDate('created_at', $date)
                    ->count();
                $countLeavesRequestYear[$date->format('F')] = $countRequest;

                $countAccepted = Leave::where('status', 'disetujui')
                    ->whereDate('created_at', $date)
                    ->count();
                $countLeavesAcceptedYear[$date->format('F')] = $countAccepted;

                $countRejected = Leave::where('status', 'disetujui')
                    ->whereDate('created_at', $date)
                    ->count();
                $countLeavesRejectedYear[$date->format('F')] = $countRejected;
            }
        }

        $users = User::where('name', 'LIKE', "%$search%")
            ->paginate(10);

            $users->appends(request()->query());

        return view('backoffice.pages.hr.leave.index', compact(
            'search',
            'startDate',
            'endDate',
            'countLeaveBalanceYear',
            'countLeavesRequest',
            'countLeavesAccepted',
            'countLeavesRejected',
            'countLeavesRequestYear',
            'countLeavesAcceptedYear',
            'countLeavesRejectedYear',
            'users'
        ));
    }

    public function detail($uuid)
    {
        $status = request('status');

        $user = User::find($uuid);

        $currentYear = Carbon::now()->year;

        $leaveYear = Leave::whereYear('created_at', $currentYear);

        $countLeaveBalanceYear = SettingLeave::first()
            ->balance_year;

        $leaves  = Leave::where('master_user_uuid', $uuid);

        switch ($status) {
            case 'menunggu':
                $leaves->where('status', 'pending');
                break;
            case 'disetujui':
                $leaves->where('status', 'disetujui');
                break;
            case 'ditolak':
                $leaves->where('status', 'ditolak');
                break;
            case 'expired':
                $leaves->where('start_date', '<', Carbon::now());
                break;
        }

        $leaves = $leaves->paginate(10);

        return view('backoffice.pages.hr.leave.detail', compact(
            'status',
            'user',
            'countLeaveBalanceYear',
            'leaves'
        ));
    }

    public function export()
    {
        $currentYear = Carbon::now()->year;
        $users = User::all();
        
        return Excel::download(new UserLeaveExport($users), "Laporan Cuti Karyawan Tahunan ($currentYear) .xlsx");
    }

    public function pdf()
    {
        $search = request('search');
        $startDate = Carbon::parse(request('startDate'));
        $endDate = Carbon::parse(request('endDate'));
        $currentYear = Carbon::now()->year;
        $users = User::all();

        $users = User::orderBy('name', 'ASC')->get();

        $countLeaveBalanceYear = SettingLeave::first()
            ->balance_year;

            // memakai tahun

            // $startDate = $startDate->format('d M Y');
            // $fileName = "Laporan Data Cuti ($currentYear";
            // if ($currentYear != $startDate) {
            //     $fileName .= " - $currentYear";
            // }

            //memakai tanggal bulan tahun

        $startDate = $startDate->format('d M Y');
        $endDate = $endDate->format('d M Y');
        $fileName = "Laporan Data Cuti ($startDate";
        if ($endDate != $startDate) {
            $fileName .= " - $endDate";
        }
        $fileName .= ').pdf';

        // return view('backoffice.pages.hr.absent.pdf', compact('absents', 'startDate', 'endDate'));

        $pdf = pdf::loadView('backoffice.pages.hr.leave.pdf', compact('users', 'startDate', 'endDate', 'countLeaveBalanceYear'));
        $pdf->setOption('dpi', 96);

        return $pdf->download($fileName);
    }

    public function exportDetail($uuid)
    {
        $currentYear = Carbon::now()->year;

        $user = User::find($uuid);

        $leaves = Leave::whereYear('created_at', $currentYear)
            ->where('master_user_uuid', $uuid)
            ->get();

        return Excel::download(new UserDetailLeaveExport($leaves), "Laporan Cuti $user->name ($currentYear) .xlsx");
    }

    public function pdfDetail($uuid)
    {
        $currentYear = Carbon::now()->year;
        $search = request('search');
        $startDate = Carbon::parse(request('startDate'));
        $endDate = Carbon::parse(request('endDate'));

        $user = User::find($uuid);

        $leaves = Leave::whereYear('created_at', $currentYear)
            ->where('master_user_uuid', $uuid)
            ->get();

            $startDate = $startDate->format('M Y');
        $endDate = $endDate->format('M Y');
        $fileName = "Laporan Data Cuti $user->name ($startDate";
        if ($endDate != $startDate) {
            $fileName .= " - $endDate";
        }
        $fileName .= ').pdf';

        $pdf = pdf::loadView('backoffice.pages.hr.leave.pdf-detail', compact('leaves', 'startDate', 'endDate'));
        $pdf->setOption('dpi', 96); 

        return $pdf->download($fileName);
    }

}
