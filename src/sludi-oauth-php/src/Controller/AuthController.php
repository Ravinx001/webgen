<?php

namespace User\SludiOauthPhp\Controller;

use Exception;
use Firebase\JWT\JWT;
use User\SludiOauthPhp\Service\Database;

require_once __DIR__ . '/../Helper/functions.php';

class AuthController
{
    /**
     * Authenticate user with username and password
     */
    public function authenticate()
    {
        try {
            $input = $this->getJsonInput();
            $username = $input['user_name'] ?? '';
            $password = $input['password'] ?? '';

            // Validate input
            $validationErrors = $this->validateUserInput($username, $password);
            if (!empty($validationErrors)) {
                $this->errorResponse('VALIDATION_ERROR', 'Invalid input: ' . implode(', ', $validationErrors), 400);
                return;
            }

            $pdo = Database::getConnection();
            $user = $this->findUserByUsername($pdo, $username);

            $authResult = $this->verifyUserCredentials($user, $password);
            $this->logAuthAttempt($pdo, $user, $username, $authResult);

            if (!$authResult['success']) {
                $this->errorResponse('AUTH_FAILED', 'Invalid username or password', 401);
                return;
            }

            $tokens = $this->generateUserTokens($user);
            $this->successResponse($tokens);
        } catch (Exception $e) {
            error_log("Authentication error: " . $e->getMessage());
            $this->errorResponse('INTERNAL_ERROR', 'Authentication service temporarily unavailable', 500);
        }
    }

    /**
     * Authenticate OAuth client with client credentials
     */
    public function authenticateClient()
    {
        try {
            $input = $this->getJsonInput();
            $clientId = $input['client_id'] ?? '';
            $clientSecret = $input['client_secret'] ?? '';

            // Validate input
            $validationErrors = $this->validateClientInput($clientId, $clientSecret);
            if (!empty($validationErrors)) {
                $this->errorResponse('VALIDATION_ERROR', 'Invalid input: ' . implode(', ', $validationErrors), 400);
                return;
            }

            $pdo = Database::getConnection();
            $client = $this->findClientById($pdo, $clientId);

            // SECURITY FIX: Use password_verify for hashed client secrets
            if (!$client || !password_verify($clientSecret, $client['client_secret'])) {
                $this->errorResponse('CLIENT_AUTH_FAILED', 'Invalid client credentials', 401);
                return;
            }

            $token = $this->generateClientToken($clientId);
            $this->successResponse($token);
        } catch (Exception $e) {
            error_log("Client authentication error: " . $e->getMessage());
            $this->errorResponse('INTERNAL_ERROR', 'Authentication service temporarily unavailable', 500);
        }
    }

    /**
     * Provide JSON Web Key Set for token verification
     */
    public function jwks()
    {
        try {
            $pubKeyPath = $this->getPublicKeyPath();

            if (!file_exists($pubKeyPath)) {
                throw new Exception('Public key file not found');
            }

            $pubKey = file_get_contents($pubKeyPath);
            if ($pubKey === false) {
                throw new Exception('Unable to read public key');
            }

            $details = openssl_pkey_get_details(openssl_pkey_get_public($pubKey));

            if (!$details || !isset($details['rsa'])) {
                throw new Exception('Invalid RSA public key');
            }

            $n = $this->base64UrlEncode($details['rsa']['n']);
            $e = $this->base64UrlEncode($details['rsa']['e']);

            $jwk = [
                "keys" => [[
                    "kty" => "RSA",
                    "alg" => env('JWT_ALG', 'RS256'),
                    "use" => "sig",
                    "kid" => "sludi-key-1",
                    "n" => $n,
                    "e" => $e
                ]]
            ];

            header('Content-Type: application/json');
            header('Cache-Control: public, max-age=3600'); // Cache for 1 hour
            echo json_encode($jwk);
        } catch (Exception $e) {
            error_log("JWKS error: " . $e->getMessage());
            $this->errorResponse('INTERNAL_ERROR', 'JWKS service temporarily unavailable', 500);
        }
    }

    // ====================================
    // Private Helper Methods
    // ====================================

