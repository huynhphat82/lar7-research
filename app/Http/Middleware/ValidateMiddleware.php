<?php

namespace App\Http\Middleware;

use Closure;
use App\Validation\Validator;

class ValidateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If it is APIs, validate automatically
        if (isApi()) {
            Validator::autovalidate();
        }

        return $next($request);
    }
}
