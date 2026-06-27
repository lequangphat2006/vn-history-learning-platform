<?php

require_once __DIR__ . '/backend/bootstrap.php';

if (!empty($_SESSION['user_id'])) {
    redirect('index.php');
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = AuthService::register($_POST);
    if ($result['ok']) {
        flash('success', $result['message']);
        redirect('login.php');
    }
    $error = $result['message'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Đăng ký</title>
</head>

<body class="bg-[#f3f6f4] flex items-center justify-center min-h-screen">
    <div class="sign-up-form min-w-2xl bg-[#ffff] rounded-lg min-h-100vh shadow-md">
        <div class="form-header bg-[#1b4332] text-white rounded-t-lg p-6 font-bold text-center">
            <h1><b>ĐĂNG KÝ TÀI KHOẢN HỌC VIÊN</b></h1>
            <p class="text-[11px] text-emerald-200/70">Tạo tài khoản ôn thi Lịch sử miễn phí</p>
        </div>
        <div class="line bg-[#e9c46a] p-px font-bold w-full"></div>
        <div class="form-body"></div>
        <?php if ($error): ?>
            <p class="mx-6 mt-4 p-2 text-sm text-red-800 bg-red-100 rounded-lg"><?= e($error) ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <div class="grid grid-cols-2 gap-3">
                <div class="column-1 px-6 py-4">
                    <label class="text-[#1b4332] text-[15px] flex justify-center font-bold"><b>1. THÔNG TIN TÀI KHOẢN</b></label>
                    <div class="text-[13px] mb-4 input-field text-slate-500 font-semibold">
                        <label class="mb-1.5"><b>Tên đăng nhập</b></label>
                        <label class="text-red-500">*</label>
                        <input class="w-full border border-[#64748b] rounded-lg p-1.5 flex justify-center" type="text" name="tdn" placeholder="Nhập username...">
                    </div>
                    <div class="text-[13px] mb-4 input-field text-slate-500 font-semibold">
                        <label class="mb-1.5"><b>Mật khẩu</b></label>
                        <label class="text-red-500">*</label>
                        <input class="w-full border border-[#64748b] rounded-lg p-1.5 flex justify-center" type="password" name="pass" placeholder="Tối thiểu 6 ký tự...">
                    </div>
                    <div class="text-[13px] mb-4 input-field text-slate-500 font-semibold">
                        <label class="mb-1.5"><b>Nhập lại mật khẩu</b></label>
                        <label class="text-red-500">*</label>
                        <input class="w-full border border-[#64748b] rounded-lg p-1.5 flex justify-center " type="password" name="nlpass" placeholder="Gõ lại mật khẩu...">
                    </div>
                </div>
                <div class="column-2 px-6 py-4">
                    <label class="text-[#1b4332] text-[15px] flex justify-center font-bold"><b>2. THÔNG TIN CÁ NHÂN</b></label>
                    <div class="text-[13px] mb-4 input-field text-slate-500 font-semibold">
                        <label class="mb-1.5"><b>Họ và tên</b></label>
                        <label class="text-red-500">*</label>
                        <input class="w-full border border-[#64748b] rounded-lg p-1.5 flex justify-center" type="text" name="thvt" placeholder="Nhập Họ và tên...">
                    </div>
                    <div class="text-[13px] mb-4 input-field text-slate-500 font-semibold grid grid-cols-2 gap-2">
                        <div class="mr-1">
                            <label><b>Ngày sinh</b></label>
                            <input class="border border-[#64748b] rounded-lg p-1.5 flex justify-center" type="date" name="date">
                        </div>
                        <div>
                            <label><b>Giới tính</b></label>
                            <div class="mt-1.5">
                                <input class="border border-[#64748b]" type="radio" name="sex" value="Nam">
                                <label>Nam</label>
                                <input class="border border-[#64748b]" type="radio" name="sex" value="Nữ">
                                <span>Nữ</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-[13px] mb-4 input-field text-slate-500 font-semibold">
                        <label class="mb-1.5"><b>Địa chỉ email</b></label>
                        <label class="text-red-500">*</label>
                        <input class="w-full border border-[#64748b] rounded-lg p-1.5 flex justify-center" type="email" name="email" placeholder="email@gmail.com">
                    </div>
                </div>
            </div>
            <div class="mx-8 submit bg-[#1b4332] text-white text-center font-bold rounded-lg flex justify-center py-3 hover:bg-emerald-700">
                <button type="submit"><b>ĐĂNG KÝ NGAY</b></button>
            </div>
        </form>
        <div class="form-options text-[12px] flex justify-center text-[#1b4332] m-5">
            <span class="mr-1 text-slate-500">Đã có tài khoản rồi?</span>
            <a class="hover:text-gray-500" href="login.php"><span><b>Đăng nhập tại đây</b></span></a>
        </div>
    </div>
</body>

</html>
