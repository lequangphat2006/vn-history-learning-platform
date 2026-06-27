# Backend — Cổng luyện thi Lịch sử

Backend PHP + SQLite, tích hợp trực tiếp với frontend PHP hiện có.

## Yêu cầu

- PHP 8.0+ (extension `pdo_sqlite`, `json`)

## Cài đặt

**Yêu cầu:** PHP 8+ có trong PATH (`php -v`), và Node.js (cho npm).

```bash
npm install
npm run db:init
```

Hoặc chỉ PHP:

```bash
php backend/init_db.php
```

Lệnh này tạo file `backend/database/lichsu.db`, bảng dữ liệu và **20 câu hỏi mẫu**.

**Tài khoản demo:** `admin` / `123456`

## Chạy ứng dụng

### Dùng npm (khuyến nghị)

```bash
npm run dev
```

- `npm run dev` — khởi tạo DB (nếu chưa có) + chạy server  
- `npm start` — chỉ chạy server (http://localhost:8080)  
- `npm run db:init` — chỉ tạo/cập nhật database  

Mở trình duyệt: http://localhost:8080/PublicHome.php

### PHP built-in server (không qua npm)

```bash
php -S localhost:8080
```

### XAMPP / Laragon

Copy project vào `htdocs`, truy cập qua Apache (ví dụ `http://localhost/FR_CK-main/login.php`).

## Cấu trúc backend

```
backend/
  bootstrap.php      # Khởi tạo session, autoload services
  config.php
  Database.php       # PDO SQLite
  init_db.php        # Tạo schema + seed
  helpers.php
  middleware/Auth.php
  services/
    AuthService.php  # Đăng ký, đăng nhập, đổi MK
    QuizService.php  # Tạo đề, làm bài, lịch sử, thống kê
  api/index.php      # REST JSON (tùy chọn)
```

## Luồng chức năng

| Trang frontend | Backend |
|----------------|---------|
| `login.php` | `AuthService::login` |
| `signUp.php` | `AuthService::register` |
| `ResetPassword.php` | `AuthService::changePassword` (cần đăng nhập) |
| `do_quiz.php` | `QuizService::startAttempt` → `take_quiz.php` |
| `take_quiz.php` | `QuizService::submitAttempt` |
| `historyPage.php` | `QuizService::history` |
| `index.php` (dashboard) | `QuizService::dashboardStats` |

## REST API (tùy chọn)

Base: `/backend/api/?action=...`

| Action | Method | Mô tả |
|--------|--------|--------|
| `health` | GET | Kiểm tra server |
| `login` | POST JSON `{username, password}` | Đăng nhập |
| `register` | POST JSON | Đăng ký |
| `history` | GET | Lịch sử (cần session) |
| `dashboard` | GET | Thống kê (cần session) |

## Ghi chú

- Ngân hàng đề hiện có ~20 câu; khi chọn nhiều câu hơn số câu đúng mức độ, hệ thống bổ sung câu cùng lớp (mức độ khác).
- Để thêm câu hỏi: insert vào bảng `questions` hoặc mở rộng `backend/init_db.php`.
