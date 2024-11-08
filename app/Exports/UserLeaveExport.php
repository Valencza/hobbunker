<?php

namespace App\Exports;

use App\Models\SettingLeave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserLeaveExport implements FromCollection, WithHeadings, WithMapping
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Jabatan',
            'Pengajuan/Saldo',
            'Telepon',
            'Umur'
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->masterRole->name,
            $user->leave_request . '/' . SettingLeave::first()->balance_year,
            $user->phone,
            $user->age
        ];
    }
}
