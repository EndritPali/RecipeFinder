<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Base API controller for V1 endpoints.
 *
 * Provides common functionality for API controllers, such as error responses.
 *
 * @package App\Http\Controllers\Api\V1
 */
class ApiController extends Controller
{
    /**
     * Create an error response.
     *
     * @param string $message Error message
     * @param int $status HTTP status code
     * @return JsonResponse Error response
     */
    protected function errorResponse(string $message, int $status): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }
}