    private function getJsonInput()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON input');
        }

        return $data ?? [];
    }

    private function validateUserInput($username, $password)
    {
        $errors = [];

        if (empty($username) || strlen($username) < 6 || strlen($username) > 32) {
            $errors[] = 'Username must be 6-32 characters';
        }

        if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
            $errors[] = 'Username contains invalid characters';
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        return $errors;
    }

    private function validateClientInput($clientId, $clientSecret)
    {
        $errors = [];

        if (empty($clientId) || strlen($clientId) < 5 || strlen($clientId) > 80) {
            $errors[] = 'Client ID must be 5-80 characters';
        }

        if (empty($clientSecret) || strlen($clientSecret) < 8) {
            $errors[] = 'Client secret must be at least 8 characters';
        }

        return $errors;
    }

    private function findUserByUsername($pdo, $username)
    {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND status = 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    private function findClientById($pdo, $clientId)
    {
        $stmt = $pdo->prepare('SELECT * FROM oauth_clients WHERE client_id = ?');
        $stmt->execute([$clientId]);
        $client = $stmt->fetch();

        return $client ?: null;
    }

    private function verifyUserCredentials($user, $password)
    {
        if (!$user) {
            return ['success' => false, 'reason' => 'User not found'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'reason' => 'Invalid password'];
        }

        return ['success' => true, 'reason' => ''];
    }

    private function getUserDepartmentRoles($pdo, $userId)
    {
        $stmt = $pdo->prepare("
             SELECT 
            r.name AS role,
            d.id AS department_id,
            d.name AS department_name
        FROM department_user_role udr
        JOIN roles r ON udr.role_id = r.id
        JOIN departments d ON udr.department_id = d.id
        WHERE udr.user_id = ?
        ");

        $stmt->execute([$userId]);

        $roles = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $roles ?: ['user'];
    }

    private function getUserPermissions($pdo, $userId)
    {
        $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.name,
            p.display_name,
            p.description,
            p.created_at,
            p.updated_at,
            up.granted_at
        FROM user_permissions up
        JOIN permissions p ON up.permission_id = p.id
        WHERE up.user_id = ?
    ");

        $stmt->execute([$userId]);
        $permissions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $permissions ?: [];
    }

    private function logAuthAttempt($pdo, $user, $username, $authResult)
    {
        $ip = $this->getClientIp();
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $logStmt = $pdo->prepare(
            'INSERT INTO auth_logs (user_id, username, success, ip_address, user_agent, reason) VALUES (?, ?, ?, ?, ?, ?)'
        );

        $logStmt->execute([
            $user['id'] ?? null,
            $username,
            $authResult['success'] ? 1 : 0,
            $ip,
            $agent,
            $authResult['reason'] ?: null
        ]);
    }

    private function getClientIp()
    {
        // Check for IP from various headers (proxy-aware)
        $ipHeaders = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];

        foreach ($ipHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                return trim($ips[0]);
            }
        }

        return 'unknown';
    }

    private function generateUserTokens($user)
    {
        $now = time();
        $jwtExpire = (int) env('JWT_EXPIRE', 3600);
        $issuer = env('APP_URL', 'http://localhost:8000');
        $audience = env('JWT_AUD', 'your-client-id');

        $pdo = Database::getConnection();
        $roles = $this->getUserDepartmentRoles($pdo, $user['id']);
        $permissions = $this->getUserPermissions($pdo, $user['id']);

        $idPayload = [
            "iss" => $issuer,
            "sub" => $user['digital_id'],
            "iat" => $now,
            "exp" => $now + $jwtExpire,
            "name" => $user['name'] ?? null,
            "email" => $user['email'] ?? null,
            "aud" => $audience,
            "nonce" => $this->generateSecureNonce()
        ];

        $accessPayload = [
            "iss" => $issuer,
            "sub" => $user['digital_id'],
            "iat" => $now,
            "exp" => $now + $jwtExpire,
            "scope" => "read write",
            "roles" => $roles,
            "permissions" => $permissions
        ];

        return [
            "access_token" => $this->createJwtToken($accessPayload),
            "id_token" => $this->createJwtToken($idPayload),
            "token_type" => "Bearer",
            "expires_in" => $jwtExpire
        ];
    }

    private function generateClientToken($clientId)
    {
        $now = time();
        $jwtExpire = (int) env('JWT_EXPIRE', 3600);
        $issuer = env('APP_URL', 'http://localhost:8000');

        $tokenPayload = [
            'iss' => $issuer,
            'sub' => $clientId,
            'iat' => $now,
            'exp' => $now + $jwtExpire,
            'scope' => 'read write',
            'type' => 'client_credentials'
        ];

        return [
            'access_token' => $this->createJwtToken($tokenPayload),
            'token_type' => 'Bearer',
            'expires_in' => $jwtExpire
        ];
    }

    private function createJwtToken($payload)
    {
        $privateKeyPath = $this->getPrivateKeyPath();

        if (!file_exists($privateKeyPath)) {
            throw new Exception('Private key file not found');
        }

        $privateKey = file_get_contents($privateKeyPath);
        if ($privateKey === false) {
            throw new Exception('Unable to read private key');
        }

        $algorithm = env('JWT_ALG', 'RS256');
        return JWT::encode($payload, $privateKey, $algorithm);
    }

    private function getPrivateKeyPath()
    {
        return env('PRIVATE_KEY_PATH', __DIR__ . '/../../keys/private.pem');
    }

    private function getPublicKeyPath()
    {
        return env('PUBLIC_KEY_PATH', __DIR__ . '/../../keys/public.pem');
    }

    private function generateSecureNonce()
    {
        // SECURITY FIX: Generate random nonce instead of hardcoded string
        return bin2hex(random_bytes(16));
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function errorResponse(string $code, string $message, int $httpCode): void
    {
        echo json_encode([
            "success" => false,
            "error" => $code,
            "message" => $message,
            "timestamp" => date('c')
        ]);
    }

    private function successResponse(array $data): void
    {
        echo json_encode(array_merge([
            "success" => true,
            "status" => "SUCCESS",
            "timestamp" => date('c')
        ], $data));
    }
}
