<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;

class VerifyAccessToken
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
        if (isApi()) {
            $tokensAllowed = ['44b48f2305bf2680', 'a40d97bfc2ab0e56'];
            if ($request->header('Authorization')) {
                $token = $request->header('Authorization');

                // check token
                if (!in_array($token, $tokensAllowed)) {
                    return $this->responseError('Api token is not valid.');
                }
            }
            return $this->responseError('Api token is missing.');
        }
        return $next($request);
    }
}
