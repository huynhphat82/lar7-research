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
        return is_callable($stylesSheet) ? $stylesSheet() : $stylesSheet;
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
}
