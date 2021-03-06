<?php

namespace App\Exports;

use App\Services\FileFormat;

class Export
{
    /**
     * startCell
     *
     * @var string
     */
    protected $startCell = 'A1';

    /**
     * sheetName
     *
     * @var string
     */
    protected $sheetName = 'Sheet1';

    /**
     * styles
     *
     * @var array
     */
    protected $styles = [];

    /**
     * writeType
     *
     * @var string
     */
    protected $writeType = FileFormat::XLSX;

    /**
     * Define arguments in contructor function in order
     *
     * @var array
     */
    protected $propsName = ['startCell', 'sheetName'];

    /**
     * beforeEvents
     *
     * @var array
     */
    protected $beforeEvents = [];

    /**
     * afterEvents
     *
     * @var array
     */
    protected $afterEvents = [];

    /**
     * _resolveProps
     *
     * @param  array $args
     * @return void
     */
    protected function resolvePropNames(array $args = [])
    {
        foreach($args as $k => $v) {
            if ($v && array_key_exists($k, $this->propsName)) {
                $this->{$this->propsName[$k]} = $v;
            }
        }
    }

    /**
     * startCell
     *
     * @return string
     */
    public function startCell(): string
    {
        return $this->startCell;
    }

    /**
     * withTitle
     *
     * @return string
     */
    public function title(): string
    {
        return $this->sheetName;
    }

    /**
     * @implement
     *
     * styles
     *
     * @param  mixed $sheet
     * @return void
     */
    public function styles($sheet)
    {
        $stylesSheet = $this->styles;
        return is_callable($stylesSheet) ? $stylesSheet($sheet) : $stylesSheet;
    }

    /**
     * registerEvents
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return array_merge(
            array_merge($this->_defaultBeforeEvents(), $this->beforeEvents),
            array_merge($this->_defaultAfterEvents(), $this->afterEvents),
        );
    }

    /**
     * _defaultBeforeEvents
     *
     * @return void
     */
    private function _defaultBeforeEvents()
    {
        return [
            'BeforeExport' => function ($event) {

            },
            'BeforeWriting' => function ($event) {

            },
            'BeforeSheet' => function ($event) {

            },
            'BeforeImport' => function ($event) {

            },
        ];
    }

    /**
     * _defaultAfterEvents
     *
     * @return void
     */
    private function _defaultAfterEvents()
    {
        return [
            'AfterImport' => function ($event) {

            },
            'AfterSheet' => function ($event) {

            },
        ];
    }

    public function data()
    {
        return [
            'columns' => [
                'order_id',
                'subtotal',
                'shipping_fee',
                'discount',
                'rewards',
                'total',
                'notes'
            ],
            'rows' => [
                'Q1' => [
                    ['Restaurant in Q1 (CQ10001)'],
                    ['No.', 'OrderID', 'Subtotal', 'Fee', 'Discount', 'Rewards', 'Total', 'Notes'],
                    [1, '000001', '200,000.00', '10,000.00', '0.00	    ', '5,000.00', '195,000.00'],
                    [2, '000002', '300,000.00', '10,000.00', '0.00	    ', '0.00	', '290,000.00'],
                    [3, '000003', '500,000.00', '15,000.00', '5,000.00 ', '0.00	', '490,000.00'],
                    [4, '000004', '450,000.00', '20,000.00', '5,000.00 ', '0.00	', '435,000.00'],
                    [5, '000005', '100,000.00', '14,000.00', '10,000.00', '7,000.00', '103,000.00'],
                    [null],
                ],
                'Q2' => [
                    ['Restaurant in Q2 (CQ20001)'],
                    ['No.', 'OrderID', 'Subtotal', 'Fee', 'Discount', 'Rewards', 'Total', 'Notes'],
                    [1, '000001', '200,000.00', '10,000.00', '0.00	    ', '5,000.00', '195,000.00'],
                    [2, '000002', '300,000.00', '10,000.00', '0.00	    ', '0.00	', '290,000.00'],
                    [3, '000003', '500,000.00', '15,000.00', '5,000.00 ', '0.00	', '490,000.00'],
                    [4, '000004', '450,000.00', '20,000.00', '5,000.00 ', '0.00	', '435,000.00'],
                    [5, '000005', '100,000.00', '14,000.00', '10,000.00', '7,000.00', '103,000.00'],
                    [null],
                ],
            ],
        ];
    }
}
