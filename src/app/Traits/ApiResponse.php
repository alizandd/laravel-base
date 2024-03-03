<?php

namespace App\Traits;
use Illuminate\Http\Response;
trait ApiResponse
{
    protected function success($data, $message = null, $code = Response::HTTP_OK)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error($message, $code, $errors = [])
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
