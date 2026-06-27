<?php

require_once __DIR__ . '/backend/bootstrap.php';

if (!empty($_SESSION['user_id'])) {
    redirect('index.php');
}

$error = null;
$success = flash('success');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = AuthService::login($_POST['tdn'] ?? '', $_POST['pass'] ?? '');
    if ($result['ok']) {
        redirect('index.php');
    }
    $error = $result['message'];
}

$rememberUser = $_COOKIE['remember_user'] ?? '';
?>
<!doctype html>
<html lang="vi">

<head>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <meta charset="UTF-8" />
  <title>Đăng nhập hệ thống</title>
</head>

<body class="bg-[#f3f6f4] flex items-center justify-center min-h-screen">
  <div class="login-card bg-[#ffff] min-w-md shadow-md rounded-lg ">
    <div class="login-header bg-[#1b4332] text-white p-6 rounded-t-lg text-center font-bold">
      <h1><b>CỔNG LUYỆN THI LỊCH SỬ</b></h1>
      <p class="text-[11px] text-emerald-200/70">Trường Đại Học Quy Nhơn - TTNT K47</p>
    </div>
    <div class="bg-[#e9c46a] font-bold w-full p-px"></div>
    <div class="login-body px-8 py-6">
      <div class="text-[#1b4332] p-4 flex justify-center text-[18px] font-bold">
        <h2><b>ĐĂNG NHẬP HỆ THỐNG</b></h2>
      </div>
      <?php if ($success): ?>
        <p class="mb-3 p-2 text-sm text-emerald-800 bg-emerald-100 rounded-lg"><?= e($success) ?></p>
      <?php endif; ?>
      <?php if ($error): ?>
        <p class="mb-3 p-2 text-sm text-red-800 bg-red-100 rounded-lg"><?= e($error) ?></p>
      <?php endif; ?>
      <form action="" method="post">
        <div class="form-group mb-4 text-[13px]">
          <label class="mb-1.5 text-slate-500 font-semibold"><b>Tài khoản (Tên đăng nhập)</b></label>
          <input class="p-1.5 w-full border border-gray-400 rounded-lg" type="text" name="tdn" value="<?= e($rememberUser) ?>" placeholder="Nhập tên đăng nhập...">
        </div>
        <div class="form-group mb-4 text-[13px]">
          <label class=" font-semibold text-slate-500 mb-1.5 "><b>Mật khẩu</b></label>
          <input class="p-1.5 w-full border border-[#64748b] rounded-lg text-slate-800" type="password" name="pass" placeholder="Nhập mật khẩu...">
        </div>
        <div class="form-options text-[12px] flex justify-between">
          <div class="checkbox">
            <input type="checkbox" name="cbpass" value="1">
            <label>Nhớ mật khẩu</label>
          </div>
          <a class="text-[#1b4332] hover:text-gray-800" href="ResetPassword.php">Đổi mật khẩu</a>
        </div>
        <div class="bg-[#1b4332] m-5 rounded-lg flex justify-center py-3 hover:bg-emerald-700">
          <button type="submit" class="text-[13px] font-bold text-white">ĐĂNG NHẬP</button>
        </div>
      </form>
      <div class="form-options text-[12px] flex justify-center">
        <span class="mr-1 text-slate-500">Chưa có tài khoản? </span>
        <a class="hover:text-gray-500" href="signUp.php"><span><b>Đăng ký tại đây</b></span></a>
      </div>
      <p class="text-[11px] text-center text-slate-400 mt-4">Demo: admin / 123456</p>
    </div>

  </div>

</body>

</html>
