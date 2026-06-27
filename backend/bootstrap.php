<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/middleware/Auth.php';
require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/QuizService.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
