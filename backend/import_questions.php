<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';

/**
 * Import câu hỏi từ file JSON vào bảng questions.
 *
 * Usage:
 *   php backend/import_questions.php
 *   php backend/import_questions.php --replace   # xóa câu cũ trước khi import
 */

function normalizeDifficulty(string $value): string
{
    $normalized = preg_replace('/\s+/u', ' ', trim($value)) ?? trim($value);

    foreach (['Nhận biết', 'Thông hiểu', 'Vận dụng'] as $canonical) {
        if (strcasecmp($normalized, $canonical) === 0) {
            return $canonical;
        }
    }

    $ascii = strtolower(str_replace(['ậ', 'ẩ', 'ẫ', 'ă', 'â', 'đ', 'ê', 'ô', 'ơ', 'ư', 'í', 'ì', 'ỉ', 'ĩ', 'ị'], ['a', 'a', 'a', 'a', 'a', 'd', 'e', 'o', 'o', 'u', 'i', 'i', 'i', 'i', 'i'], $normalized));
    $map = [
        'nhan biet' => 'Nhận biết',
        'thong hieu' => 'Thông hiểu',
        'van dung' => 'Vận dụng',
    ];

    return $map[$ascii] ?? $normalized;
}

function repairVanDungJson(string $raw): string
{
    $pattern = '/("correctOption": "D",)\s*\{\s*"id": "B",.*?"correctOption": "",/s';
    $replacement = '$1';

    return preg_replace($pattern, $replacement, $raw) ?? $raw;
}

function loadQuestionsFromFile(string $path): array
{
    if (!is_file($path)) {
        throw new RuntimeException("Không tìm thấy file: {$path}");
    }

    $raw = file_get_contents($path);
    if ($raw === false) {
        throw new RuntimeException("Không đọc được file: {$path}");
    }

    if (str_ends_with(basename($path), 'van_dung.json')) {
        $raw = repairVanDungJson($raw);
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        throw new RuntimeException('JSON lỗi (' . basename($path) . '): ' . json_last_error_msg());
    }

    return $data;
}

function optionText(array $options, string $id): ?string
{
    foreach ($options as $option) {
        if (($option['id'] ?? '') === $id) {
            return trim((string) ($option['text'] ?? ''));
        }
    }

    return null;
}

function mapQuestion(array $item, string $fallbackDifficulty): ?array
{
    $grade = (int) ($item['grade'] ?? 12);
    $content = trim((string) ($item['question'] ?? ''));
    $options = $item['options'] ?? [];
    $correct = strtoupper(trim((string) ($item['correctOption'] ?? '')));
    $difficulty = normalizeDifficulty((string) ($item['difficulty'] ?? $fallbackDifficulty));

    if ($content === '' || !in_array($correct, ['A', 'B', 'C', 'D'], true)) {
        return null;
    }

    $optionA = optionText($options, 'A');
    $optionB = optionText($options, 'B');
    $optionC = optionText($options, 'C');
    $optionD = optionText($options, 'D');

    if ($optionA === null || $optionB === null || $optionC === null || $optionD === null) {
        return null;
    }

    if ($optionA === '' || $optionB === '' || $optionC === '' || $optionD === '') {
        return null;
    }

    return [
        'grade' => $grade,
        'difficulty' => $difficulty,
        'content' => $content,
        'option_a' => $optionA,
        'option_b' => $optionB,
        'option_c' => $optionC,
        'option_d' => $optionD,
        'correct_option' => $correct,
    ];
}

$replace = in_array('--replace', $argv ?? [], true);

$files = [
    __DIR__ . '/data/nhan_biet.json' => 'Nhận biết',
    __DIR__ . '/data/thong_hieu.json' => 'Thông hiểu',
    __DIR__ . '/data/van_dung.json' => 'Vận dụng',
];

$pdo = Database::connection();

$pdo->exec(<<<'SQL'
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
SQL);

if ($replace) {
    $pdo->exec('DELETE FROM questions');
    echo "Đã xóa toàn bộ câu hỏi cũ.\n";
}

$insert = $pdo->prepare(
    'INSERT INTO questions (grade, difficulty, content, option_a, option_b, option_c, option_d, correct_option)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);

$imported = 0;
$skipped = 0;

foreach ($files as $path => $fallbackDifficulty) {
    $items = loadQuestionsFromFile($path);
    $fileImported = 0;
    $fileSkipped = 0;

    foreach ($items as $item) {
        $row = mapQuestion($item, $fallbackDifficulty);
        if ($row === null) {
            $fileSkipped++;
            continue;
        }

        $insert->execute([
            $row['grade'],
            $row['difficulty'],
            $row['content'],
            $row['option_a'],
            $row['option_b'],
            $row['option_c'],
            $row['option_d'],
            $row['correct_option'],
        ]);
        $fileImported++;
    }

    echo basename($path) . ": import {$fileImported}, bỏ qua {$fileSkipped}\n";
    $imported += $fileImported;
    $skipped += $fileSkipped;
}

$total = (int) $pdo->query('SELECT COUNT(*) FROM questions')->fetchColumn();
echo "\nHoàn tất: thêm {$imported} câu, bỏ qua {$skipped} câu lỗi.\n";
echo "Tổng câu hỏi trong database: {$total}\n";

$stats = $pdo->query(
    'SELECT grade, difficulty, COUNT(*) AS cnt FROM questions GROUP BY grade, difficulty ORDER BY grade, difficulty'
)->fetchAll();

echo "\nThống kê:\n";
foreach ($stats as $stat) {
    echo "- Lớp {$stat['grade']} | {$stat['difficulty']}: {$stat['cnt']} câu\n";
}
