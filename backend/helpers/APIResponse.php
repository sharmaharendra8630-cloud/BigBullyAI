<?php
/**
 * BigBully AI - API Response Helper
 */

class APIResponse {
    /**
     * Send success response
     */
    public static function success($data = null, $message = 'Success', $statusCode = 200) {
        self::sendResponse([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Send error response
     */
    public static function error($message = 'Error', $statusCode = 400, $errors = null) {
        self::sendResponse([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Send paginated response
     */
    public static function paginated($data, $page, $limit, $total, $message = 'Success') {
        self::sendResponse([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit),
            ],
        ], 200);
    }

    /**
     * Send response
     */
    private static function sendResponse($response, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        header('X-Content-Type-Options: nosniff');
        echo json_encode($response);
        exit;
    }
}

?>
