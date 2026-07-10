<?php
/**
 * BigBully AI - CSRF Protection Middleware
 */

class CSRFProtection {
    private static $tokenName = '_csrf_token';

    /**
     * Generate CSRF token
     */
    public static function generateToken() {
        if (!isset($_SESSION[self::$tokenName])) {
            $_SESSION[self::$tokenName] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::$tokenName];
    }

    /**
     * Verify CSRF token
     */
    public static function verifyToken($token) {
        return isset($_SESSION[self::$tokenName]) && hash_equals($_SESSION[self::$tokenName], $token);
    }

    /**
     * Validate request
     */
    public static function validateRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            return true;
        }

        $token = $_POST[self::$tokenName] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        
        if (!$token || !self::verifyToken($token)) {
            http_response_code(403);
            die(json_encode(['error' => 'CSRF token validation failed']));
        }

        return true;
    }
}

?>
