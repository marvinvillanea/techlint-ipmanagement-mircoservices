<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success(
        $data = null,
        string $message = 'Success',
        int $status = 200
    ): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error(
        string $message = 'Error',
        $data = null,
        int $status = 400
    ): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data
        ], $status);
    }

}
