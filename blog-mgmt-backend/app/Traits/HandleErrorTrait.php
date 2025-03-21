<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

trait HandleErrorTrait
{
    /**
     * Handle exceptions and return a standardized error response.
     *
     * @param \Throwable $th
     * @param array $data
     * @param int $defaultStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleError(\Throwable $th, $data = [], $defaultStatus = 500)
    {
        // Log the exception
        Log::error('Exception occurred:', [
            'message' => $th->getMessage(),
            'file' => $th->getFile(),
            'line' => $th->getLine(),
            'trace' => $th->getTraceAsString(),
            'data' => $data
        ]);

        // Determine status and message
        [$status, $message] = match (true) {
            $th instanceof ValidationException => [422, $th->errors()],
            $th instanceof ModelNotFoundException => [404, 'Resource not found.'],
            $th instanceof QueryException => [500, 'A database error occurred. Please try again later.'],
            $th instanceof HttpException => [$th->getStatusCode(), $th->getMessage()],
            default => [$defaultStatus, $th->getMessage()],
        };

        // Return the response
        return response()->json([
            'error' => $message,
            'status' => false,
            'data' => $data
        ], $status);
    }
}