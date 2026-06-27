<?php
require_once __DIR__ . '/backend/bootstrap.php';

if (!empty($_SESSION['user_id'])) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Cổng Luyện Thi Lịch Sử THPT - ĐH Quy Nhơn</title>
    <style>
        .gradient-hero { background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 50%, #1b523b 100%); }
        .card-hover { transition: transform .2s, box-shadow .2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,.15); }
        .stat-number { font-size: 2.5rem; font-weight: 800; line-height: 1; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp .6s ease forwards; }
        .delay-1 { animation-delay: .1s; opacity:0; }
        .delay-2 { animation-delay: .2s; opacity:0; }
        .delay-3 { animation-delay: .3s; opacity:0; }
    </style>
</head>
<body class="bg-[#f3f6f4] min-h-screen flex flex-col">

<!-- ====== NAVBAR ====== -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center gap-2 text-[#1b4332]">
            <i class="fa fa-graduation-cap text-2xl"></i>
            <div>
                <span class="font-extrabold text-[18px] uppercase tracking-wide">Cổng Luyện Thi Lịch Sử</span>
                <p class="text-gray-400 text-[11px] leading-none">Trường ĐH Quy Nhơn · TTNT K47</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="login.php" class="border border-[#1b4332] text-[#1b4332] font-bold px-4 py-2 rounded-lg text-[13px] hover:bg-[#1b4332] hover:text-white transition-colors">
                <i class="fa fa-sign-in-alt mr-1"></i>Đăng nhập
            </a>
            <a href="signUp.php" class="bg-[#1b4332] text-white font-bold px-4 py-2 rounded-lg text-[13px] hover:bg-emerald-700 transition-colors">
                <i class="fa fa-user-plus mr-1"></i>Đăng ký
            </a>
        </div>
    </div>
</nav>

<!-- ====== HERO ====== -->
<section class="gradient-hero text-white py-20 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <div class="fade-up">
            <span class="bg-yellow-400 text-[#1b4332] text-[12px] font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                100% Miễn phí
            </span>
        </div>
        <h1 class="mt-5 text-4xl md:text-5xl font-extrabold leading-tight fade-up delay-1">
            Luyện Thi Lịch Sử THPT<br>
            <span class="text-yellow-300">Đạt Điểm Cao Ngay Hôm Nay!</span>
        </h1>
        <p class="mt-5 text-emerald-200 text-lg max-w-2xl mx-auto fade-up delay-2">
            Ngân hàng đề thi chuyên Lịch sử lớp 12 với <strong class="text-white">hơn 950 câu hỏi</strong>
            phân cấp theo 3 mức độ: Nhận biết — Thông hiểu — Vận dụng.
            Bám sát cấu trúc đề tốt nghiệp THPT của Bộ GD&ĐT.
        </p>
        <div class="mt-8 flex flex-wrap gap-4 justify-center fade-up delay-3">
            <a href="signUp.php" class="bg-yellow-400 text-[#1b4332] font-extrabold px-8 py-3 rounded-xl text-[16px] hover:bg-yellow-300 transition-colors shadow-lg">
                <i class="fa fa-rocket mr-2"></i>Bắt đầu ngay — Miễn phí
            </a>
            <a href="login.php" class="border-2 border-white text-white font-bold px-8 py-3 rounded-xl text-[16px] hover:bg-white hover:text-[#1b4332] transition-colors">
                <i class="fa fa-sign-in-alt mr-2"></i>Đã có tài khoản
            </a>
        </div>
        <p class="mt-4 text-emerald-300 text-[12px] fade-up delay-3">Demo: <strong class="text-white">admin</strong> / <strong class="text-white">123456</strong></p>
    </div>
</section>

<!-- ====== STATS ====== -->
<section class="bg-white py-12 shadow-sm">
    <div class="max-w-4xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div class="card-hover p-4 rounded-xl border border-gray-100">
            <div class="stat-number text-[#1b4332]">950+</div>
            <p class="text-gray-500 text-[13px] mt-1 font-semibold">Câu hỏi</p>
        </div>
        <div class="card-hover p-4 rounded-xl border border-gray-100">
            <div class="stat-number text-blue-500">404</div>
            <p class="text-gray-500 text-[13px] mt-1 font-semibold">Nhận biết</p>
        </div>
        <div class="card-hover p-4 rounded-xl border border-gray-100">
            <div class="stat-number text-yellow-500">397</div>
            <p class="text-gray-500 text-[13px] mt-1 font-semibold">Thông hiểu</p>
        </div>
        <div class="card-hover p-4 rounded-xl border border-gray-100">
            <div class="stat-number text-red-500">149</div>
            <p class="text-gray-500 text-[13px] mt-1 font-semibold">Vận dụng</p>
        </div>
    </div>
</section>

<!-- ====== FEATURES ====== -->
<section class="py-16 px-4">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-center text-2xl font-extrabold text-[#1b4332] mb-10">Tại sao chọn hệ thống của chúng tôi?</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="card-hover bg-white rounded-xl p-6 shadow-md text-center">
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-layer-group text-[#1b4332] text-2xl"></i>
                </div>
                <h3 class="font-bold text-[#1b4332] text-[16px] mb-2">Phân cấp 3 mức độ</h3>
                <p class="text-gray-500 text-[13px]">
                    Câu hỏi được phân loại rõ ràng theo Nhận biết, Thông hiểu, Vận dụng — đúng theo ma trận đề thi Bộ GD&ĐT.
                </p>
            </div>
            <div class="card-hover bg-white rounded-xl p-6 shadow-md text-center">
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-clock text-blue-500 text-2xl"></i>
                </div>
                <h3 class="font-bold text-[#1b4332] text-[16px] mb-2">Đếm ngược thời gian</h3>
                <p class="text-gray-500 text-[13px]">
                    Đồng hồ đếm ngược theo thời gian thực, tự động nộp bài khi hết giờ — rèn kỹ năng quản lý thời gian thi.
                </p>
            </div>
            <div class="card-hover bg-white rounded-xl p-6 shadow-md text-center">
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-chart-bar text-yellow-500 text-2xl"></i>
                </div>
                <h3 class="font-bold text-[#1b4332] text-[16px] mb-2">Theo dõi tiến độ</h3>
                <p class="text-gray-500 text-[13px]">
                    Lưu toàn bộ lịch sử làm bài, điểm số, mã đề — theo dõi tiến độ ôn thi qua từng ngày.
                </p>
            </div>
            <div class="card-hover bg-white rounded-xl p-6 shadow-md text-center">
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-random text-purple-500 text-2xl"></i>
                </div>
                <h3 class="font-bold text-[#1b4332] text-[16px] mb-2">Đề ngẫu nhiên</h3>
                <p class="text-gray-500 text-[13px]">
                    Hệ thống tự động xáo trộn câu hỏi từ ngân hàng đề, giúp mỗi lần thi là một đề mới.
                </p>
            </div>
            <div class="card-hover bg-white rounded-xl p-6 shadow-md text-center">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-sliders-h text-red-500 text-2xl"></i>
                </div>
                <h3 class="font-bold text-[#1b4332] text-[16px] mb-2">Tùy chỉnh linh hoạt</h3>
                <p class="text-gray-500 text-[13px]">
                    Tự chọn mức độ khó, số câu hỏi (5–40 câu) và thời gian làm bài theo nhu cầu ôn tập.
                </p>
            </div>
            <div class="card-hover bg-white rounded-xl p-6 shadow-md text-center">
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-shield-alt text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-[#1b4332] text-[16px] mb-2">Hoàn toàn miễn phí</h3>
                <p class="text-gray-500 text-[13px]">
                    Không quảng cáo, không phí ẩn — dự án phi lợi nhuận do sinh viên TTNT K47 ĐH Quy Nhơn xây dựng.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ====== DIFFICULTY BREAKDOWN ====== -->
<section class="bg-[#1b4332] py-12 px-4 text-white">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-2xl font-extrabold mb-3">Cơ cấu câu hỏi</h2>
        <p class="text-emerald-300 text-[13px] mb-8">Toàn bộ câu hỏi Lịch sử lớp 12 — bám sát cấu trúc đề tốt nghiệp THPT</p>
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white/10 rounded-xl p-5 card-hover">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa fa-eye text-white text-xl"></i>
                </div>
                <div class="text-3xl font-extrabold text-blue-300">404</div>
                <p class="font-bold mt-1">Nhận biết</p>
                <p class="text-emerald-300 text-[12px] mt-1">42.8% tổng đề</p>
            </div>
            <div class="bg-white/10 rounded-xl p-5 card-hover">
                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa fa-brain text-white text-xl"></i>
                </div>
                <div class="text-3xl font-extrabold text-yellow-300">397</div>
                <p class="font-bold mt-1">Thông hiểu</p>
                <p class="text-emerald-300 text-[12px] mt-1">41.5% tổng đề</p>
            </div>
            <div class="bg-white/10 rounded-xl p-5 card-hover">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa fa-lightbulb text-white text-xl"></i>
                </div>
                <div class="text-3xl font-extrabold text-red-300">149</div>
                <p class="font-bold mt-1">Vận dụng</p>
                <p class="text-emerald-300 text-[12px] mt-1">15.7% tổng đề</p>
            </div>
        </div>
    </div>
</section>

<!-- ====== HOW IT WORKS ====== -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-center text-2xl font-extrabold text-[#1b4332] mb-10">Cách sử dụng — chỉ 3 bước</h2>
        <div class="space-y-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-[#1b4332] rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                    <span class="text-white font-bold">1</span>
                </div>
                <div>
                    <h3 class="font-bold text-[#1b4332] text-[15px]">Đăng ký tài khoản miễn phí</h3>
                    <p class="text-gray-500 text-[13px] mt-1">Tạo tài khoản trong vòng 30 giây với tên đăng nhập và mật khẩu.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                    <span class="text-white font-bold">2</span>
                </div>
                <div>
                    <h3 class="font-bold text-[#1b4332] text-[15px]">Chọn mức độ và bắt đầu thi</h3>
                    <p class="text-gray-500 text-[13px] mt-1">Chọn mức độ (Nhận biết / Thông hiểu / Vận dụng), số câu và thời gian — hệ thống tự tạo đề ngẫu nhiên.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                    <span class="text-white font-bold">3</span>
                </div>
                <div>
                    <h3 class="font-bold text-[#1b4332] text-[15px]">Xem kết quả và cải thiện</h3>
                    <p class="text-gray-500 text-[13px] mt-1">Nộp bài, xem điểm số ngay lập tức và theo dõi lịch sử tiến bộ của bạn qua từng buổi ôn tập.</p>
                </div>
            </div>
        </div>
        <div class="text-center mt-10">
            <a href="signUp.php" class="bg-[#1b4332] text-white font-extrabold px-10 py-3 rounded-xl text-[15px] hover:bg-emerald-700 transition-colors inline-block shadow-lg">
                <i class="fa fa-rocket mr-2"></i>Bắt đầu ngay — Hoàn toàn miễn phí
            </a>
        </div>
    </div>
</section>

<!-- ====== FOOTER ====== -->
<footer class="bg-[#1b4332] text-white py-8 px-4 mt-auto">
    <div class="max-w-4xl mx-auto text-center">
        <div class="flex items-center justify-center gap-2 mb-3">
            <i class="fa fa-graduation-cap text-xl"></i>
            <span class="font-bold text-[15px]">CỔNG LUYỆN THI LỊCH SỬ THPT</span>
        </div>
        <p class="text-emerald-300 text-[12px] mb-4">Trường Đại học Quy Nhơn · Khoa Công nghệ thông tin · Lớp TTNT K47</p>
        <div class="flex justify-center gap-6 text-[12px] text-emerald-200 mb-4">
            <span><i class="fa-solid fa-mobile-screen-button mr-1"></i>09.xxxx.xxxx</span>
            <span><i class="fa-regular fa-envelope mr-1"></i>lichsu.qnu@gmail.com</span>
        </div>
        <div class="border-t border-emerald-700 pt-4">
            <p class="text-emerald-400 text-[11px]">© 2024 TTNT K47 — Dự án học thuật phi lợi nhuận</p>
        </div>
    </div>
</footer>

</body>
</html>
