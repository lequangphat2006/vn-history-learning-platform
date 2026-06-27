<?php
if (empty($_SESSION['user_id'])) {
    $_SESSION['user'] = 'Khách';
    $_SESSION['role'] = 'khách';
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Trang chủ</title>
</head>

<body class="bg-[#f3f6f4] min-h-screen flex flex-col">
    <div class="main bg-[#1b4332] rounded-t-lg py-4 px-6 flex justify-between items-center text-[20px]">
        <div class="text-white text-shadow-sm">
            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
            <span><a href="index.php"><b>HỆ THỐNG ÔN THI LỊCH SỬ THPT</b></a></span>
        </div>
        <div class="text-white text-[13px]">
            <span>Xin chào</span>
            <span class="text-yellow-400"><?php echo '<b>' . e($_SESSION['user'] ?? '') . '</b>'; ?></span>
            <button class="text-[11px] rounded-2xl bg-[rgb(38,96,71)] p-1"><?php echo e($_SESSION['role'] ?? ''); ?></button>
        </div>
    </div>
    <div class="p-px font-bold bg-[#e9c46a] "></div>
    <div class="segment-page grid grid-cols-[280px_1fr] p-4 gap-4 flex-1">
