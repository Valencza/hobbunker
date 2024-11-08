<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $absents;

    public function __construct($absents)
    {
        $this->absents = $absents;
    }

    public function collection()
    {
        return $this->absents;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Absen Masuk',
            'Absen Keluar',
            'Status',
            'Status Jarak'
        ];
    }

    public function map($absent): array
    {
        return [
            $absent->formatted_created_at,
            $absent->checkin_clock,
            $absent->checkout_clock,
            $absent->status,
            $absent->checkin_status_radius
        ];
    }
}
