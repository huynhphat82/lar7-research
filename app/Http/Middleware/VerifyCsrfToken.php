<?php

namespace App\Http\Middleware;

use Constant;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        Constant::PREFIX_API.'*',
        'auth*'
    ];
}
