<?php

require_once __DIR__ . '/backend/bootstrap.php';

$error = null;
$success = null;
$isLoggedIn = !empty($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($isLoggedIn) {
        $result = AuthService::changePassword(
            (int) $_SESSION['user_id'],
            $_POST['npass'] ?? '',
            $_POST['npassm'] ?? '',
            $_POST['nlpassm'] ?? ''
        );
    } else {
        $result = AuthService::resetPassword(
            trim($_POST['username'] ?? ''),
            $_POST['npass'] ?? '',
            $_POST['npassm'] ?? '',
            $_POST['nlpassm'] ?? ''
        );
    }

    if ($result['ok']) {
        $success = $result['message'];
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="./src/output.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
</head>

<body class="bg-[#f3f6f4] flex items-center justify-center min-h-screen">
    <div class="pass-change-form bg-[#ffff] min-w-md shadow-md rounded-lg min-h-100vh">
        <div class="form-header bg-[#1b4332] text-white text-center p-6 rounded-t-lg font-bold">
            <h1>CỔNG LUYỆN THI LỊCH SỬ</h1>
            <p class="text-[11px] text-emerald-200/70 ">Trường Đại Học Quy Nhơn - TTNT K47</p>
        </div>
        <div class="font-bold bg-[#e9c46a] p-px"></div>
        <div class="form-body px-8 py-6">
            <div class="header-bdform text-[#1b4332] text-[18px] flex justify-center p-4 font-bold">
                <lable><b>ĐỔI MẬT KHẨU</b></lable>
            </div>
            <p class="text-[11px] text-slate-400 text-center mb-3">Nhập mật khẩu hiện tại để xác nhận danh tính trước khi đặt mật khẩu mới.</p>
            <?php if ($success): ?>
                <p class="mb-3 p-2 text-sm text-emerald-800 bg-emerald-100 rounded-lg"><?= e($success) ?></p>
            <?php endif; ?>
            <?php if ($error): ?>
                <p class="mb-3 p-2 text-sm text-red-800 bg-red-100 rounded-lg"><?= e($error) ?></p>
            <?php endif; ?>
            <form action="" method="post">
                <?php if (!$isLoggedIn): ?>
                <div class="form-group mb-4 text-[11px]">
                    <label class="mb-4 input-field text-slate-500 font-semibold"><b>Tên đăng nhập</b></label>
                    <label class="text-red-500">*</label>
                    <input class="p-1.5 w-full border border-[#64748b] rounded-lg text-slate-800" type="text" name="username" placeholder="Nhập tên đăng nhập...">
                </div>
                <?php endif; ?>
                <div class="form-group mb-4 text-[11px]">
                    <label class=" mb-4 input-field text-slate-500 font-semibold"><b>Mật khẩu hiện tại</b></label>
                    <label class="text-red-500">*</label>
                    <input class="p-1.5 w-full border border-[#64748b] rounded-lg text-slate-800" type="password" name="npass" placeholder="Nhập mật khẩu hiện tại...">
                </div>
                <div class="form-group mb-4 text-[11px]">
                    <label class="mb-4 input-field text-slate-500 font-semibold"><b>Mật khẩu mới</b></label>
                    <label class="text-red-500">*</label>
                    <input class="p-1.5 w-full border border-[#64748b] rounded-lg text-slate-800" type="password" name="npassm" placeholder="Tối thiểu 6 ký tự...">
                </div>
                <div class="form-group mb-4 text-[11px]">
                    <label class="mb-4 input-field text-slate-500 font-semibold"><b>Nhập lại mật khẩu</b></label>
                    <label class="text-red-500">*</label>
                    <input class=" p-1.5 w-full border border-[#64748b] rounded-lg text-slate-800" type="password" name="nlpassm" placeholder="Gõ lại mật khẩu mới...">
                </div>
                <div class="bg-[#1b4332] m-5 rounded-lg flex justify-center py-3">
                    <button type="submit" class="text-[13px] font-bold text-white"><b>CẬP NHẬT MẬT KHẨU</b></button>
                </div>
                <div class="form-options text-[12px] flex justify-center">
                    <a href="login.php"><span><b>Đăng nhập tại đây</b></span></a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
