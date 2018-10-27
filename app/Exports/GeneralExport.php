<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GeneralExport implements FromCollection, WithHeadings, WithCustomStartCell, WithDrawings, ShouldAutoSize, WithStrictNullComparison, WithTitle, WithEvents
{
    protected $parameters;
    protected $rangeContent;
    protected $records;

    public function __construct($parameters) {
        $this->parameters = $parameters;
        $this->rangeContent = null;
        $this->records = 0;
        $this->parameters['styleHeaderArray'] = [
            'font' => [
                'size' => '14',
                'bold' => true,
                'color' => [
                    'argb' => 'FF000066',
                ]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $this->parameters['headerCell'] = 'A4';
        $this->parameters['styleTableHeaderArray'] = [
            'font' => [
                'color' => [
                    'argb' => 'FFFFFFFF',
                ]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'argb' => 'FF000066',
                    ]
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => [
                    'argb' => 'FF000066',
                ]
            ],
        ];
        $this->parameters['styleTableContentArray'] = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'argb' => 'FF000066',
                    ]
                ],
            ],
        ];
    }

    public function getContentRange($countRows) {
        $cellRange = $this->parameters['settings']['cellRange'];
        $explodeArr = explode(':', $cellRange);
        $startCell = $explodeArr[0];
        $endCell = $explodeArr[1];
        $numbersStart = preg_replace('/[^0-9]/', '', $startCell);
        $lettersStart = preg_replace('/[^a-zA-Z]/', '', $startCell);
        $numbersEnd = preg_replace('/[^0-9]/', '', $endCell);
        $lettersEnd = preg_replace('/[^a-zA-Z]/', '', $endCell);
        return $lettersStart . ($numbersStart + 1) . ':' . $lettersEnd . ($numbersEnd + $countRows);
    }

    public function title(): string
    {
        return $this->parameters['settings']['headerText'];
    }

    public function collection() {

    }

    public function headings(): array
    {

    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function drawings()
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('assets/layouts/layout2/img/logo-royal-224x50.png'));
        $drawing->setHeight(45);
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);
        return $drawing;
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
