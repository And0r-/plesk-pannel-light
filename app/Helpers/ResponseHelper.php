<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ResponseHelper
{
    /**
     * Generate a standardized validation error response.
     *
     * @param array $errors
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function validationError(array $errors, string $message = 'Validation failed', int $statusCode = 422): JsonResponse
    {
        if (config('app.debug')) {
            Log::error($message, [
                'errors' => $errors
            ]);
        }

        return response()->json([
            'error' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Generate a standardized internal server error response.
     *
     * @param string $message
     * @param \Exception $exception
     * @return JsonResponse
     */
    public static function pleskServerError(string $message = 'Internal Server Error', \Exception $exception, int $statusCode = 500): JsonResponse
    {
        if (config('app.debug')) {
            Log::error($message, [
                'plesk_error_id' => $exception->getCode(),
                'plesk_error_message' => $exception->getMessage(),
                'statusCode' => $statusCode,
                'trace' => $exception->getTraceAsString(),
            ]);
        }

        return response()->json([
            'error' => $message,
            'plesk_error_id' => $exception->getCode(),
            'plesk_error_message' => $exception->getMessage(),
        ], $statusCode);
    }

    /**
     * Generate a standardized success response.
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success(string $message, $data = null, int $statusCode = 200): JsonResponse
    {

        if (config('app.debug')) {
            Log::debug($message, [
                'data' => $data,
                'statusCode' => $statusCode,
            ]);
        }

        $response = ['message' => $message];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
}
