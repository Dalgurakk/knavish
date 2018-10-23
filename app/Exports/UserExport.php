<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class UserExport implements FromCollection, WithHeadings, WithCustomStartCell, WithDrawings, WithEvents, ShouldAutoSize
{
    private $parameters;

    public function __construct($parameters) {
        $this->parameters = $parameters;
    }

    public function collection() {
        $users = User::with(['roles'])->orderBy('username')->get();
        $filtered = $users->map(function ($item, $index) {
            return [
                'index' => $index + 1,
                'username' => $item->username,
                'role' => $item->roles[0]->description,
                'name' => $item->username,
                'email' => $item->email,
                'enabled' => $item->active == '1' ? 'Yes' : 'No'
            ];
        });
        return $filtered;
    }

    public function headings(): array
    {
        return [
            'Number',
            'Username',
            'Role',
            'Name',
            'Email',
            'Enabled',
        ];
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
                $headerRange = 'A4:F4';
                $styleHeaderArray = [
                    'font' => [
                        'size' => '14',
                        'bold' => true,
                        'color' => [
                            'argb' => 'FF000066',
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $sheet->getCell('A4')->setValue("Users List");
                $sheet->getStyle('A4')->getAlignment()->setWrapText(false);
                $sheet->getStyle($headerRange)->applyFromArray($styleHeaderArray);
                $sheet->mergeCells('A4:F4');

                $cellRange = 'A6:F6';
                $styleArray = [
                    'font' => [
                        'color' => [
                            'argb' => 'FFFFFFFF',
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => [
                            'argb' => 'FF000066',
                        ]
                    ],
                ];
                $sheet->getStyle($cellRange)->applyFromArray($styleArray);
                $sheet->setAutoFilter($cellRange);
            },
        ];
    }
}
