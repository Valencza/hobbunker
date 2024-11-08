<?php

namespace App\Http\Controllers\Backoffice;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\MasterRole;
use App\Models\MasterShip;
use App\Models\SalaryFluctuation;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use  PDF;

class MasterUserController extends Controller
{
    public function index()
{
    $search = request('search');
    $startDate = request('startDate');
    $endDate = request('endDate');

    $masterRoles = MasterRole::latest()->get();
    $masterShips = MasterShip::latest()->get();
    $masterUserDivisions = User::latest()
        ->where('master_role_uuid', '6b20e26e-30e7-4c44-8c72-2e24bcf8d5e6')
        ->get();

    $masterUsers = User::orderBy('name', 'ASC')
        ->where(function ($query) use ($search) {
            $query->orWhere('name', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
        })
        ->paginate(10);

    $masterUsers->appends(request()->query());

    // Pass the variables to the view
    return view('backoffice.pages.master.user.index', compact(
        'search',
        'startDate',  // Include this variable
        'endDate',    // Include this variable
        'masterRoles',
        'masterShips',
        'masterUserDivisions',
        'masterUsers'
    ));
}

    public function store()
    {
        $validator = Validator::make(request()->all(),  [
            'master_role_uuid' => 'required',
            'name' => 'required',
            'email' => 'required|unique:master_users',
            'nik' => 'required',
            'phone' => 'required|unique:master_users',
            'emergency_phone' => 'required|unique:master_users',
            'dependent' => 'required',
            'salary' => 'required',
            'address' => 'required',
            'birth' => 'required',
            'password' => 'required|confirmed',
            'position' => 'required'
        ], [
            'master_role_uuid.required' => 'Jabatan harus dipilih',
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan',
            'nik.required' => 'NIK harus diisi',
            'phone.required' => 'No. HP harus diisi',
            'phone.unique' => 'No. HP sudah digunakan',
            'emergency_phone.required' => 'No. HP darurat harus diisi',
            'emergency_phone.unique' => 'No. HP darurat sudah digunakan',
            'dependent.required' => 'Jumlah tanggungan harus diisi',
            'salary.required' => 'Jumlah gaji harus diisi',
            'address.required' => 'Alamat harus diisi',
            'password.required' => 'Kata sandi harus diisi',
            'password.confirmed' => 'Kata sandi tidak sesuai',
            'position.required' => 'Posisi harus diisi'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Karyawan baru gagal dibuat');
        }

        $request = request()->except('password_confirmation', 'salary');
        $request['password'] = Hash::make(request('password'));

        $user = User::create($request);

        SalaryFluctuation::create([
            'master_user_uuid' => $user->uuid,
            'date' => NOW(),
            'salary' => request('salary')
        ]);

        return redirect()->back()->with('success', 'Karyawan baru berhasil dibuat');
    }

    public function update($uuid)
    {
        $user = User::where('uuid', $uuid)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Karyawan tidak ditemukan');
        }

        $validator = Validator::make(request()->all(), [
            'master_role_uuid' => 'required',
            'name' => 'required',
            'email' => 'required',
            'nik' => 'required',
            'phone' => 'required',
            'emergency_phone' => 'required',
            'dependent' => 'required',
            'salary' => 'required',
            'address' => 'required',
            'birth' => 'required',
            'position' => 'required'
        ], [
            'master_role_uuid.required' => 'Jabatan harus dipilih',
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan',
            'nik.required' => 'NIK harus diisi',
            'phone.required' => 'No. HP harus diisi',
            'emergency_phone.required' => 'No. HP darurat harus diisi',
            'dependent.required' => 'Jumlah tanggungan harus diisi',
            'salary.required' => 'Jumlah gaji harus diisi',
            'address.required' => 'Alamat harus diisi',
            'birth.required' => 'Tanggal lahir harus diisi',
            'position.required' => 'Posisi harus diisi'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Karyawan gagal diubah');
        }

        $request = request()->except('_method', '_token', 'password_confirmation', 'salary');

        if (request('password') != null && request('password') != '') {
            $request = request()->except('password_confirmation');
            $request['password'] = Hash::make(request('password'));
            $user->password = $request['password'];
        }

        SalaryFluctuation::create([
            'master_user_uuid' => $user->uuid,
            'date' => NOW(),
            'salary' => request('salary')
        ]);

        $user->name = $request['name'];
        $user->master_role_uuid = $request['master_role_uuid'];
        $user->sub_role = isset($request['sub_role']) ? $request['sub_role'] : null;
        $user->email = $request['email'];
        $user->nik = $request['nik'];
        $user->phone = $request['phone'];
        $user->emergency_phone = $request['emergency_phone'];
        $user->dependent = $request['dependent'];
        $user->address = $request['address'];
        $user->position = $request['position'];
        $user->birth = $request['birth'];
        $user->save();

        return redirect()->back()->with('success', 'Karyawan berhasil diubah');
    }

    public function delete($uuid)
    {
        $user = User::where('uuid', $uuid)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Karyawan gagal dihapus');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Karyawan berhasil dihapus');
    }

    public function export()
    {
        $search = request('search') ?? '';

        $masterUsers = User::orderBy('name', 'ASC')
            ->where(function ($query) use ($search) {
                $query->oRwhere('name', 'LIKE', "%$search%");
                $query->oRwhere('email', 'LIKE', "%$search%");
            })->get();


        return Excel::download(new UserExport($masterUsers), "Laporan Karyawan.xlsx");
    }

    public function pdf()
    {
        $search = request('search');
        $startDate = Carbon::parse(request('startDate'));
        $endDate = Carbon::parse(request('endDate'));

        $masterUsers = User::orderBy('name', 'ASC')
            ->where(function ($query) use ($search) {
                $query->oRwhere('name', 'LIKE', "%$search%");
                $query->oRwhere('email', 'LIKE', "%$search%");
            })->get();

        $startDate = $startDate->format('d M Y');
        $endDate = $endDate->format('d M Y');
        $fileName = "Laporan Data Karyawan ($startDate";
        if ($endDate != $startDate) {
            $fileName .= " - $endDate";
        }
        $fileName .= ').pdf';

        // return view('backoffice.pages.hr.absent.pdf', compact('absents', 'startDate', 'endDate'));

        $pdf = pdf::loadView('backoffice.pages.master.user.pdf', compact('masterUsers', 'startDate', 'endDate'));
        $pdf->setOption('dpi', 96); 

        return $pdf->download($fileName);
    }
}
