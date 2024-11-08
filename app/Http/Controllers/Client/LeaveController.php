<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\SettingLeave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function index()
    {
        $settingLeave = SettingLeave::first();
        $user = Auth::user();
        $leaves =  Leave::where('master_user_uuid', $user->uuid)->paginate(10);
        $leavesRequest =  Leave::where('master_user_uuid', $user->uuid)
            ->where('status', 'pending')
            ->paginate(10);
        $leavesAccepted =  Leave::where('master_user_uuid', $user->uuid)
            ->where('status', 'disetujui')
            ->paginate(10);
        $leavesRejected =  Leave::where('master_user_uuid', $user->uuid)
            ->where('status', 'ditolak')
            ->paginate(10);
        $leavesExpired =  Leave::where('master_user_uuid', $user->uuid)
            ->where('status', 'expired')
            ->paginate(10);

            $leavesRequest->appends(request()->query());

            $leavesAccepted->appends(request()->query());

            $leavesRejected->appends(request()->query());

            $leavesExpired->appends(request()->query());



        return view('client.pages.leave.index', compact(
            'settingLeave',
            'user',
            'leaves',
            'leavesRequest',
            'leavesAccepted',
            'leavesRejected',
            'leavesExpired'
        ));
    }

    public function list()
    {
        $search = request('search') ?? '';

        $leaves =  Leave::whereHas('masterUser', function ($query) use ($search) {
            $query->where('name', 'LIKE', "%$search%");
        })
            ->paginate(10);

        $leavesRequest =  Leave::where('status', 'pending')
            ->whereHas('masterUser', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->paginate(10);
        $leavesAccepted =  Leave::where('status', 'disetujui')
            ->whereHas('masterUser', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->paginate(10);
        $leavesRejected =  Leave::where('status', 'ditolak')
            ->whereHas('masterUser', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->paginate(10);
        $leavesExpired =  Leave::where('status', 'expired')
            ->whereHas('masterUser', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->paginate(10);

            $leaves->appends(request()->query());


            $leavesRequest->appends(request()->query());


            $leavesAccepted->appends(request()->query());


            $leavesRejected->appends(request()->query());


            $leavesExpired->appends(request()->query());


        return view('client.pages.leave.list', compact(
            'search',
            'leaves',
            'leavesRequest',
            'leavesAccepted',
            'leavesRejected',
            'leavesExpired'
        ));
    }

    public function detail($uuid)
    {
        $leave = Leave::find($uuid);

        return view('client.pages.leave.detail', compact(
            'leave'
        ));
    }

    public function store()
    {
        $user = Auth::user();
        $settingLeave = SettingLeave::first();

        if ($user->leave_request == $settingLeave->balance_year) {
            return redirect()->back()->with('error', 'Saldo cuti tahunan telah habis');
        }

        $validator = Validator::make(request()->all(),  [
            'title' => 'required',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required',
        ], [
            'title.required' => 'Judul harus diisi',
            'type.required' => 'Tipe harus dipilih',
            'start_date.required' => 'Tanggal awal harus diisi',
            'end_date.required' => 'Tanggal akhir harus diisi',
            'reason.required' => 'Alasan harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $request = request()->except('_token');
        $request['master_user_uuid'] = $user->uuid;

        if ($user->masterRole->slug == 'direksi' || $user->masterRole->slug == 'hrd') {
            $request['status'] = 'disetujui';
            $request['approver_master_user_uuid'] = $user->uuid;
        }

        $startDate = Carbon::parse(request('start_date'));
        $endDate = Carbon::parse(request('end_date'));

        $totalDays = $endDate->diffInDays($startDate);


        if($totalDays == 0){
            return redirect()->back()->with('error', 'Pengajuan cuti tidak valid');
        }

        $total = $user->leave_total + $totalDays;

        if($total >= $settingLeave->balance_year){
            return redirect()->back()->with('error', 'Melebihi saldo cuti tahunan');
        }

        Leave::create($request);

        return redirect()->back()->with('success', 'Pengajuan cuti baru berhasil dibuat');
    }


    public function accept($uuid)
    {
        $leave = Leave::findOrFail($uuid);
        $leave->status = 'disetujui';
        $leave->approver_master_user_uuid = Auth::user()->uuid;
        $leave->note = request('note') ?? null;
        $leave->save();

        return redirect()->route('leave.list')->with('success', 'Pengajuan cuti berhasil disetujui');
    }

    public function reject($uuid)
    {
        $leave = Leave::findOrFail($uuid);
        $leave->status = 'ditolak';
        $leave->approver_master_user_uuid = Auth::user()->uuid;
        $leave->note = request('note') ?? null;
        $leave->save();

        return redirect()->route('leave.list')->with('success', 'Pengajuan cuti berhasil ditolak');
    }
}
