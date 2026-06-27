<?php

declare(strict_types=1);

final class QuizService
{
    public static function startAttempt(int $userId, int $grade, string $difficulty, int $count, int $timeMinutes): array
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT id FROM questions WHERE grade = ? AND difficulty = ? ORDER BY RANDOM() LIMIT ?'
        );
        $stmt->execute([$grade, $difficulty, $count]);
        $questionIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (count($questionIds) < $count) {
            $need = $count - count($questionIds);
            if ($questionIds === []) {
                $fallback = $pdo->prepare(
                    'SELECT id FROM questions WHERE grade = ? ORDER BY RANDOM() LIMIT ?'
                );
                $fallback->execute([$grade, $need]);
            } else {
                $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
                $params = array_merge([$grade], array_map('intval', $questionIds), [$need]);
                $fallback = $pdo->prepare(
                    "SELECT id FROM questions WHERE grade = ? AND id NOT IN ({$placeholders}) ORDER BY RANDOM() LIMIT ?"
                );
                $fallback->execute($params);
            }
            $questionIds = array_merge($questionIds, $fallback->fetchAll(PDO::FETCH_COLUMN));
        }

        if (count($questionIds) < 1) {
            return ['ok' => false, 'message' => 'Chưa có câu hỏi trong ngân hàng đề. Chạy backend/init_db.php.'];
        }

        $available = count($questionIds);
        $warning = null;
        if ($available < $count) {
            $warning = "Ngân hàng đề chỉ có {$available} câu cho lựa chọn này. Đề thi sẽ có {$available} câu.";
        }
        $count = min($count, $available);
        $questionIds = array_slice($questionIds, 0, $count);

        $examCode = sprintf('DE_LS_%d_%s_%s', $grade, strtoupper(substr(md5((string) microtime(true)), 0, 6)), date('ymd'));
        $title = "Đề ôn luyện Lớp {$grade} - {$difficulty}";

        $pdo->prepare(
            'INSERT INTO exam_attempts (user_id, exam_code, title, grade, difficulty, total_questions, time_limit_minutes)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        )->execute([$userId, $examCode, $title, $grade, $difficulty, $count, $timeMinutes]);

        $attemptId = (int) $pdo->lastInsertId();

        $insert = $pdo->prepare(
            'INSERT INTO attempt_questions (attempt_id, question_id, sort_order) VALUES (?, ?, ?)'
        );
        foreach ($questionIds as $i => $qid) {
            $insert->execute([$attemptId, (int) $qid, $i + 1]);
        }

        return ['ok' => true, 'attempt_id' => $attemptId, 'warning' => $warning];
    }

    public static function getAttemptForUser(int $attemptId, int $userId): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM exam_attempts WHERE id = ? AND user_id = ?');
        $stmt->execute([$attemptId, $userId]);
        $attempt = $stmt->fetch();
        if (!$attempt) {
            return null;
        }

        $q = $pdo->prepare(
            'SELECT aq.id AS aq_id, aq.sort_order, q.*
             FROM attempt_questions aq
             JOIN questions q ON q.id = aq.question_id
             WHERE aq.attempt_id = ?
             ORDER BY aq.sort_order'
        );
        $q->execute([$attemptId]);
        $attempt['questions'] = $q->fetchAll();

        return $attempt;
    }

    public static function submitAttempt(int $attemptId, int $userId, array $answers): array
    {
        $attempt = self::getAttemptForUser($attemptId, $userId);
        if (!$attempt) {
            return ['ok' => false, 'message' => 'Bài thi không tồn tại.'];
        }

        if ($attempt['status'] === 'completed') {
            return ['ok' => false, 'message' => 'Bài thi đã nộp trước đó.'];
        }

        $pdo = Database::connection();

        // Kiểm tra hết giờ phía server
        $isLate = false;
        $deadlineCheck = $pdo->prepare(
            "SELECT CASE WHEN datetime('now') > datetime(started_at, '+' || time_limit_minutes || ' minutes')
                    THEN 1 ELSE 0 END AS is_late
             FROM exam_attempts WHERE id = ?"
        );
        $deadlineCheck->execute([$attemptId]);
        $deadlineRow = $deadlineCheck->fetch();
        if ($deadlineRow && (int) $deadlineRow['is_late'] === 1) {
            $isLate = true;
        }
        $correct = 0;
        $total = count($attempt['questions']);

        $update = $pdo->prepare(
            'UPDATE attempt_questions SET selected_option = ?, is_correct = ? WHERE id = ?'
        );

        foreach ($attempt['questions'] as $row) {
            $selected = strtoupper(trim($answers[$row['aq_id']] ?? ''));
            if (!in_array($selected, ['A', 'B', 'C', 'D'], true)) {
                $selected = '';
            }
            $isCorrect = $selected !== '' && $selected === $row['correct_option'] ? 1 : 0;
            if ($isCorrect) {
                $correct++;
            }
            $update->execute([$selected ?: null, $isCorrect, $row['aq_id']]);
        }

        $score = $total > 0 ? round(($correct / $total) * 10, 1) : 0;

        $pdo->prepare(
            'UPDATE exam_attempts SET correct_count = ?, score = ?, status = ?, completed_at = datetime(\'now\') WHERE id = ?'
        )->execute([$correct, $score, 'completed', $attemptId]);

        return [
            'ok'      => true,
            'correct' => $correct,
            'total'   => $total,
            'score'   => $score,
            'is_late' => $isLate,
        ];
    }

    public static function history(int $userId): array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT id, title, exam_code, correct_count, total_questions, score, status,
                    COALESCE(completed_at, started_at) AS attempt_date
             FROM exam_attempts
             WHERE user_id = ? AND status = ?
             ORDER BY completed_at DESC, started_at DESC'
        );
        $stmt->execute([$userId, 'completed']);
        return $stmt->fetchAll();
    }

    public static function dashboardStats(int $userId): array
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT COUNT(DISTINCT date(COALESCE(completed_at, started_at))) AS study_days
             FROM exam_attempts WHERE user_id = ?'
        );
        $stmt->execute([$userId]);
        $studyDays = (int) ($stmt->fetch()['study_days'] ?? 0);

        $stmt = $pdo->prepare(
            'SELECT COUNT(*) AS attempt_count, MAX(score) AS best_score
             FROM exam_attempts WHERE user_id = ? AND status = ?'
        );
        $stmt->execute([$userId, 'completed']);
        $row = $stmt->fetch() ?: ['attempt_count' => 0, 'best_score' => null];

        $attemptCount = (int) $row['attempt_count'];
        $bestScore    = $attemptCount > 0 ? (float) $row['best_score'] : null;

        $stmt = $pdo->prepare(
            'SELECT title, exam_code, correct_count, total_questions, score, completed_at
             FROM exam_attempts WHERE user_id = ? AND status = ?
             ORDER BY completed_at DESC LIMIT 1'
        );
        $stmt->execute([$userId, 'completed']);
        $last = $stmt->fetch() ?: null;

        return [
            'study_days'    => max($studyDays, 1),
            'attempt_count' => $attemptCount,
            'best_score'    => $bestScore,
            'last_attempt'  => $last,
        ];
    }
}
