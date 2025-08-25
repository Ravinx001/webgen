<?php

namespace App\Services;

use App\Helpers\ApiHelper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class OSLAuthService
{

    /**
     * Authenticate user with external API
     *
     * @param array $credentials User credentials (email, password, etc.)
     * @return array Standardized response array
     */
    public function authenticate(array $credentials): array
    {
        try {

            $validationResult = $this->validateCredentials($credentials);
            if (!$validationResult['valid']) {
                return [
                    'success' => false,
                    'error' => true,
                    'error_code' => 'VALIDATION_ERROR',
                    'message' => 'Invalid credentials provided',
                    'errors' => $validationResult['errors'],
                    'data' => null
                ];
            }

            $options = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30,
                'log_requests' => true
            ];

            $response = ApiHelper::post(
                env('SLUDI_API_URL') . '/authenticate.php',
                $credentials,
                $options
            );
            if (!$response['success']) {
                return [
                    'success' => false,
                    'error' => true,
                    'error_code' => $response['error_code'] ?? 'API_ERROR',
                    'message' => 'Authentication service unavailable: ' . $response['message'],
                    'data' => null
                ];
            }

            return $this->processAuthenticationResponse($response['data']);
        } catch (\Exception $e) {

            Log::error('Authentication Error', [
                'message' => $e->getMessage(),
                'credentials' => array_keys($credentials),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => true,
                'error_code' => 'AUTHENTICATION_EXCEPTION',
                'message' => 'Authentication failed due to system error',
                'data' => null
            ];
        }
    }

    /**
     * Validate authentication credentials
     *
     * @param array $credentials
     * @return array Validation result
     */
    private function validateCredentials(array $credentials): array
    {
        $errors = [];

        $requiredFields = ['user_name', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($credentials[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }

        if (!empty($credentials['user_name'])) {
            $userName = $credentials['user_name'];
            if (strlen($userName) < 6 || strlen($userName) > 32) {
                $errors['user_name'] = 'Username must be between 3 and 45 characters';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $userName)) {
                $errors['user_name'] = 'Username contains invalid characters';
            }
        }

        if (!empty($credentials['password']) && strlen($credentials['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Process authentication response from API
     *
     * @param mixed $responseData
     * @return array Processed response
     */
    private function processAuthenticationResponse($responseData): array
    {
        if (is_string($responseData)) {
            $responseData = json_decode($responseData, true);
        }

        if (isset($responseData['success']) && !$responseData['success']) {
            return [
                'success' => false,
                'error' => true,
                'error_code' => 'AUTHENTICATION_FAILED',
                'message' => $responseData['message'] ?? 'Invalid credentials',
                'data' => null
            ];
        }

        $token = $this->extractToken($responseData);

        if (empty($token)) {
            return [
                'success' => false,
                'error' => true,
                'error_code' => 'TOKEN_NOT_FOUND',
                'message' => 'Authentication response missing token',
                'data' => $responseData
            ];
        }

        $this->storeAuthenticationData($token, $responseData);

        $this->storeTokenPermissionAndRoleDataToInAppUse($token);

        return [
            'success' => true,
            'error' => false,
            'message' => 'Authentication successful',
            'data' => [
                'token' => $token,
                'user' => $responseData['user'] ?? null,
                'expires_at' => $responseData['expires_at'] ?? null,
                'permissions' => $responseData['permissions'] ?? []
            ]
        ];
    }

    /**
     * Extract token from various response formats
     *
     * @param array $responseData
     * @return string|null
     */
    private function extractToken(array $responseData): ?string
    {
        $tokenFields = ['token', 'access_token', 'auth_token', 'api_token'];

        foreach ($tokenFields as $field) {
            if (isset($responseData[$field]) && !empty($responseData[$field])) {
                return $responseData[$field];
            }
        }

        return null;
    }

    /**
     * Store authentication data in session
     *
     * @param string $token
     * @param array $responseData
     */
    private function storeAuthenticationData(string $token, array $responseData): void
    {
        session(['sludi_access_token' => $token]);

        if (isset($responseData['id_token'])) {
            session(['sludi_id_token' => $responseData['id_token']]);
        }

        if (isset($responseData['expires_in'])) {
            session(['sludi_token_expires_in' => $responseData['expires_in']]);
        } else {
            session(['sludi_token_expires_in' => now()->addHours(24)->toISOString()]);
        }

        session(['sludi_authenticated_at' => now()->toISOString()]);
    }

    private function storeTokenPermissionAndRoleDataToInAppUse($token)
    {
        if (!$token) {
            return;
        }

        $decodedToken = $this->decodeJwtToken($token);
        if (!$decodedToken) {
            Log::error('Failed to decode JWT token', ['token' => $token]);
            return;
        }

        $permissions = $decodedToken->permissions ?? [];
        $roles = $decodedToken->roles ?? [];

        session([
            'sludi_permissions' => $permissions,
            'sludi_roles' => $roles,
        ]);
    }

    private function decodeJwtToken(string $jwtToken): ?object
    {
        try {
            $jwksUrl = env('SLUDI_API_URL') . '/jwks.php';

            $response = ApiHelper::get($jwksUrl);

            if ($response['success'] != true) {
                throw new \Exception("Failed to fetch JWKS: " . $response);
            }

            $jwks = $response['data'];

            if (empty($jwks['keys'][0])) {
                throw new \Exception('No keys found in JWKS');
            }

            $jwk = $jwks['keys'][0];

            $publicKey = $this->jwkToPem($jwk);

            return JWT::decode($jwtToken, new Key($publicKey, 'RS256'));
        } catch (\Exception $e) {
            Log::error('JWT Decode Error', ['message' => $e->getMessage()]);
            return null;
        }
    }

    private function jwkToPem(array $jwk): string
    {
        $n = $this->base64UrlDecode($jwk['n']);
        $e = $this->base64UrlDecode($jwk['e']);

        $modulus = "\x02" . $this->encodeLength(strlen($n)) . $n;
        $publicExponent = "\x02" . $this->encodeLength(strlen($e)) . $e;

        $rsaPublicKey = "\x30" . $this->encodeLength(strlen($modulus . $publicExponent)) . $modulus . $publicExponent;
        $rsaOID = "\x30\x0D\x06\x09\x2A\x86\x48\x86\xF7\x0D\x01\x01\x01\x05\x00";
        $rsaPublicKey = "\x03" . $this->encodeLength(strlen($rsaPublicKey) + 1) . "\x00" . $rsaPublicKey;
        $rsaPublicKey = "\x30" . $this->encodeLength(strlen($rsaOID . $rsaPublicKey)) . $rsaOID . $rsaPublicKey;

        return "-----BEGIN PUBLIC KEY-----\n" .
            chunk_split(base64_encode($rsaPublicKey), 64, "\n") .
            "-----END PUBLIC KEY-----\n";
    }

    private function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $padLen = 4 - $remainder;
            $data .= str_repeat('=', $padLen);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    private function encodeLength(int $length): string
    {
        if ($length <= 0x7F) {
            return chr($length);
        }
        $temp = ltrim(pack('N', $length), "\x00");
        return chr(0x80 | strlen($temp)) . $temp;
    }

    /**
     * Check if user is currently authenticated
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        $token = session('sludi_access_token');
        $expiresAt = session('sludi_token_expires_in');

        if (empty($token)) {
            return false;
        }

        if ($expiresAt && now()->isAfter($expiresAt)) {
            $this->logout();
            return false;
        }

        return true;
    }

    /**
     * Get current authentication token
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->isAuthenticated() ? session('sludi_access_token') : null;
    }

    /**
     * Get authenticated user data
     *
     * @return array|null
     */
    public function getUser(): ?array
    {
        return $this->isAuthenticated() ? session('sludi_user') : null;
    }

    /**
     * Check if user has specific permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        $permissions = session('sludi_permissions', []);
        return in_array($permission, $permissions);
    }

    /**
     * Logout user and clear session data
     *
     * @return void
     */
    public function logout(): void
    {
        $sessionKeys = [
            'sludi_token',
            'sludi_user',
            'sludi_token_expires_at',
            'sludi_permissions',
            'sludi_authenticated_at'
        ];

        foreach ($sessionKeys as $key) {
            session()->forget($key);
        }
    }

    /**
     * Refresh authentication token
     *
     * @return array
     */
    public function refreshToken(): array
    {
        $currentToken = $this->getToken();

        if (!$currentToken) {
            return [
                'success' => false,
                'error' => true,
                'error_code' => 'NO_TOKEN',
                'message' => 'No valid token to refresh',
                'data' => null
            ];
        }

        try {
            $response = ApiHelper::post(
                env('SLUDI_API_URL') . '/refresh-token.php',
                ['token' => $currentToken],
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $currentToken
                    ]
                ]
            );

            if ($response['success']) {
                return $this->processAuthenticationResponse($response['data']);
            } else {
                return $response;
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => true,
                'error_code' => 'REFRESH_ERROR',
                'message' => 'Failed to refresh token: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}
