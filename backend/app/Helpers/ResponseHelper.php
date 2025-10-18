<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function jsonResponse(bool $success, string $message, mixed $data, string $statusCode): JsonResponse
    {
        return response()->json([
           'success' => $success,
           'message' => $message,
           'data' => $data
        ], $statusCode);
    }
}
