<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Success response
     *
     * @param  mixed  $data
     * @param  string $message
     * @param  int    $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    /**
     * Error response
     *
     * @param  string $message
     * @param  int    $code
     * @param  mixed  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse(string $message = 'Error', int $code = 400, $errors = null)
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors
        ], $code);
    }

    /**
     * Paginated response
     *
     * @param  \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Database\Eloquent\Collection  $data
     * @param  string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginatedResponse($data, string $message = 'Success')
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data->items(),
            'meta'    => [
                'current_page' => $data->currentPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
                'last_page'    => $data->lastPage()
            ]
        ]);
    }
}
