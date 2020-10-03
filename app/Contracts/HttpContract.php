<?php
namespace App\Contracts;

interface HttpContract
{
    public function request($config = []);
}
