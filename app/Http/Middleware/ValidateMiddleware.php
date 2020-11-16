<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;
use App\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ValidateMiddleware
{
    use ApiResponse;

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
                return $this->responseError($validator->errors());
            }
            if ($request->ajax()) {
                return $this->responseError($validator->errors(), 422);
            }
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        return $next($request);
    }
}
