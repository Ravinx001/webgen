<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Carbon\Carbon;

class ApiHelper
{
    /**
     * Supported HTTP methods
     */
    const SUPPORTED_METHODS = ['get', 'post', 'put', 'patch', 'delete'];

    /**
     * Default configuration for API calls.
     */
    const DEFAULT_CONFIG = [
        'timeout' => 10,
        'retries' => 3,
        'retry_delay' => 100,
        'log_requests' => true,
        'verify_ssl' => true,
        'headers' => [],
        'as_form' => false,
        'as_json' => true,
    ];

    /**
     * Call an external API with a fluent, configurable interface.
     *
     * @param string $method HTTP method (e.g., 'get', 'post', 'put').
     * @param string $url Full API URL.
     * @param array $data Request data (query parameters for GET, body for others).
     * @param array $options Optional configuration to override defaults.
     * @return array A structured response array containing success/error status, data, and metadata.
     */
    public static function callApi(string $method, string $url, array $data = [], array $options = []): array
    {
        try {
            // Merge options with defaults to create a final configuration
            $config = array_merge(self::DEFAULT_CONFIG, $options);

            // Validate and normalize the HTTP method
            $method = strtolower(trim($method));
            if (!self::isValidMethod($method)) {
                return self::errorResponse('INVALID_METHOD', "Unsupported HTTP method: {$method}", 405);
            }

            // Validate the URL format
            if (!self::isValidUrl($url)) {
                return self::errorResponse('INVALID_URL', "Invalid URL provided: {$url}", 400);
            }

            // Log the request details if enabled
            if ($config['log_requests']) {
                self::logRequest($method, $url, $data, $config);
            }

            // Make the actual HTTP request using a helper method
            $response = self::makeHttpRequest($method, $url, $data, $config);

            // Process the response and return the structured result
            return self::processResponse($response, $config);
        } catch (ConnectionException $e) {
            return self::handleConnectionException($e, $config);
        } catch (RequestException $e) {
            return self::handleRequestException($e, $config);
        } catch (\Exception $e) {
            // Catch any other unexpected exceptions
            return self::handleGeneralException($e, $config);
        }
    }

    /**
     * Make HTTP GET request
     */
    public static function get(string $url, array $params = [], array $options = []): array
    {
        return self::callApi('GET', $url, $params, $options);
    }

    /**
     * Make HTTP POST request
     */
    public static function post(string $url, array $data = [], array $options = []): array
    {
        return self::callApi('POST', $url, $data, $options);
    }

    /**
     * Make HTTP PUT request
     */
    public static function put(string $url, array $data = [], array $options = []): array
    {
        return self::callApi('PUT', $url, $data, $options);
    }

    /**
     * Make HTTP PATCH request
     */
    public static function patch(string $url, array $data = [], array $options = []): array
    {
        return self::callApi('PATCH', $url, $data, $options);
    }

    /**
     * Make HTTP DELETE request
     */
    public static function delete(string $url, array $data = [], array $options = []): array
    {
        return self::callApi('DELETE', $url, $data, $options);
    }

    /**
     * Make authenticated API call by adding an Authorization header.
     */
    public static function authenticatedCall(
        string $method,
        string $url,
        array $data = [],
        string $token = '',
        string $tokenType = 'Bearer',
        array $options = []
    ): array {
        if (!empty($token)) {
            $options['headers']['Authorization'] = "{$tokenType} {$token}";
        }

        return self::callApi($method, $url, $data, $options);
    }

    /**
     * Make multiple API calls in parallel.
     *
     * @param array $requests An array of request arrays, each with 'method', 'url', 'data', and 'options'.
     * @return array An array of structured responses, keyed by the original request key.
     */
    public static function parallelCalls(array $requests): array
    {
        $responses = [];

        $pool = Http::pool(function ($pool) use ($requests) {
            $promises = [];
            foreach ($requests as $key => $request) {
                // Merge default config with request-specific options
                $config = array_merge(self::DEFAULT_CONFIG, $request['options'] ?? []);

                // Create a base HTTP client instance with common options
                $client = Http::withHeaders($config['headers'])
                    ->timeout($config['timeout'])
                    ->retry($config['retries'], $config['retry_delay']);

                // Handle SSL verification if disabled
                if (!$config['verify_ssl']) {
                    $client->withoutVerifying();
                }

                // Handle body format (JSON vs. form)
                if ($config['as_form']) {
                    $client->asForm();
                } elseif ($config['as_json']) {
                    $client->asJson();
                }

                // Add the request promise to the pool
                $promises[$key] = $pool->{$request['method']}($request['url'], $request['data'] ?? []);
            }
            return $promises;
        });

        // Process each response from the pool
        foreach ($pool as $key => $response) {
            try {
                // Each item in the pool is a Laravel Response object
                $responses[$key] = self::processResponse($response, self::DEFAULT_CONFIG);
            } catch (\Exception $e) {
                // Catch any exceptions during processing and provide a structured error
                $responses[$key] = self::handleGeneralException($e, self::DEFAULT_CONFIG);
            }
        }

        return $responses;
    }

