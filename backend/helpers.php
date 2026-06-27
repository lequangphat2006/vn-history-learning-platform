<?php

declare(strict_types=1);

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['_flash'][$key] = $value;
        return null;
    }

    $msg = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $msg;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function parse_grade(string $label): int
{
    if (preg_match('/\d+/', $label, $m)) {
        return (int) $m[0];
    }
    return 12;
}

function parse_question_count(string $label): int
{
    if (preg_match('/\d+/', $label, $m)) {
        return (int) $m[0];
    }
    return 40;
}

function parse_time_minutes(string $label): int
{
    if (preg_match('/\d+/', $label, $m)) {
        return (int) $m[0];
    }
    return 50;
}
