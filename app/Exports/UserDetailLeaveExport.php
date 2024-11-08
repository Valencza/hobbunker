<?php

namespace App\Exports;

use App\Models\SettingLeave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserDetailLeaveExport implements FromCollection, WithHeadings, WithMapping
{
    protected $leaves;

    public function __construct($leaves)
    {
        $this->leaves = $leaves;
    }

    public function collection()
    {
        return $this->leaves;
    }

    public function headings(): array
    {
        return [
            'Tanggal Pengajuan',
            'Tipe',
            'Mulai',
            'Selesai',
            'Disetujui',
            'Status'
        ];
    }

    public function map($leave): array
    {
        return [
            $leave->formatted_created_at,
            $leave->type,
            $leave->formatted_start_date,
            $leave->formatted_end_date,
            $leave->approverMasterUser->name,
            $leave->status
        ];
    }
}
