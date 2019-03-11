<?php

namespace App\Exports;

use App\Models\HotelBoardType;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class BoardTypeExport extends GeneralExport implements WithEvents
{
    public function collection() {
        $query = HotelBoardType::orderBy('name');
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
            'Enabled',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $settings = $this->parameters['settings'];
                $headerRange = $settings['headerRange'];
                $styleHeaderArray = $this->parameters['styleHeaderArray'];
                $headerCell = $this->parameters['headerCell'];
                $sheet->getCell($headerCell)->setValue('  ' . $settings['headerText']);
                $sheet->getStyle($headerCell)->getAlignment()->setWrapText(false);
                $sheet->getStyle($headerRange)->applyFromArray($styleHeaderArray);
                $sheet->mergeCells($settings['headerRange']);
                $cellRange = $settings['cellRange'];
                $styleArray = $this->parameters['styleTableHeaderArray'];
                $sheet->getStyle($cellRange)->applyFromArray($styleArray);
                $sheet->setAutoFilter($cellRange);
                if($this->records > 0) {
                    $sheet->getStyle($this->rangeContent)->applyFromArray($this->parameters['styleTableContentArray']);
                }
            },
        ];
    }
}
