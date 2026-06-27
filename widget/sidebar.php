<div class="column_1 bg-[#1b4332] rounded-md flex flex-col shadow-xl h-full w-full">
    <div class="text-[18px] header font-bold text-emerald-500 p-4 flex flex-col items-center text-shadow-md">
        <p><b>HỌC VIÊN DASHBOARD</b></p>
        <p class="text-[13px] text-emerald-300">Thực hành và ôn thi</p>
    </div>
    <div class="body flex-1 w-full flex flex-col">
        <div class="menu-field mb-4 p-3 text-emerald-200">
            <a href="index.php" class="flex items-center gap-2 text-[14px] my-2 p-3 rounded-lg hover:bg-emerald-600 hover:text-white hover:font-bold transition-colors">
                <i class="fa fa-home w-5 text-center"></i>
                <span>Trang chủ</span>
            </a>
            <a href="do_quiz.php" class="flex items-center gap-2 text-[14px] my-2 p-3 rounded-lg hover:bg-emerald-600 hover:text-white hover:font-bold transition-colors">
                <i class="fa fa-check-square w-5 text-center"></i>
                <span>Luyện thi thử</span>
            </a>
            <a href="historyPage.php" class="flex items-center gap-2 text-[14px] my-2 p-3 rounded-lg hover:bg-emerald-600 hover:text-white hover:font-bold transition-colors">
                <i class="fa fa-history w-5 text-center"></i>
                <span>Lịch sử thi</span>
            </a>
            <a href="ResetPassword.php" class="flex items-center gap-2 text-[14px] my-2 p-3 rounded-lg hover:bg-emerald-600 hover:text-white hover:font-bold transition-colors">
                <i class="fa fa-key w-5 text-center"></i>
                <span>Đổi mật khẩu</span>
            </a>
        </div>

        <!-- Thống kê nhanh -->
        <div class="mx-3 mb-4 bg-emerald-900/50 rounded-lg p-3 text-[12px] text-emerald-200">
            <p class="font-bold text-emerald-400 mb-2">📚 Ngân hàng đề</p>
            <div class="space-y-1">
                <div class="flex justify-between">
                    <span>Nhận biết</span>
                    <span class="font-bold text-blue-300">404 câu</span>
                </div>
                <div class="flex justify-between">
                    <span>Thông hiểu</span>
                    <span class="font-bold text-yellow-300">397 câu</span>
                </div>
                <div class="flex justify-between">
                    <span>Vận dụng</span>
                    <span class="font-bold text-red-300">149 câu</span>
                </div>
                <div class="border-t border-emerald-700 pt-1 mt-1 flex justify-between">
                    <span>Tổng cộng</span>
                    <span class="font-bold text-white">950 câu</span>
                </div>
            </div>
        </div>

        <a href="exit.php" class="flex items-center gap-2 text-red-400 text-[14px] p-3 mx-3 mb-4 rounded-lg hover:bg-red-900 hover:text-white hover:font-bold transition-colors mt-auto">
            <i class="fa fa-sign-out w-5 text-center"></i>
            <span>Đăng xuất</span>
        </a>
    </div>
</div>
