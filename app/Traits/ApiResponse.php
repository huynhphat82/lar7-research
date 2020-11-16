<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Response with success status
     *
     * @param  mixed $data
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($data, $status = 200)
    {
        return $this->json(true, $data, $status);
    }

    /**
     * Response with error status
     *
     * @param  mixed $message
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError($message, $status = 400)
    {
        return $this->json(false, $message, $status);
    }

    /**
     * Response json
     *
     * @param  bool $success
     * @param  mixed $data
     * @param  int $status
     * @return \Illuminate\Http\JsonResponse
     */
    private function json($success, $data, $status = 200)
    {
        return response()->json([
            'success' => $success,
            ($success ? 'data' : 'error') => $data,
            'message_type' => $this->messageTypes($status),
        ], $status);
    }

    /**
     * Get message type
     *
     * @param  int $status
     * @return string
     */
    private function messageTypes($status = 200)
    {
        if ($status < 300) {
            return 'SUCCESS';
        }
        if ($status < 400) {
            return 'WARNING';
        }
        return 'ERROR';
    }
}
