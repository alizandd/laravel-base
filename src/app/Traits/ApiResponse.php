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
            $response['data'] = $errors;
        }

        return response()->json($response, $code);
    }

    protected function paginate($paginatedData, $message = null, $code = Response::HTTP_OK)
    {
        $paginationDetails = [
            'total' => $paginatedData->total(),
            'perPage' => $paginatedData->perPage(),
            'currentPage' => $paginatedData->currentPage(),
            'lastPage' => $paginatedData->lastPage(),
            'nextPageUrl' => $paginatedData->nextPageUrl(),
            'prevPageUrl' => $paginatedData->previousPageUrl(),
        ];

        $data = [
            'items' => $paginatedData->items(),
            'pagination' => $paginationDetails,
        ];
        return self::success($data,$message,$code);
    }
}
