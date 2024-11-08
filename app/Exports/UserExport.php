<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
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
            'Nama',
            'Jabatan',
            'Emaill',
            'Nomor Pegawai',
            'No. HP',
            'No. HP Darurat',
            'Alamat',
            'Tanggal Lahir',
            'Jumlah Tanggungan',
            'Lokasi',
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->masterRole->name,
            $user->email,
            $user->nik,
            $user->phone,
            $user->emergency_phone,
            $user->address,
            $user->birth,
            $user->dependent,
            $user->position,
        ];
    }
}
