<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once dirname(__DIR__) . '/bootstrap.php';

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input') ?: '{}', true) ?? [];

function json_response(array $data, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($action) {
    case 'health':
        json_response(['status' => 'ok', 'service' => 'lich-su-quiz-api']);

    case 'login':
        if ($method !== 'POST') {
            json_response(['error' => 'Method not allowed'], 405);
        }
        $r = AuthService::login($body['username'] ?? '', $body['password'] ?? '');
        json_response($r, $r['ok'] ? 200 : 401);

    case 'register':
        if ($method !== 'POST') {
            json_response(['error' => 'Method not allowed'], 405);
        }
        $r = AuthService::register($body);
        json_response($r, $r['ok'] ? 201 : 400);

    case 'history':
        if (empty($_SESSION['user_id'])) {
            json_response(['ok' => false, 'message' => 'Unauthorized'], 401);
        }
        json_response(['items' => QuizService::history((int) $_SESSION['user_id'])]);

    case 'dashboard':
        if (empty($_SESSION['user_id'])) {
            json_response(['ok' => false, 'message' => 'Unauthorized'], 401);
        }
        json_response(QuizService::dashboardStats((int) $_SESSION['user_id']));

    default:
        json_response(['error' => 'Unknown action', 'available' => ['health', 'login', 'register', 'history', 'dashboard']], 404);
}
