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
        $validator = Validator::autovalidate();
        if ($validator !== true) {
            if (isApi()) {
                dd($validator->errors());
            }
            return back()->withErrors($validator)->withInput();
        }

        return $next($request);
    }
}