    /**
     * Validate HTTP method against a list of supported methods.
     */
    private static function isValidMethod(string $method): bool
    {
        return in_array($method, self::SUPPORTED_METHODS);
    }

    /**
     * Validate URL format using a built-in filter.
     */
    private static function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Log the API request details for debugging.
     */
    private static function logRequest(string $method, string $url, array $data, array $config): void
    {
        // Intersect key keeps only the specified keys from the config array for logging
        $logConfig = array_intersect_key($config, array_flip(['timeout', 'retries']));

        Log::info("API Request", [
            'method' => strtoupper($method),
            'url' => $url,
            'data' => $data,
            'config' => $logConfig
        ]);
    }

    /**
     * Make the actual HTTP request using the configured client instance.
     */
    private static function makeHttpRequest(string $method, string $url, array $data, array $config): Response
    {
        $request = Http::withHeaders($config['headers'])
            ->timeout($config['timeout'])
            ->retry($config['retries'], $config['retry_delay']);

        if ($config['as_form']) {
            $request->asForm();
        } elseif ($config['as_json']) {
            $request->asJson();
        }

        // Add SSL verification setting
        if (!$config['verify_ssl']) {
            $request = $request->withoutVerifying();
        }

        // Execute request based on method
        return $request->$method($url, $data);
    }

    /**
     * Process the raw HTTP response object into a structured array.
     */
    private static function processResponse(Response $response, array $config): array
    {
        $statusCode = $response->status();
        $responseHeaders = $response->headers();

        // Try to decode JSON, otherwise return raw body
        $decodedData = $response->successful() ? $response->json() : $response->json() ?? $response->body();

        // Log failed requests for debugging
        if ($config['log_requests'] && !$response->successful()) {
            Log::warning("API Request Failed", [
                'url' => $response->effectiveUri(),
                'status_code' => $statusCode,
                'response_body' => $response->body(),
                'headers' => $responseHeaders
            ]);
        }

        // Return a structured success or error response
        if ($response->successful()) {
            return self::successResponse($decodedData, $statusCode, $responseHeaders);
        } else {
            $errorMessage = self::extractErrorMessage($decodedData, $statusCode);
            return self::errorResponse('HTTP_ERROR', $errorMessage, $statusCode, $decodedData);
        }
    }

    /**
     * Extract a meaningful error message from the API response body.
     */
    private static function extractErrorMessage($response, int $statusCode): string
    {
        if (is_array($response)) {
            // Check common keys for an error message
            $errorFields = ['message', 'error', 'error_description', 'detail'];
            foreach ($errorFields as $field) {
                if (isset($response[$field])) {
                    return $response[$field];
                }
            }
        } elseif (is_string($response)) {
            // If the response is a string, use it as the message
            return $response;
        }

        // Fallback to a generic message if no specific error found
        return self::getGenericErrorMessage($statusCode);
    }

    /**
     * Get a generic error message based on the HTTP status code.
     */
    private static function getGenericErrorMessage(int $statusCode): string
    {
        $statusMessages = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout'
        ];

        return $statusMessages[$statusCode] ?? "HTTP Error {$statusCode}";
    }

    /**
     * Handle ConnectionException and return a structured error response.
     */
    private static function handleConnectionException(ConnectionException $e, array $config): array
    {
        if ($config['log_requests']) {
            Log::error("API Connection Error", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }

        return self::errorResponse(
            'CONNECTION_ERROR',
            'Connection failed: ' . $e->getMessage(),
            0
        );
    }

    /**
     * Handle RequestException and return a structured error response.
     */
    private static function handleRequestException(RequestException $e, array $config): array
    {
        $statusCode = $e->response ? $e->response->status() : 500;
        $responseData = $e->response ? $e->response->json() : null;

        if ($config['log_requests']) {
            Log::error("API Request Exception", [
                'error' => $e->getMessage(),
                'status_code' => $statusCode,
                'response_body' => $e->response ? $e->response->body() : null
            ]);
        }

        return self::errorResponse(
            'REQUEST_EXCEPTION',
            'Request exception: ' . $e->getMessage(),
            $statusCode,
            $responseData
        );
    }

    /**
     * Handle general exceptions and return a structured error response.
     */
    private static function handleGeneralException(\Exception $e, array $config): array
    {
        if ($config['log_requests']) {
            Log::error("API General Exception", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }

        return self::errorResponse(
            'GENERAL_ERROR',
            'Unexpected error: ' . $e->getMessage(),
            500
        );
    }

    /**
     * Create a structured success response.
     */
    private static function successResponse($data, int $statusCode = 200, array $headers = []): array
    {
        return [
            'success' => true,
            'error' => false,
            'status_code' => $statusCode,
            'message' => 'Request successful',
            'data' => $data,
            'headers' => $headers,
            'timestamp' => Carbon::now()->toISOString()
        ];
    }

    /**
     * Create a structured error response.
     */
    private static function errorResponse(
        string $code,
        string $message,
        int $statusCode = 500,
        $data = null
    ): array {
        return [
            'success' => false,
            'error' => true,
            'error_code' => $code,
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data,
            'timestamp' => Carbon::now()->toISOString()
        ];
    }
}
