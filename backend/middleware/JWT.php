<?php
/**
 * BigBully AI - JWT Authentication Handler
 */

class JWT {
    private static $secret;
    private static $algorithm = 'HS256';
    private static $expiryTime = 604800; // 7 days

    public static function init($secret, $expiryTime = null) {
        self::$secret = $secret;
        if ($expiryTime) {
            self::$expiryTime = $expiryTime;
        }
    }

    /**
     * Create JWT token
     */
    public static function create($data) {
        $now = time();
        $payload = [
            'iat' => $now,
            'exp' => $now + self::$expiryTime,
            'data' => $data,
        ];

        return self::encode($payload);
    }

    /**
     * Verify JWT token
     */
    public static function verify($token) {
        try {
            $parts = explode('.', $token);
            if (count($parts) !== 3) {
                return false;
            }

            list($header, $payload, $signature) = $parts;

            // Verify signature
            $expectedSignature = hash_hmac(
                'sha256',
                $header . '.' . $payload,
                self::$secret,
                true
            );
            $expectedSignature = rtrim(strtr(base64_encode($expectedSignature), '+/', '-_'), '=');

            if ($signature !== $expectedSignature) {
                return false;
            }

            // Decode payload
            $decoded = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

            // Check expiration
            if ($decoded['exp'] < time()) {
                return false;
            }

            return $decoded['data'] ?? false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Encode JWT
     */
    private static function encode($payload) {
        $header = [
            'typ' => 'JWT',
            'alg' => self::$algorithm,
        ];

        $headerEncoded = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $payloadEncoded = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

        $signature = hash_hmac(
            'sha256',
            $headerEncoded . '.' . $payloadEncoded,
            self::$secret,
            true
        );
        $signatureEncoded = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }
}

?>
