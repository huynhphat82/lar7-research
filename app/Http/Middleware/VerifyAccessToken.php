<?php

namespace App\Http\Middleware;

use App\Services\Facades\Encrypter;
use Closure;
use App\Traits\ApiResponse;
use Constant;

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
            $xApiKey = $this->xApiKey();
            if (empty($xApiKey)) {
                return $this->responseError('Api token is missing.');
            }
            if (!Encrypter::verifyKey($xApiKey)) {
                return $this->responseError('Api token is not valid.');
            }
        }
        return $next($request);
    }

    /**
     * Get X-API-KEY from request
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    private function xApiKey($request = null)
    {
        $request = $request ?: request();
        // X-API-KEY
        if ($request->header(Constant::X_API_TOKEN)) {
            return $request->header(Constant::X_API_TOKEN);
        }
        // X-Api-Key
        $key = str_replace(' ', '-', ucwords(str_replace('-', ' ', strtolower(Constant::X_API_TOKEN))));
        if ($request->header($key)) {
            return $request->header($key);
        }
        // x-api-key
        $key = str_replace(' ', '-', str_replace('-', ' ', strtolower(Constant::X_API_TOKEN)));
        if ($request->header($key)) {
            return $request->header($key);
        }
        return null;
    }
}
