<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class ApiCallerService
{
    /**
     * Default timeout for HTTP requests (in seconds)
     */
    protected int $timeout = 30;

    /**
     * Default retry attempts
     */
    protected int $retryAttempts = 3;

    /**
     * Default retry delay (in milliseconds)
     */
    protected int $retryDelay = 1000;

    public function get($url, $params = [], $headers = [])
    {
        try {
            $response = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->retry($this->retryAttempts, $this->retryDelay)
                ->get($url, $params);

            $response->throw();

            // Log successful requests in non-production environments
            $this->logRequest('GET', $url, $params, $headers, $response);

            return $response->json();
        } catch (RequestException $e) {
            // Log the error (respecting environment)
            $this->logError('GET', $url, $params, $headers, $e);

            return $this->buildErrorResponse($e);
        }
    }

    public function post($url, $data = [], $headers = [])
    {
        try {
            $response = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->retry($this->retryAttempts, $this->retryDelay)
                ->post($url, $data);

            $response->throw();

            // Log successful requests in non-production environments
            $this->logRequest('POST', $url, $data, $headers, $response);

            return $response->json();
        } catch (RequestException $e) {
            // Log the error (respecting environment)
            $this->logError('POST', $url, $data, $headers, $e);

            return $this->buildErrorResponse($e);
        }
    }

    public function put($url, $data = [], $headers = [])
    {
        try {
            $response = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->retry($this->retryAttempts, $this->retryDelay)
                ->put($url, $data);

            $response->throw();

            // Log successful requests in non-production environments
            $this->logRequest('PUT', $url, $data, $headers, $response);

            return $response->json();
        } catch (RequestException $e) {
            // Log the error (respecting environment)
            $this->logError('PUT', $url, $data, $headers, $e);

            return $this->buildErrorResponse($e);
        }
    }

    public function patch($url, $data = [], $headers = [])
    {
        try {
            $response = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->retry($this->retryAttempts, $this->retryDelay)
                ->patch($url, $data);

            $response->throw();

            // Log successful requests in non-production environments
            $this->logRequest('PATCH', $url, $data, $headers, $response);

            return $response->json();
        } catch (RequestException $e) {
            // Log the error (respecting environment)
            $this->logError('PATCH', $url, $data, $headers, $e);

            return $this->buildErrorResponse($e);
        }
    }

    public function delete($url, $headers = [])
    {
        try {
            $response = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->retry($this->retryAttempts, $this->retryDelay)
                ->delete($url);

            $response->throw();

            // Log successful requests in non-production environments
            $this->logRequest('DELETE', $url, [], $headers, $response);

            return $response->json();
        } catch (RequestException $e) {
            // Log the error (respecting environment)
            $this->logError('DELETE', $url, [], $headers, $e);

            return $this->buildErrorResponse($e);
        }
    }

    /**
     * Upload files via POST request
     */
    public function uploadFile($url, $files = [], $data = [], $headers = [])
    {
        try {
            $http = Http::withHeaders($headers)
                ->timeout($this->timeout * 2) // Double timeout for file uploads
                ->retry($this->retryAttempts, $this->retryDelay);

            // Attach files
            foreach ($files as $key => $file) {
                if (is_string($file)) {
                    $http->attach($key, file_get_contents($file), basename($file));
                } else {
                    $http->attach($key, $file);
                }
            }

            $response = $http->post($url, $data);
            $response->throw();

            // Log successful requests in non-production environments
            $this->logRequest('POST (FILE UPLOAD)', $url, array_merge($data, ['files' => array_keys($files)]), $headers, $response);

            return $response->json();
        } catch (RequestException $e) {
            // Log the error (respecting environment)
            $this->logError('POST (FILE UPLOAD)', $url, array_merge($data, ['files' => array_keys($files)]), $headers, $e);

            return $this->buildErrorResponse($e);
        }
    }

    /**
     * Make request with bearer token
     */
    public function withToken($token)
    {
        return new class($token, $this) {
            private $token;
            private $service;

            public function __construct($token, $service)
            {
                $this->token = $token;
                $this->service = $service;
            }

            public function get($url, $params = [])
            {
                return $this->service->get($url, $params, ['Authorization' => 'Bearer ' . $this->token]);
            }

            public function post($url, $data = [])
            {
                return $this->service->post($url, $data, ['Authorization' => 'Bearer ' . $this->token]);
            }

            public function put($url, $data = [])
            {
                return $this->service->put($url, $data, ['Authorization' => 'Bearer ' . $this->token]);
            }

            public function patch($url, $data = [])
            {
                return $this->service->patch($url, $data, ['Authorization' => 'Bearer ' . $this->token]);
            }

            public function delete($url)
            {
                return $this->service->delete($url, ['Authorization' => 'Bearer ' . $this->token]);
            }
        };
    }

    /**
     * Make request with basic auth
     */
    public function withBasicAuth($username, $password)
    {
        return new class($username, $password, $this) {
            private $username;
            private $password;
            private $service;

            public function __construct($username, $password, $service)
            {
                $this->username = $username;
                $this->password = $password;
                $this->service = $service;
            }

            public function get($url, $params = [])
            {
                return $this->service->get($url, $params, ['Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)]);
            }

            public function post($url, $data = [])
            {
                return $this->service->post($url, $data, ['Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)]);
            }

            public function put($url, $data = [])
            {
                return $this->service->put($url, $data, ['Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)]);
            }

            public function patch($url, $data = [])
            {
                return $this->service->patch($url, $data, ['Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)]);
            }

            public function delete($url)
            {
                return $this->service->delete($url, ['Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)]);
            }
        };
    }

    /**
     * Set request timeout
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Set retry configuration
     */
    public function setRetry(int $attempts, int $delay = 1000): self
    {
        $this->retryAttempts = $attempts;
        $this->retryDelay = $delay;
        return $this;
    }

    /**
     * Log successful requests (only in non-production environments)
     */
    private function logRequest(string $method, string $url, array $data, array $headers, $response): void
    {
        if (!app()->environment('production')) {
            $executionTime = null;
            if (method_exists($response, 'transferStats') && $response->transferStats) {
                $executionTime = $response->transferStats->getTransferTime() . 's';
            }

            Log::channel('api_calls')->info('API Request Success', [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'user' => 'Ravinx-SLIIT', // Using your current login
                'method' => $method,
                'url' => $this->sanitizeUrl($url),
                'request_data' => $this->sanitizeLogData($data),
                'request_headers' => $this->sanitizeHeaders($headers),
                'response_status' => $response->status(),
                'response_size' => strlen($response->body()) . ' bytes',
                'execution_time' => $executionTime,
                'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
            ]);
        }
    }

    /**
     * Log errors (respecting environment settings)
     */
    private function logError(string $method, string $url, array $data, array $headers, RequestException $e): void
    {
        $logLevel = app()->environment('production') ? 'error' : 'warning';

        $logData = [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'user' => 'Ravinx-SLIIT', // Using your current login
            'method' => $method,
            'url' => $this->sanitizeUrl($url),
            'error_message' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'response_status' => optional($e->response)->status(),
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'MB',
        ];

        // Add detailed information only in non-production environments
        if (!app()->environment('production')) {
            $logData = array_merge($logData, [
                'request_data' => $this->sanitizeLogData($data),
                'request_headers' => $this->sanitizeHeaders($headers),
                'response_body' => optional($e->response)->json() ?? optional($e->response)->body(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
        }

        Log::channel('api_calls')->{$logLevel}('API Request Failed', $logData);
    }

    /**
     * Build standardized error response
     */
    private function buildErrorResponse(RequestException $e): array
    {
        $response = [
            'status' => 'error',
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];

        // Add response details if available
        if ($e->response) {
            $response['http_status'] = $e->response->status();

            $response['response_body'] = $e->response->json() ?? $e->response->body();
        }

        return $response;
    }

    /**
     * Sanitize URL (remove sensitive query parameters)
     */
    private function sanitizeUrl(string $url): string
    {
        $sensitiveParams = ['api_key', 'token', 'password', 'secret', 'key'];

        $parsedUrl = parse_url($url);
        if (!isset($parsedUrl['query'])) {
            return $url;
        }

        parse_str($parsedUrl['query'], $queryParams);

        foreach ($sensitiveParams as $param) {
            if (isset($queryParams[$param])) {
                $queryParams[$param] = '[REDACTED]';
            }
        }

        $parsedUrl['query'] = http_build_query($queryParams);

        return $this->buildUrl($parsedUrl);
    }

    /**
     * Build URL from parsed components
     */
    private function buildUrl(array $parsedUrl): string
    {
        $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
        $host = $parsedUrl['host'] ?? '';
        $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
        $user = $parsedUrl['user'] ?? '';
        $pass = isset($parsedUrl['pass']) ? ':' . $parsedUrl['pass']  : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = $parsedUrl['path'] ?? '';
        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * Sanitize sensitive data from logs
     */
    private function sanitizeLogData(array $data): array
    {
        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'token',
            'api_key',
            'secret',
            'client_secret',
            'private_key',
            'credit_card',
            'ssn',
            'social_security',
            'access_token',
            'refresh_token',
            'auth_token',
            'session_id'
        ];

        return $this->recursiveSanitize($data, $sensitiveKeys);
    }

    /**
     * Sanitize sensitive headers from logs
     */
    private function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = [
            'Authorization',
            'X-API-Key',
            'X-Auth-Token',
            'Cookie',
            'Set-Cookie',
            'X-Session-Token',
            'X-Access-Token',
            'Bearer'
        ];

        foreach ($sensitiveHeaders as $header) {
            // Check for exact match and case-insensitive match
            foreach ($headers as $key => $value) {
                if (strtolower($key) === strtolower($header)) {
                    $headers[$key] = '[REDACTED]';
                }
            }
        }

        return $headers;
    }

    /**
     * Recursively sanitize array data
     */
    private function recursiveSanitize(array $data, array $sensitiveKeys): array
    {
        foreach ($data as $key => $value) {
            if (in_array(strtolower($key), array_map('strtolower', $sensitiveKeys))) {
                $data[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $data[$key] = $this->recursiveSanitize($value, $sensitiveKeys);
            }
        }

        return $data;
    }

    /**
     * Get request statistics
     */
    public function getStats(): array
    {
        return [
            'total_requests' => 0,
            'failed_requests' => 0,
            'average_response_time' => 0,
            'last_request_time' => null,
            'service_version' => '1.0.0',
            'current_user' => 'Ravinx-SLIIT',
        ];
    }
}
