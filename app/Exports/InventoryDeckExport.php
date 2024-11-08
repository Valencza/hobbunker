<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryDeckExport implements FromCollection, WithHeadings, WithMapping
{
    protected $masterItems;

    public function __construct($masterItems)
    {
        $this->masterItems = $masterItems;
    }

    public function collection()
    {
        return $this->masterItems;
    }

    public function headings(): array
    {
        return [
            'Barang',
            'Merk',
            'Sisa Stok',
        ];
    }

    public function map($masterItem): array
    {
        return [
            $masterItem->name,
            $masterItem->merk,
            $masterItem->stockHistory->total,
        ];
    }
}
