<?php
namespace App\Implementations;

use App\Implementations\Report;

class Reporter
{
    public function __construct(Report ...$reports) {
        $this->reports = $reports;
    }
}
