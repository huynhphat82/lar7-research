<?php

namespace App\Exports;

use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Writer;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;

//class SaleExport extends Export implements FromCollection, WithCustomStartCell, WithTitle, WithHeadings, WithEvents, WithStyles, ShouldAutoSize
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
        $dataMock = $this->data();
        $data = collect($dataMock['rows']);
        // $data = $data->reduce(function ($carry, $item) {
        //     var_dump(($item));
        //     return $carry;
        // });
        // dd($data);
        return $data;
        // return collect([
        //     ['Company 1 in Q1 (C00001)'],
        //     [1, 2, 3],
        //     [4, 5, 6],
        //     [null],
        //     ['Company 2 in Q2 (C00002)'],
        //     [7, 8, 9],
        //     [10, 11, 12],
        // ]);
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
        return function (Worksheet $sheet) {
            $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            $sheet->mergeCells('B1:H1')->setCellValue('B1', 'Monthly Sale Report');
            $sheet->mergeCells('B2:H2')->setCellValue('B2', '(10/2020)');

            $sheet->getCell('B3')
                ->setValue('Company Name')
                ->getStyle()->getFont()->setBold(true)->setSize(13);
            $sheet->getCell('C3')
                ->setValue('Cong ty TNHH Persol Viet Nam 2')
                ->getStyle()->getFont()->setSize(13);

            $cell = $sheet->getCell('B1');

            $styles = [
                'font' => [
                    'bold' => true,
                    'size' => 20,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => '',
                    'textRotation' => 90,
                    'wrapText' => true,
                    'shrinkToFit' => true,
                    'indent' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'top' => '',
                        'bottom' => '',
                        'left' => '',
                        'right' => '',
                    ],
                    'top' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'diagonal' => [

                    ],
                    'vertical' => '',
                    'horizontal' => '',
                    'diagonalDirection' => '',
                    'outline' => [
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => [
                            'argb' => 'FFFF0000',
                        ],
                    ]
                ],
                'border' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'argb' => 'FFFF0000',
                    ],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFFFF'
                    ]
                ],
                'numberFormat' => [
                    'formatCode' => '',
                ],
                'protection' => [
                    'locked' => true,
                    'hidden' => true,
                ]
            ];

            $cell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $cell->getStyle()->getFont()->setSize(20)->setBold(true);
            $cell->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(Color::COLOR_DARKYELLOW);

            $headerStyle = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
            ];
            $cellStyle = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ]
                ],
                'font' => [
                    'size' => 10,
                ],
            ];
            $borders = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ]
                ],
            ];
            $bgColor = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'c4c4c4ff'] // https://jsfiddle.net/Mottie/don375nj/
                ]
            ];
            $bgColorLabel = [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => '0e40e1ff']
                ]
            ];
            $aligment = [
                'vertical_center' => [
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                ],
                'horizontal_center' => [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]
            ];
            $firstColumn = 'B';
            $lastColumn = 'I';
            $firstRow = 8;
            $lastRow = 13;

            $blocks = [
                [
                    'label' => [
                        'row' => 7
                    ],
                    'header' => [
                        'row' => 8
                    ],
                    'body' => [
                        'first_row' => 9,
                        'last_row' => 13
                    ]
                ],
                [
                    'label' => [
                        'row' => 15
                    ],
                    'header' => [
                        'row' => 16
                    ],
                    'body' => [
                        'first_row' => 17,
                        'last_row' => 21
                    ],
                ],
            ];

            $heightRows = [
                '2' => 22,
                '3' => 24
            ];

            foreach ($blocks as $block) {
                // set style for label
                $rowLabel = $block['label']['row'];
                $rowHeader = $block['header']['row'];
                $sheet->getStyle("B{$rowLabel}:I{$rowLabel}")->applyFromArray($bgColorLabel);
                // set style for header
                $sheet->getStyle("B{$rowHeader}:I{$rowHeader}")->applyFromArray(array_merge($bgColor, $borders));
                // set style for body
                $sheet->getStyle("B{$block['body']['first_row']}:I{$block['body']['last_row']}")->applyFromArray($cellStyle);
            }

            foreach ($heightRows as $row => $value) {
                $sheet->getRowDimension($row)->setRowHeight($value);
            }

            $sheet->getColumnDimension('B')->setAutoSize(true);
            // set background color for a range
            //$sheet->getStyle("B8:I8")->applyFromArray($bgColor);
            // $sheet->getStyle("B7:I7")->applyFromArray($bgColorLabel);
            // set style for a range
            // $sheet->getStyle("{$firstColumn}{$firstRow}:{$lastColumn}{$lastRow}")->applyFromArray($cellStyle);
            // set height for a row
            //$sheet->getRowDimension('3')->setRowHeight(23);
            // automatically turns on "wrap text" for the cell when it sees a newline character
            Cell::setValueBinder(new AdvancedValueBinder());
            // add new line character to text
            $sheet->getCell('I9')->setValue("Hello fsdgs sdgsdgs sgsgsd sdsdfsfdsfsd\nWorld");
        };
        // return [
        //     // Style the first row as bold text.
        //     5    => ['font' => ['bold' => true]],

        //     // Styling a specific cell by coordinate.
        //     'B7' => ['font' => ['italic' => true]],

        //     // Styling an entire column.
        //     'C'  => ['font' => ['size' => 16]],
        // ];
    }

}
