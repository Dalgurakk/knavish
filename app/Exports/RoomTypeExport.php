<?php

namespace App\Exports;

use App\Models\HotelRoomType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class RoomTypeExport extends GeneralExport implements WithColumnFormatting
{
    public function collection() {
        $query = HotelRoomType::orderBy('name');
        if ($this->parameters['name'] != '') {
            $query->where('name', 'like', '%' . $this->parameters['name'] . '%');
        }
        if ($this->parameters['code'] != '') {
            $query->where('code', 'like', '%' . $this->parameters['code'] . '%');
        }
        if ($this->parameters['active'] != '') {
            $query->where('active', $this->parameters['active']);
        }
        $items = $query->get();
        $filtered = $items->map(function ($item, $index) {
            $this->records = $index + 1;
            return [
                'index' => $index + 1,
                'code' => $item->code,
                'denomination' => $item->name,
                'max_pax' => $item->max_pax,
                'min_pax' => $item->min_pax,
                'max_adult' => $item->max_adult,
                'min_adult' => $item->min_adult,
                'max_children' => $item->max_children,
                'min_children' => $item->min_children,
                'max_infant' => $item->max_infant,
                'min_infant' => $item->min_infant,
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
            'Max Pax',
            'Min Pax',
            'Max AD',
            'Min AD',
            'Max CH',
            'Min CH',
            'Max INF',
            'Min INF',
            'Enabled',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_GENERAL,
            'J' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
