<?php

require_once __DIR__ . '/backend/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grade = parse_grade($_POST['sclass'] ?? 'Lớp 12');
    $difficulty = $_POST['sdifficulty'] ?? 'Nhận biết';
    $numQuestions = parse_question_count($_POST['snumquestion'] ?? '20 câu');
    $timeMinutes = parse_time_minutes($_POST['stime'] ?? '30 phút');

    $result = QuizService::startAttempt(
        (int) $_SESSION['user_id'],
        $grade,
        $difficulty,
        $numQuestions,
        $timeMinutes
    );

    if ($result['ok']) {
        if (!empty($result['warning'])) {
            flash('warning', $result['warning']);
        }
        redirect('take_quiz.php?id=' . $result['attempt_id']);
    }
    flash('error', $result['message']);
}

require 'site.php';
load_top();
load_sitebar();
?>

<div class="column_2 rounded-xl h-full mb-16">
    <!-- Header -->
    <div class="header p-5 rounded-lg shadow-md bg-[#1b523b] font-bold">
        <h1 class="text-white text-[20px]"><b>Kho Đề Luyện Thi</b></h1>
        <p class="text-emerald-400 text-[13px] mt-1">Hệ thống đề thi thử bám sát ma trận cấu trúc đề tốt nghiệp THPT của Bộ GD&ĐT</p>
    </div>

    <?php if ($msg = flash('error')): ?>
        <p class="mx-4 mt-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">❌ <?= e($msg) ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <!-- Bộ lọc -->
        <div class="body max-w-4xl px-4 pt-10 pb-6 mx-auto">
            <h2 class="text-center text-[#1b4332] font-bold text-[15px] mb-8 uppercase tracking-wide">
                Chọn thông số để tạo đề thi ngẫu nhiên
            </h2>

            <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6 text-center">

                <!-- Lớp -->
                <div class="filter-card">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fa fa-school text-indigo-500"></i>
                    </div>
                    <p class="uppercase font-bold text-[12px] text-gray-600 mb-2">Lớp</p>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-indigo-400">
                        <select class="text-gray-700 w-full text-center py-2 px-2 focus:outline-none" name="sclass">
                            <option selected>Lớp 12</option>
                        </select>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">963 câu có sẵn</p>
                </div>

                <!-- Mức độ -->
                <div class="filter-card">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fa fa-layer-group text-emerald-600"></i>
                    </div>
                    <p class="uppercase font-bold text-[12px] text-gray-600 mb-2">Mức độ</p>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-emerald-500">
                        <select class="text-gray-700 w-full text-center py-2 px-2 focus:outline-none" name="sdifficulty">
                            <option>Nhận biết</option>
                            <option>Thông hiểu</option>
                            <option>Vận dụng</option>
                        </select>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">412 / 400 / 151 câu</p>
                </div>

                <!-- Số câu -->
                <div class="filter-card">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fa fa-list-ol text-red-400"></i>
                    </div>
                    <p class="uppercase font-bold text-[12px] text-gray-600 mb-2">Số câu hỏi</p>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-red-400">
                        <select class="text-gray-700 w-full text-center py-2 px-2 focus:outline-none" name="snumquestion">
                            <option>5 câu</option>
                            <option>10 câu</option>
                            <option selected>20 câu</option>
                            <option>40 câu</option>
                        </select>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Đề ngẫu nhiên</p>
                </div>

                <!-- Thời gian -->
                <div class="filter-card">
                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fa fa-clock text-amber-500"></i>
                    </div>
                    <p class="uppercase font-bold text-[12px] text-gray-600 mb-2">Thời gian</p>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-amber-400">
                        <select class="text-gray-700 w-full text-center py-2 px-2 focus:outline-none" name="stime">
                            <option>15 phút</option>
                            <option selected>30 phút</option>
                            <option>45 phút</option>
                            <option>50 phút</option>
                            <option>60 phút</option>
                            <option>90 phút</option>
                        </select>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Tự động nộp khi hết giờ</p>
                </div>
            </div>
        </div>

        <!-- Info box -->
        <div class="mx-4 mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-[13px] text-emerald-800 flex gap-3">
            <i class="fa fa-info-circle text-emerald-500 mt-0.5 flex-shrink-0"></i>
            <p>Hệ thống sẽ tự động chọn ngẫu nhiên số câu hỏi từ ngân hàng đề theo mức độ bạn chọn. Mỗi lần thi là một bộ câu hỏi hoàn toàn khác nhau.</p>
        </div>

        <!-- Submit -->
        <div class="flex justify-center pb-8">
            <button type="submit"
                class="bg-[#1b4332] text-white font-extrabold px-12 py-4 rounded-xl text-[15px] hover:bg-emerald-700 transition-colors shadow-lg uppercase tracking-wide flex items-center gap-3">
                <i class="fa fa-play-circle text-xl"></i>
                Bắt đầu làm bài
            </button>
        </div>
    </form>
</div>

<?php load_footer(); ?>
