<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';

$pdo = Database::connection();

// === 1. Tạo bảng ===
$pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    full_name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    birth_date TEXT,
    gender TEXT,
    created_at TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS questions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    grade INTEGER NOT NULL,
    difficulty TEXT NOT NULL,
    content TEXT NOT NULL,
    option_a TEXT NOT NULL,
    option_b TEXT NOT NULL,
    option_c TEXT NOT NULL,
    option_d TEXT NOT NULL,
    correct_option TEXT NOT NULL CHECK (correct_option IN ('A','B','C','D'))
);

CREATE TABLE IF NOT EXISTS exam_attempts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    exam_code TEXT NOT NULL,
    title TEXT NOT NULL,
    grade INTEGER NOT NULL,
    difficulty TEXT NOT NULL,
    total_questions INTEGER NOT NULL,
    time_limit_minutes INTEGER NOT NULL,
    correct_count INTEGER DEFAULT 0,
    score REAL DEFAULT 0,
    status TEXT NOT NULL DEFAULT 'in_progress' CHECK (status IN ('in_progress','completed')),
    started_at TEXT NOT NULL DEFAULT (datetime('now')),
    completed_at TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS attempt_questions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    attempt_id INTEGER NOT NULL,
    question_id INTEGER NOT NULL,
    sort_order INTEGER NOT NULL,
    selected_option TEXT CHECK (selected_option IN ('A','B','C','D')),
    is_correct INTEGER DEFAULT 0,
    FOREIGN KEY (attempt_id) REFERENCES exam_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    UNIQUE (attempt_id, question_id)
);
SQL);

echo "✅ Tạo bảng thành công.\n";

// === 2. Import câu hỏi từ JSON ===

function normalizeDifficulty(string $value): string
{
    $normalized = preg_replace('/\s+/u', ' ', trim($value)) ?? trim($value);
    foreach (['Nhận biết', 'Thông hiểu', 'Vận dụng'] as $canonical) {
        if (strcasecmp($normalized, $canonical) === 0) {
            return $canonical;
        }
    }
    return $normalized;
}

function optionText(array $options, string $id): ?string
{
    foreach ($options as $opt) {
        if (($opt['id'] ?? '') === $id) {
            return trim((string) ($opt['text'] ?? ''));
        }
    }
    return null;
}

function mapQuestion(array $item, string $fallbackDifficulty): ?array
{
    $grade   = (int) ($item['grade'] ?? 12);
    $content = trim((string) ($item['question'] ?? ''));
    $options = $item['options'] ?? [];
    $correct = strtoupper(trim((string) ($item['correctOption'] ?? '')));
    $difficulty = normalizeDifficulty((string) ($item['difficulty'] ?? $fallbackDifficulty));

    if ($content === '' || !in_array($correct, ['A', 'B', 'C', 'D'], true)) {
        return null;
    }

    $a = optionText($options, 'A');
    $b = optionText($options, 'B');
    $c = optionText($options, 'C');
    $d = optionText($options, 'D');

    if ($a === null || $b === null || $c === null || $d === null) return null;
    if ($a === '' || $b === '' || $c === '' || $d === '') return null;

    return [
        'grade'          => $grade,
        'difficulty'     => $difficulty,
        'content'        => $content,
        'option_a'       => $a,
        'option_b'       => $b,
        'option_c'       => $c,
        'option_d'       => $d,
        'correct_option' => $correct,
    ];
}

$existing = (int) $pdo->query('SELECT COUNT(*) FROM questions')->fetchColumn();

if ($existing > 0) {
    echo "ℹ️  Database đã có {$existing} câu hỏi — bỏ qua import.\n";
} else {
    $files = [
        __DIR__ . '/data/nhan_biet.json' => 'Nhận biết',
        __DIR__ . '/data/thong_hieu.json' => 'Thông hiểu',
        __DIR__ . '/data/van_dung.json'   => 'Vận dụng',
    ];

    $insert = $pdo->prepare(
        'INSERT INTO questions (grade, difficulty, content, option_a, option_b, option_c, option_d, correct_option)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );

    $totalImported = 0;
    $totalSkipped  = 0;

    foreach ($files as $path => $fallbackDifficulty) {
        if (!is_file($path)) {
            echo "⚠️  Không tìm thấy: {$path}\n";
            continue;
        }

        $raw = file_get_contents($path);
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            echo "⚠️  JSON lỗi: " . basename($path) . "\n";
            continue;
        }

        $cnt = 0; $skip = 0;
        foreach ($data as $item) {
            $row = mapQuestion($item, $fallbackDifficulty);
            if ($row === null) { $skip++; continue; }
            $insert->execute([
                $row['grade'], $row['difficulty'], $row['content'],
                $row['option_a'], $row['option_b'], $row['option_c'],
                $row['option_d'], $row['correct_option'],
            ]);
            $cnt++;
        }

        echo "📥 " . basename($path) . ": import {$cnt} câu" . ($skip > 0 ? ", bỏ qua {$skip}" : "") . ".\n";
        $totalImported += $cnt;
        $totalSkipped  += $skip;
    }

    $total = (int) $pdo->query('SELECT COUNT(*) FROM questions')->fetchColumn();
    echo "\n✅ Hoàn tất: {$totalImported} câu được import" . ($totalSkipped > 0 ? ", {$totalSkipped} bỏ qua" : "") . ".\n";
    echo "📊 Tổng câu hỏi trong database: {$total}\n\n";

    $stats = $pdo->query(
        'SELECT grade, difficulty, COUNT(*) AS cnt FROM questions GROUP BY grade, difficulty ORDER BY grade, difficulty'
    )->fetchAll(PDO::FETCH_ASSOC);

    echo "Thống kê theo mức độ:\n";
    foreach ($stats as $s) {
        echo "  - Lớp {$s['grade']} | {$s['difficulty']}: {$s['cnt']} câu\n";
    }
}

// === 3. Tạo tài khoản admin mặc định ===
$hash = password_hash('123456', PASSWORD_DEFAULT);
$pdo->prepare(
    'INSERT OR IGNORE INTO users (username, password_hash, full_name, email, birth_date, gender)
     VALUES (?, ?, ?, ?, ?, ?)'
)->execute(['admin', $hash, 'Quản trị viên', 'admin@lichsu.edu.vn', '2000-01-01', 'Nam']);

echo "\n✅ Tài khoản demo: username=admin / password=123456\n";
echo "🚀 Hệ thống sẵn sàng!\n";
