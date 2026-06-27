# 🎓 Cổng Luyện Thi Lịch Sử THPT

> Dự án **Nghiên cứu khoa học sinh viên** — Khoa CNTT, Trường ĐH Quy Nhơn · Lớp TTNT K47

---

## 📋 Giới thiệu

Hệ thống ôn thi Lịch sử trực tuyến dành cho học sinh THPT, với ngân hàng **963 câu hỏi** lớp 12 phân cấp theo 3 mức độ tư duy:

| Mức độ | Số câu | Tỷ lệ |
|--------|--------|--------|
| Nhận biết | 404 | 42.5% |
| Thông hiểu | 397 | 41.8% |
| Vận dụng | 149 | 15.7% |
| **Tổng** | **950** | **100%** |

---

## 🚀 Cài đặt

### Yêu cầu
- PHP 8.0+
- SQLite3
- Web server (Apache / Nginx / PHP built-in)

### Các bước cài đặt

**Bước 1: Khởi tạo database**
```bash
php backend/init_db.php
```

Lệnh này sẽ tự động:
- Tạo các bảng (users, questions, exam_attempts, attempt_questions)
- Import toàn bộ 963 câu hỏi từ file JSON
- Tạo tài khoản admin mặc định

**Bước 2: Chạy server**
```bash
php -S localhost:8000
```

**Bước 3: Truy cập**
- Trang chủ: http://localhost:8000/PublicHome.php
- Đăng nhập: http://localhost:8000/login.php

### Tài khoản demo
```
Username: admin
Password: 123456
```

---

## 📁 Cấu trúc thư mục

```
vn-history/
├── backend/
│   ├── data/
│   │   ├── nhan_biet.json      # 412 câu Nhận biết
│   │   ├── thong_hieu.json     # 400 câu Thông hiểu
│   │   └── van_dung.json       # 151 câu Vận dụng
│   ├── services/
│   │   ├── AuthService.php
│   │   └── QuizService.php
│   ├── middleware/Auth.php
│   ├── Database.php
│   ├── bootstrap.php
│   ├── config.php
│   ├── helpers.php
│   ├── init_db.php             # ← Chạy cái này để cài đặt
│   └── import_questions.php    # Re-import riêng nếu cần
├── widget/
│   ├── topbar.php
│   ├── sidebar.php
│   ├── content.php
│   └── footer.php
├── PublicHome.php              # Trang chủ công khai
├── login.php
├── signUp.php
├── ResetPassword.php
├── index.php                   # Dashboard sau đăng nhập
├── do_quiz.php                 # Cấu hình đề thi
├── take_quiz.php               # Làm bài thi
├── historyPage.php             # Lịch sử thi
└── exit.php
```

---

## 🔄 Re-import câu hỏi

Nếu muốn import lại câu hỏi (ví dụ sau khi cập nhật JSON):

```bash
php backend/import_questions.php --replace
```

---

## 🛠️ Tính năng

- ✅ Đăng ký / Đăng nhập / Đổi mật khẩu
- ✅ Tạo đề thi ngẫu nhiên theo mức độ
- ✅ Đếm ngược thời gian thực, tự động nộp khi hết giờ
- ✅ Chấm điểm tức thì (thang 10)
- ✅ Lịch sử thi với thống kê: tổng lượt, điểm TB, điểm cao nhất
- ✅ Dashboard học viên (số ngày học, lượt thi, điểm tốt nhất)

---

*Dự án phi lợi nhuận — phục vụ học tập miễn phí*
