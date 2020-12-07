<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use App\Services\Facades\AppLog;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);

        AppLog::exception($exception, $this->shouldReport($exception));
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (isApi()) {
            // Missing jwt token from headers
            if ($exception instanceof UnauthorizedHttpException) {
                return $this->responseError($exception->getMessage(), $exception->getStatusCode());
            }
            $statusCode = 422;
            if (method_exists($exception, 'getStatusCode')) {
                $statusCode = $exception->getStatusCode();
            }
            return $this->responseError($exception->getMessage(), $statusCode);
        }
        return parent::render($request, $exception);
    }
}
