<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\SettingLeave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LeaveApprovalController extends Controller
{
    public function index()
    {
        $status = request('status');
        $search = request('search');

        $leaves = Leave::latest();

        $countLeaveBalanceYear = SettingLeave::first()
            ->balance_year;

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

        $leaves = $leaves
            ->whereHas('masterUser', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->paginate(10);

            $leaves->appends(request()->query());

        return view('backoffice.pages.hr.leave.approval', compact(
            'status',
            'search',
            'countLeaveBalanceYear',
            'leaves'
        ));
    }

    public function accept($uuid)
    {
        $leave = Leave::findOrFail($uuid);
        $leave->status = 'disetujui';
        $leave->approver_master_user_uuid = Auth::user()->uuid;
        $leave->note = request('note') ?? null;
        $leave->save();

        return redirect()->route('leave-approval')->with('success', 'Pengajuan cuti berhasil disetujui');
    }

    public function reject($uuid)
    {
        $leave = Leave::findOrFail($uuid);
        $leave->status = 'ditolak';
        $leave->approver_master_user_uuid = Auth::user()->uuid;
        $leave->note = request('note') ?? null;
        $leave->save();

        return redirect()->route('leave-approval')->with('success', 'Pengajuan cuti berhasil ditolak');
    }
}
