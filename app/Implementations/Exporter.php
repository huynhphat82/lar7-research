<?php

namespace App\Implementations;

use App\Services\FileFormat;

class Exporter
{
    protected $filename = 'download';
    protected $extension = '.xlsx';
    protected $writeType = FileFormat::XLSX;

    /**
     * filename
     *
     * @param  string|null $filename
     * @return string
     */
    protected function filename($filename = null)
    {
        return $filename ?: $this->filename.$this->extension;
    }

    /**
     * filename
     *
     * @param  string|null $writeType
     * @return string
     */
    protected function writeType($writeType = null)
    {
        return $writeType ?: $this->writeType;
    }
}
