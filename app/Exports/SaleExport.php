<?php

namespace App\Exports;

use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Writer;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;

class SaleExport extends Export implements FromCollection, WithCustomStartCell, WithTitle, WithHeadings, WithEvents, WithStyles
{
    /**
     * __construct
     *
     * @param  string $startCell
     * @return void
     */
    public function __construct(string $startCell = '', string $title = '')
    {
        $this->resolvePropNames(func_get_args());
        $this->_loadMacroable();
        $this->beforeEvents = $this->_beforeEvents();
        $this->afterEvents = $this->_afterEvents();
        $this->styles = $this->_styles();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            ['Company 1 in Q1 (C00001)'],
            [1, 2, 3],
            [4, 5, 6],
            [null],
            ['Company 2 in Q2 (C00002)'],
            [7, 8, 9],
            [10, 11, 12],
        ]);
    }

    public function headings(): array
    {
        return [
            [
                '#',
                'User',
                'Date',
            ],
            [
                '#2',
                'User2',
                'Date2',
            ],
        ];
    }

    private function _loadMacroable()
    {
        Writer::macro('setCreator', function (Writer $writer, string $creator) {
            $writer->getDelegate()->getProperties()->setCreator($creator);
        });

        Sheet::macro('setOrientation', function (Sheet $sheet, $orientation) {
            $sheet->getDelegate()->getPageSetup()->setOrientation($orientation);
        });

        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });
    }

    private function _beforeEvents()
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->setCreator('Tester');
            }
        ];
    }

    private function _afterEvents()
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                $event->sheet->styleCells(
                    'B5:D10',
                    [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => Border::BORDER_THICK,
                                'color' => ['argb' => 'FFFF0000'],
                            ],
                        ]
                    ]
                );
            },
        ];
    }

    private function _styles()
    {
        return [
            // Style the first row as bold text.
            5    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            'B7' => ['font' => ['italic' => true]],

            // Styling an entire column.
            'C'  => ['font' => ['size' => 16]],
        ];
    }

}
