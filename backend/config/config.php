<?php
/**
 * BigBully AI - Environment Configuration
 */

return [
    'app' => [
        'name' => 'BigBully AI',
        'version' => '1.0.0',
        'env' => $_ENV['APP_ENV'] ?? 'development',
        'debug' => $_ENV['DEBUG'] ?? true,
    ],
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'name' => $_ENV['DB_NAME'] ?? 'bigbully_ai',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
    ],
    'api' => [
        'base_url' => $_ENV['API_URL'] ?? 'http://localhost/api',
        'version' => 'v1',
    ],
    'jwt' => [
        'secret' => $_ENV['JWT_SECRET'] ?? 'your-secret-key-change-in-production',
        'algorithm' => 'HS256',
        'expiry' => 86400 * 7, // 7 days
    ],
    'email' => [
        'driver' => $_ENV['MAIL_DRIVER'] ?? 'smtp',
        'host' => $_ENV['MAIL_HOST'] ?? 'smtp.mailtrap.io',
        'port' => $_ENV['MAIL_PORT'] ?? 465,
        'user' => $_ENV['MAIL_USER'] ?? '',
        'password' => $_ENV['MAIL_PASSWORD'] ?? '',
    ],
    'payment' => [
        'provider' => $_ENV['PAYMENT_PROVIDER'] ?? 'razorpay',
        'razorpay_key' => $_ENV['RAZORPAY_KEY'] ?? '',
        'razorpay_secret' => $_ENV['RAZORPAY_SECRET'] ?? '',
    ],
];

?>
