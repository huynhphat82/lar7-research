<?php
namespace App\Implementations;

use Maatwebsite\Excel\Excel;
use App\Contracts\ExporterContract;

class ExporterExcel extends Exporter implements ExporterContract
{
    private $exporter;

    /**
     * __construct
     *
     * @param  \Maatwebsite\Excel\Facades\Excel $exporter
     * @return void
     */
    public function __construct(Excel $exporter)
    {
        $this->exporter = $exporter;
    }

    /**
     * download
     *
     * @param  mixed $data
     * @param  string|null $filename
     * @param  string|null $writeType
     * @return void
     */
    public function download($data, $filename = null, $writeType = null)
    {
        return $this->exporter->download($data, $this->filename($filename), $this->writeType($writeType));
    }

    /**
     * store
     *
     * @param  mixed $data
     * @param  string|null $filename
     * @param  string|null $writeType
     * @param  array $config
     * @return void
     */
    public function store($data, $filename = null, $writeType = null, $config = [])
    {
        $disk = $config['disk'] ?? null;
        return $this->exporter->store($data, $this->filename($filename), $disk, $this->writeType($writeType));
    }

}

