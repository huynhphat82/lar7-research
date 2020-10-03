<?php
namespace App\Implementations;

use App\Implementations\SpeedReport;
use App\Implementations\MemoryReport;

class Report
{
    public function __construct(
        SpeedReport $speedReport,
        MemoryReport $memoryReport
    ) {
        $this->speedyReport = $speedReport;
        $this->memoryReport = $memoryReport;
    }
}
