<?php

declare(strict_types=1);

function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        flash('error', 'Vui lòng đăng nhập để tiếp tục.');
        redirect('login.php');
    }
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    return [
        'id' => (int) $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'full_name' => $_SESSION['user'] ?? '',
        'role' => $_SESSION['role'] ?? 'Học sinh',
    ];
}
