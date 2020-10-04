<?php

namespace App\Services;

use App\Contracts\ExporterContract;
use App\Exports\SaleExport;

class ExporterService
{
    private $expoter;
    private $type = null;

    public function __construct(ExporterContract $exporter)
    {
        $this->expoter = $exporter;
    }

    public function XLSX()
    {
        $this->type = 'XLSX';
        return $this;
    }

    public function PDF()
    {
        $this->type = 'pdf';
        return $this;
    }

    public function sales($filename = null, $writeType = null)
    {
        // return $this->expoter->download(new SaleExport('B5'), $filename ?? 'download.pdf', $writeType ?? FileFormat::MPDF);
        return $this->expoter->download(new SaleExport('B5'), $filename, $writeType);
    }
}
