<?php

require_once __DIR__ . '/backend/bootstrap.php';
require_login();

$attemptId = (int) ($_GET['id'] ?? 0);
$user = current_user();
$attempt = QuizService::getAttemptForUser($attemptId, $user['id']);

if (!$attempt) {
    flash('error', 'Không tìm thấy bài thi.');
    redirect('do_quiz.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = QuizService::submitAttempt($attemptId, $user['id'], $_POST['answers'] ?? []);
    if ($result['ok']) {
        $lateNote = $result['is_late'] ? ' (nộp trễ sau khi hết giờ)' : '';
        flash('success', "Hoàn thành! Bạn đúng {$result['correct']}/{$result['total']} câu — Điểm: {$result['score']}{$lateNote}");
        redirect('historyPage.php');
    }
    flash('error', $result['message']);
    redirect('take_quiz.php?id=' . $attemptId);
}

require 'site.php';
load_top();
load_sitebar();
?>
<div class="column_2 rounded-xl h-full mb-16">
    <div class="header p-4 rounded-lg shadow-md bg-[#1b523b] font-bold flex justify-between items-center">
        <div>
            <h1 class="text-white text-[20px]"><b><?= e($attempt['title']) ?></b></h1>
            <p class="text-emerald-400 text-[13px]">Mã đề: <?= e($attempt['exam_code']) ?> — Thời gian: <?= (int) $attempt['time_limit_minutes'] ?> phút</p>
        </div>
        <div id="countdown-box" class="bg-white rounded-lg px-4 py-2 text-center min-w-[90px]">
            <p class="text-[11px] text-slate-500 font-semibold">Thời gian còn</p>
            <span id="countdown-display" class="text-[22px] font-bold text-[#1b4332]">--:--</span>
        </div>
    </div>

    <?php if ($msg = flash('warning')): ?>
        <p class="mx-4 mt-4 p-3 bg-yellow-100 text-yellow-800 rounded-lg text-sm">⚠️ <?= e($msg) ?></p>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
        <p class="mx-4 mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm"><?= e($msg) ?></p>
    <?php endif; ?>

    <form method="post" id="quiz-form" class="p-4 space-y-6 max-w-4xl">
        <?php foreach ($attempt['questions'] as $i => $q): ?>
            <div class="bg-white rounded-lg shadow-md p-4">
                <p class="font-bold text-[#1b4332] mb-3">Câu <?= $i + 1 ?>: <?= e($q['content']) ?></p>
                <div class="grid gap-2 text-sm text-slate-700">
                    <?php foreach (['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $opt => $col): ?>
                        <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg hover:bg-emerald-50 cursor-pointer">
                            <input type="radio" name="answers[<?= (int) $q['aq_id'] ?>]" value="<?= $opt ?>" required>
                            <span><b><?= $opt ?>.</b> <?= e($q[$col]) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="flex justify-center pb-8">
            <button type="submit" id="submit-btn" class="bg-[#1b4332] text-white font-bold px-8 py-3 rounded-lg hover:bg-emerald-700">
                NỘP BÀI
            </button>
        </div>
    </form>
</div>

<script>
(function () {
    var timeLimitMinutes = <?= (int) $attempt['time_limit_minutes'] ?>;
    var startedAt       = <?= json_encode($attempt['started_at']) ?>;
    var display         = document.getElementById('countdown-display');
    var box             = document.getElementById('countdown-box');
    var form            = document.getElementById('quiz-form');
    var submitted       = false;

    // Tính deadline theo giờ server (started_at là UTC từ SQLite datetime('now'))
    var deadlineMs = new Date(startedAt.replace(' ', 'T') + 'Z').getTime() + timeLimitMinutes * 60 * 1000;

    function tick() {
        var now       = Date.now();
        var remaining = Math.max(0, Math.floor((deadlineMs - now) / 1000));
        var mm        = Math.floor(remaining / 60);
        var ss        = remaining % 60;

        display.textContent = (mm < 10 ? '0' : '') + mm + ':' + (ss < 10 ? '0' : '') + ss;

        if (remaining <= 60) {
            box.style.borderColor   = '#ef4444';
            display.style.color     = '#ef4444';
            box.style.border        = '2px solid #ef4444';
        } else if (remaining <= 300) {
            display.style.color = '#f59e0b';
        }

        if (remaining <= 0 && !submitted) {
            submitted = true;
            display.textContent = '00:00';
            display.style.color = '#ef4444';
            // Auto nộp bài
            var hiddenInput = document.createElement('input');
            hiddenInput.type  = 'hidden';
            hiddenInput.name  = 'auto_submit';
            hiddenInput.value = '1';
            form.appendChild(hiddenInput);
            form.submit();
        }
    }

    tick();
    var interval = setInterval(tick, 1000);

    // Xác nhận trước khi nộp thủ công
    document.getElementById('submit-btn').addEventListener('click', function (e) {
        if (submitted) return;
        if (!confirm('Bạn có chắc muốn nộp bài?')) {
            e.preventDefault();
        }
    });
})();
</script>
<?php load_footer(); ?>
