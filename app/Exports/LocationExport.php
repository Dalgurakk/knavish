<?php

namespace App\Exports;

use App\Location;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class LocationExport extends GeneralExport implements WithColumnFormatting
{
    public function collection() {
        $items = Location::with(['parent'])->orderBy('_lft')->get();
        $filtered = $items->map(function ($item, $index) {
            $this->records = $index + 1;
            return [
                'index' => $index + 1,
                'code' => $item->code,
                'denomination' => $item->name,
                'parent' => $item->parent['name'],
                'enabled' => $item->active == '1' ? 'Yes' : 'No'
            ];
        });
        $this->rangeContent = parent::getContentRange($this->records);
        return $filtered;
    }

    public function headings(): array
    {
        return [
            'Number',
            'Code',
            'Denomination',
            'Parent',
            'Enabled',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
