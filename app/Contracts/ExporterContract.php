<?php
namespace App\Contracts;

interface ExporterContract
{
    /**
     * download
     *
     * @param  mixed $data
     * @param  string|null $filename
     * @param  string|null $writeType
     * @return void
     */
    public function download($data, $filename = null, $writeType = null);

    /**
     * store
     *
     * @param  mixed $data
     * @param  string|null $filename
     * @param  string|null $writeType
     * @param  array $config
     * @return void
     */
    public function store($data, $filename = null, $writeType = null, $config = []);
}

