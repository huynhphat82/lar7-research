<?php
namespace App\Contracts;

interface HttpNewContract extends HttpContract
{
    public function request($config = []);
}
