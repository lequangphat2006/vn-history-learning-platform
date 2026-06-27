<?php

declare(strict_types=1);

final class AuthService
{
    private static function resolveRole(string $username): string
    {
        return strtolower(trim($username)) === 'admin' ? 'admin' : 'học sinh';
    }

    public static function register(array $data): array
    {
        $username = trim($data['tdn'] ?? '');
        $password = $data['pass'] ?? '';
        $confirm = $data['nlpass'] ?? '';
        $fullName = trim($data['thvt'] ?? '');
        $email = trim($data['email'] ?? '');
        $birthDate = $data['date'] ?? null;
        $gender = $data['sex'] ?? null;

        if ($username === '' || $password === '' || $fullName === '' || $email === '') {
            return ['ok' => false, 'message' => 'Vui lòng điền đầy đủ các trường bắt buộc.'];
        }

        if (strlen($password) < 6) {
            return ['ok' => false, 'message' => 'Mật khẩu tối thiểu 6 ký tự.'];
        }

        if ($password !== $confirm) {
            return ['ok' => false, 'message' => 'Mật khẩu nhập lại không khớp.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'message' => 'Email không hợp lệ.'];
        }

        $pdo = Database::connection();
        $exists = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $exists->execute([$username, $email]);
        if ($exists->fetch()) {
            return ['ok' => false, 'message' => 'Tên đăng nhập hoặc email đã tồn tại.'];
        }

        $pdo->prepare(
            'INSERT INTO users (username, password_hash, full_name, email, birth_date, gender)
             VALUES (?, ?, ?, ?, ?, ?)'
        )->execute([
            $username,
            password_hash($password, PASSWORD_DEFAULT),
            $fullName,
            $email,
            $birthDate ?: null,
            $gender ?: null,
        ]);

        return ['ok' => true, 'message' => 'Đăng ký thành công. Vui lòng đăng nhập.'];
    }

    public static function login(string $username, string $password): array
    {
        $username = trim($username);
        if ($username === '' || $password === '') {
            return ['ok' => false, 'message' => 'Vui lòng nhập tên đăng nhập và mật khẩu.'];
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['ok' => false, 'message' => 'Tên đăng nhập hoặc mật khẩu không đúng.'];
        }

        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = self::resolveRole($user['username']);

        if (!empty($_POST['cbpass'])) {
            setcookie('remember_user', $user['username'], time() + 60 * 60 * 24 * 30, '/');
        }

        return ['ok' => true, 'message' => 'Đăng nhập thành công.'];
    }

    public static function resetPassword(string $username, string $current, string $newPass, string $confirm): array
    {
        $username = trim($username);
        if ($username === '' || $current === '' || $newPass === '' || $confirm === '') {
            return ['ok' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin.'];
        }

        if (strlen($newPass) < 6) {
            return ['ok' => false, 'message' => 'Mật khẩu mới tối thiểu 6 ký tự.'];
        }

        if ($newPass !== $confirm) {
            return ['ok' => false, 'message' => 'Mật khẩu nhập lại không khớp.'];
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        if (!$row || !password_verify($current, $row['password_hash'])) {
            return ['ok' => false, 'message' => 'Tên đăng nhập hoặc mật khẩu hiện tại không đúng.'];
        }

        $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($newPass, PASSWORD_DEFAULT), (int) $row['id']]);

        return ['ok' => true, 'message' => 'Đổi mật khẩu thành công.'];
    }

    public static function changePassword(int $userId, string $current, string $newPass, string $confirm): array
    {
        if (strlen($newPass) < 6) {
            return ['ok' => false, 'message' => 'Mật khẩu mới tối thiểu 6 ký tự.'];
        }

        if ($newPass !== $confirm) {
            return ['ok' => false, 'message' => 'Mật khẩu nhập lại không khớp.'];
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $row = $stmt->fetch();

        if (!$row || !password_verify($current, $row['password_hash'])) {
            return ['ok' => false, 'message' => 'Mật khẩu hiện tại không đúng.'];
        }

        $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($newPass, PASSWORD_DEFAULT), $userId]);

        return ['ok' => true, 'message' => 'Đổi mật khẩu thành công.'];
    }
}
