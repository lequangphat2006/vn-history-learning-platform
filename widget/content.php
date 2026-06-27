<?php
$stats = QuizService::dashboardStats((int) $_SESSION['user_id']);
$last = $stats['last_attempt'];
$bestScore = $stats['best_score'] !== null ? number_format($stats['best_score'], 1) : '—';
?>
<div class="column_2 rounded-xl h-full">
    <!-- Welcome Banner -->
    <div class="text-white header_cl2 bg-[#1b523b] font-bold rounded-xl p-5 shadow-md">
        <h1 class="text-[20px]">Học sử hôm nay — Rạng danh ngày mai! 🎓</h1>
        <p class="text-[13px] text-emerald-400 mt-1">Cổng luyện thi trực tuyến bám sát cấu trúc đề tốt nghiệp THPT. <strong class="text-white">hơn 950 câu hỏi</strong> phân cấp 3 mức độ.</p>
    </div>

    <!-- Stats Cards -->
    <div class="mt-5 px-1 gap-4 grid grid-cols-3">
        <div class="rounded-xl bg-white text-center shadow-md font-semibold overflow-hidden">
            <div class="border-t-4 border-emerald-600"></div>
            <div class="p-4">
                <p class="text-gray-500 text-[12px] uppercase font-bold mb-1">Số ngày học</p>
                <span class="text-[32px] font-extrabold text-[#1b4332]"><?= (int) $stats['study_days'] ?></span>
                <p class="text-gray-400 text-[11px]">ngày</p>
            </div>
        </div>
        <div class="rounded-xl bg-white text-center shadow-md font-semibold overflow-hidden">
            <div class="border-t-4 border-yellow-500"></div>
            <div class="p-4">
                <p class="text-gray-500 text-[12px] uppercase font-bold mb-1">Lượt thi thử</p>
                <span class="text-[32px] font-extrabold text-yellow-500"><?= (int) $stats['attempt_count'] ?></span>
                <p class="text-gray-400 text-[11px]">đề</p>
            </div>
        </div>
        <div class="rounded-xl bg-white text-center shadow-md font-semibold overflow-hidden">
            <div class="border-t-4 border-pink-400"></div>
            <div class="p-4">
                <p class="text-gray-500 text-[12px] uppercase font-bold mb-1">Điểm cao nhất</p>
                <span class="text-[32px] font-extrabold text-pink-400"><?= e($bestScore) ?></span>
                <p class="text-gray-400 text-[11px]"><?= $stats['best_score'] !== null ? '/ 10' : '' ?></p>
            </div>
        </div>
    </div>

    <!-- Last Attempt -->
    <div class="rounded-xl bg-white font-semibold p-4 mt-4 shadow-md">
        <div class="flex items-center gap-2 mb-3">
            <i class="fa fa-history text-emerald-500 text-[16px]"></i>
            <span class="uppercase text-gray-700 font-bold text-[13px]">Lần làm bài gần nhất</span>
        </div>
        <?php if ($last): ?>
            <?php
            $scoreNum = (float) $last['score'];
            $scoreColor = $scoreNum >= 8 ? 'text-emerald-600' : ($scoreNum >= 5 ? 'text-yellow-600' : 'text-red-500');
            $scoreIcon = $scoreNum >= 8 ? '🏆' : ($scoreNum >= 5 ? '✅' : '📝');
            $datetime = new DateTime($last['completed_at'], new DateTimeZone('UTC'));
            $datetime->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
            ?>
            <div class="bg-gray-50 rounded-lg p-3 text-[13px]">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-bold text-[#1b4332]"><?= e($last['title']) ?></p>
                        <p class="text-gray-500 text-[12px] mt-1">
                            Mã đề: <span class="font-mono"><?= e($last['exam_code']) ?></span> ·
                            <?= e($datetime->format('d/m/Y H:i')) ?>
                        </p>
                        <p class="text-gray-600 mt-1">
                            Kết quả: <strong><?= (int) $last['correct_count'] ?>/<?= (int) $last['total_questions'] ?> câu đúng</strong>
                        </p>
                    </div>
                    <div class="text-center ml-4">
                        <span class="text-3xl"><?= $scoreIcon ?></span>
                        <p class="<?= $scoreColor ?> font-extrabold text-[22px]"><?= number_format($scoreNum, 1) ?></p>
                        <p class="text-gray-400 text-[11px]">/ 10 điểm</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-gray-50 rounded-lg p-5 text-center text-gray-500 text-[13px]">
                <i class="fa fa-clipboard text-3xl text-gray-300 mb-2 block"></i>
                Chưa có bài thi hoàn thành.
                <a href="do_quiz.php" class="text-[#1b4332] font-bold underline ml-1">Luyện thi ngay →</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Action Buttons -->
    <div class="mt-4 grid grid-cols-3 gap-3 pb-4">
        <a href="do_quiz.php" class="bg-[#1b4332] text-white rounded-xl p-4 text-center font-bold text-[13px] hover:bg-emerald-700 transition-colors shadow-md">
            <i class="fa fa-play-circle text-2xl block mb-1"></i>
            Luyện thi thử
        </a>
        <a href="historyPage.php" class="bg-blue-600 text-white rounded-xl p-4 text-center font-bold text-[13px] hover:bg-blue-700 transition-colors shadow-md">
            <i class="fa fa-chart-line text-2xl block mb-1"></i>
            Xem lịch sử
        </a>
        <div class="bg-white rounded-xl p-4 text-center shadow-md border border-gray-100">
            <i class="fa fa-database text-2xl text-gray-300 block mb-1"></i>
            <span class="text-gray-500 font-bold text-[12px]">950+ câu hỏi<br>sẵn sàng</span>
        </div>
    </div>
</div>
</div>
