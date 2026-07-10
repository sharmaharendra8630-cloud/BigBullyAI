<?php
/**
 * BigBully AI - PHP Backend Entry Point
 * index.php for /api endpoint
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-TOKEN');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/middleware/JWT.php';
require_once __DIR__ . '/middleware/CSRFProtection.php';
require_once __DIR__ . '/helpers/APIResponse.php';
require_once __DIR__ . '/helpers/Validator.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Company.php';
require_once __DIR__ . '/models/Research.php';
require_once __DIR__ . '/models/Watchlist.php';
require_once __DIR__ . '/models/Analysis.php';

// Load config
$config = require_once __DIR__ . '/config/config.php';

// Initialize JWT
JWT::init($config['jwt']['secret'], $config['jwt']['expiry']);

// Initialize database
$database = new Database();
$db = $database->connect();

// Route parser
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = str_replace('/api', '', $path);
$path_parts = explode('/', trim($path, '/'));

// Default response
$response = ['status' => 'error', 'message' => 'Endpoint not found'];
http_response_code(404);

// Route handling
if (empty($path_parts[0])) {
    APIResponse::success(['version' => '1.0', 'name' => 'BigBully AI API'], 'Welcome to BigBully AI API');
}

echo json_encode($response);

?>
