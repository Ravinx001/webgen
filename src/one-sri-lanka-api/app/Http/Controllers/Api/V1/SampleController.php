<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/welcome",
     *     summary="Return a welcome message for the API",
     *     tags={"Sample"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Welcome to the Sample API Controller!")
     *         )
     *     )
     * )
     */
    public function welcome()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Welcome to the Sample API Controller!'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/echo",
     *     summary="Echo back the data sent in the request",
     *     tags={"Sample"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload to echo",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"foo":"bar","number":123}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Echoed data with status",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="received_data", type="object")
     *         )
     *     )
     * )
     */
    public function echoRequest(Request $request)
    {
        $data = $request->all();
        return response()->json([
            'status' => 'success',
            'received_data' => $data
        ]);
    }
}
