<?php

use App\Facades\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle all unhandled exceptions for API routes
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                // Log server errors
                if (shouldLogException($e)) {
                    report($e);
                }

                // Handle validation exceptions
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return ApiResponse::error(
                        'Validation failed',
                        422,
                        $e->errors()
                    );
                }

                // Handle authentication exceptions
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return ApiResponse::error('Unauthenticated', 401);
                }

                // Handle authorization exceptions
                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return ApiResponse::error('Forbidden', 403);
                }

                // Handle not found exceptions
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return ApiResponse::error('Resource not found', 404);
                }

                // Handle model not found exceptions
                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return ApiResponse::error('Resource not found', 404);
                }

                // Handle method not allowed exceptions
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    return ApiResponse::error('Method not allowed', 405);
                }

                // Handle throttle exceptions
                if ($e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
                    return ApiResponse::error('Too many requests', 429);
                }

                // Handle database connection exceptions
                if ($e instanceof \Illuminate\Database\QueryException) {
                    $message = app()->environment('production')
                        ? 'Database error occurred'
                        : $e->getMessage();
                    return ApiResponse::error($message, 500);
                }

                // Handle token mismatch exceptions
                if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                    return ApiResponse::error('CSRF token mismatch', 419);
                }

                // Handle timeout exceptions
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $e->getStatusCode() === 408) {
                    return ApiResponse::error('Request timeout', 408);
                }

                // Default fallback for all other exceptions
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                $message = getErrorMessage($e, $statusCode);

                return ApiResponse::error($message, $statusCode);
            }
        });
    })
    ->create();

/**
 * Helper function to determine if exception should be logged
 */
function shouldLogException(Throwable $e): bool
{
    // Don't log client errors (4xx)
    if (method_exists($e, 'getStatusCode')) {
        $statusCode = $e->getStatusCode();
        if ($statusCode >= 400 && $statusCode < 500) {
            return false;
        }
    }

    // Don't log validation errors
    if ($e instanceof \Illuminate\Validation\ValidationException) {
        return false;
    }

    // Don't log auth errors
    if ($e instanceof \Illuminate\Auth\AuthenticationException ||
        $e instanceof \Illuminate\Auth\Access\AuthorizationException) {
        return false;
    }

    return true;
}

/**
 * Helper function to get appropriate error message
 */
function getErrorMessage(Throwable $e, int $statusCode): string
{
    // In production, don't expose internal error details
    if (app()->environment('production')) {
        return match ($statusCode) {
            400 => 'Bad request',
            401 => 'Unauthenticated',
            403 => 'Forbidden',
            404 => 'Not found',
            405 => 'Method not allowed',
            408 => 'Request timeout',
            409 => 'Conflict',
            419 => 'CSRF token mismatch',
            422 => 'Unprocessable entity',
            429 => 'Too many requests',
            500 => 'Internal server error',
            501 => 'Not implemented',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
            504 => 'Gateway timeout',
            default => 'An error occurred',
        };
    }

    // In development, show the actual error message
    return $e->getMessage() ?: 'An error occurred';
}
