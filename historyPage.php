<?php

require_once __DIR__ . '/backend/bootstrap.php';
require_login();

$history = QuizService::history((int) $_SESSION['user_id']);

require 'site.php';
load_top();
load_sitebar();

?>
<div class="column_2 rounded-xl h-full mb-16">
    <!-- Header -->
    <div class="header p-5 bg-[#1b523b] rounded-lg shadow-md font-bold">
        <h1 class="text-white font-bold text-[20px]">Lịch Sử Thi & Làm Bài</h1>
        <p class="text-emerald-400 text-[13px] mt-1">Theo dõi tiến độ ôn tập của bạn qua từng buổi học</p>
    </div>

    <?php if ($msg = flash('success')): ?>
        <p class="my-4 mx-auto w-fit p-3 bg-emerald-100 text-emerald-800 rounded-lg text-sm">✅ <?= e($msg) ?></p>
    <?php endif; ?>

    <?php if (empty($history)): ?>
        <div class="mt-8 text-center p-10">
            <i class="fa fa-clipboard-list text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 text-[15px] font-semibold">Chưa có lịch sử thi</p>
            <p class="text-gray-400 text-[13px] mt-1 mb-5">Bắt đầu làm bài thi thử đầu tiên để thấy kết quả ở đây.</p>
            <a href="do_quiz.php" class="bg-[#1b4332] text-white font-bold px-6 py-3 rounded-lg hover:bg-emerald-700 transition-colors text-[13px] inline-block">
                <i class="fa fa-play-circle mr-2"></i>Luyện thi ngay
            </a>
        </div>
    <?php else: ?>
        <!-- Summary Stats -->
        <?php
        $totalAttempts = count($history);
        $scores = array_column($history, 'score');
        $avgScore = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
        $maxScore = count($scores) > 0 ? max($scores) : 0;
        ?>
        <div class="grid grid-cols-3 gap-4 p-4 mt-2">
            <div class="bg-white rounded-xl p-4 shadow-md text-center border-t-4 border-[#1b4332]">
                <div class="text-[28px] font-extrabold text-[#1b4332]"><?= $totalAttempts ?></div>
                <p class="text-gray-500 text-[12px] font-semibold">Tổng lượt thi</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md text-center border-t-4 border-yellow-500">
                <div class="text-[28px] font-extrabold text-yellow-500"><?= number_format($avgScore, 1) ?></div>
                <p class="text-gray-500 text-[12px] font-semibold">Điểm trung bình</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md text-center border-t-4 border-pink-500">
                <div class="text-[28px] font-extrabold text-pink-500"><?= number_format($maxScore, 1) ?></div>
                <p class="text-gray-500 text-[12px] font-semibold">Điểm cao nhất</p>
            </div>
        </div>

        <!-- Table -->
        <div class="px-4 pb-4">
            <div class="bg-white shadow-md rounded-xl overflow-hidden">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 font-bold uppercase text-[11px]">
                            <th class="px-4 py-3 text-left">Ngày làm bài</th>
                            <th class="px-4 py-3 text-left">Tên đề thi</th>
                            <th class="px-4 py-3 text-center">Mã đề</th>
                            <th class="px-4 py-3 text-center">Kết quả</th>
                            <th class="px-4 py-3 text-center">Điểm số</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $i => $row): ?>
                            <?php
                            $scoreNum = (float) $row['score'];
                            $scoreClass = $scoreNum >= 8 ? 'bg-emerald-500' : ($scoreNum >= 5 ? 'bg-amber-500' : 'bg-red-400');
                            $date = date('d/m/Y', strtotime($row['attempt_date']));
                            $percent = $row['total_questions'] > 0
                                ? round(($row['correct_count'] / $row['total_questions']) * 100)
                                : 0;
                            ?>
                            <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors <?= $i % 2 === 0 ? '' : 'bg-gray-50/30' ?>">
                                <td class="px-4 py-3 text-gray-500"><?= e($date) ?></td>
                                <td class="px-4 py-3 text-[#1b4332] font-semibold"><?= e($row['title']) ?></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-mono text-[11px] bg-gray-100 px-2 py-0.5 rounded"><?= e($row['exam_code']) ?></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold"><?= (int) $row['correct_count'] ?>/<?= (int) $row['total_questions'] ?></span>
                                    <span class="text-gray-400 text-[11px] ml-1">(<?= $percent ?>%)</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="rounded-lg <?= $scoreClass ?> px-3 py-1 text-white font-bold">
                                        <?= number_format($scoreNum, 1) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-4">
                <a href="do_quiz.php" class="text-[#1b4332] font-bold text-[13px] hover:underline">
                    <i class="fa fa-plus-circle mr-1"></i>Thêm bài thi mới
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php load_footer(); ?>
