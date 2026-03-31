# 📁 CẤU TRÚC THƯ MỤC DỰ ÁN ANTIGRAVITY (PHP MVC)

Cấu trúc này được thiết kế để đảm bảo tính bảo mật (tách biệt mã nguồn và thư mục `public`) và dễ bảo trì cho các dự án sử dụng PHP thuần hoặc Framework nhẹ, kết hợp HTML/CSS/JS.

---

## 🌳 SƠ ĐỒ THƯ MỤC (DIRECTORY TREE)

```text
antigravity-project/
│
├── index.php               # 🌐 Entry point duy nhất (Nằm ở gốc để deploy lên hosting)
├── .htaccess               # Cấu hình rewrite URL về index.php
├── css/                    # Các file CSS 
├── js/                     # Các file JavaScript
├── images/                 # Hình ảnh tĩnh
│
├── app/                    # 🔒 Logic ứng dụng (Được bảo vệ bằng .htaccess nội bộ)
│   ├── Controllers/        # Xử lý request, gọi Model, trả về View
│   ├── Models/             # Tương tác với Database (MySQL - PDO)
│   ├── Views/              # Các file giao diện (.php) chứa HTML, gọi CSS/JS
│   ├── Core/               # Các class lõi (Database Connection, Router, BaseController)
│   └── Helpers/            # Các hàm dùng chung (format_date, sanitize_input...)
│
├── config/                 # ⚙️ Các file cấu hình hệ thống
│   └── database.php        # Thông tin kết nối PDO
│
├── routes/                 # 🔀 Định tuyến (URL mapping)
│   └── web.php             # Khai báo URL nào gọi Controller nào
│
├── storage/                # 🗄️ Thư mục chứa file tạm
│   ├── logs/               # Log lỗi PHP (php_error.log), log hệ thống
│   └── cache/              # File cache giao diện/query
│
├── docs/                   # 📝 Thư mục chứa tài liệu (Skill 5 của Agent)
│   └── modules/            # Chứa các file .md mô tả từng module
│
├── .env                    # Biến môi trường (DB_HOST, DB_USER... - KHÔNG push lên Git)
├── .env.example            # File mẫu chứa danh sách các biến môi trường
├── .gitignore              # Bỏ qua node_modules, vendor, .env, storage...
├── composer.json           # Quản lý thư viện PHP (nếu có)
└── README.md               # Thông tin tổng quan dự án
```

---

## 📋 QUY TẮC BỐ TRÍ FILE DÀNH CHO AGENT (Dành cho Hosting truy cập trực tiếp Document Root)

### 1. Giao diện & Tài nguyên tĩnh (Frontend)

- **CSS:** Mọi file `.css` phải đặt trong thư mục `css/` ở thư mục gốc. Ưu tiên chia nhỏ (VD: `header.css`, `auth.css`) và tuân thủ class quy định từ thư mục Stitch Skills.
- **JavaScript:** Mọi file `.js` đặt trong thư mục `js/` ở gốc. Tách biệt logic xử lý DOM và gọi AJAX.
- **Views:** Code HTML/Giao diện **bắt buộc** phải nằm trong `app/Views/` với phần mở rộng là `.php`. Giao diện này không được chứa truy vấn SQL. Các file tải tĩnh (CSS/JS/Ảnh) được nhúng qua đường dẫn tuyệt đối hoặc tương đối (VD: `/css/style.css`).

### 2. Logic xử lý (Backend)

- **Controllers:** Đặt trong `app/Controllers/`. Tên file dùng `PascalCase` (VD: `UserController.php`). Chỉ nhận Request, gọi Model và `require` file View.
- **Models:** Đặt trong `app/Models/`. Tên file dùng `PascalCase` (VD: `UserModel.php`). Chỉ chứa code SQL/PDO.

### 3. Nguyên tắc Bảo mật (Security)

- **Bảo vệ thư mục lõi:** Vì repo Git được deploy ngang hàng với Document Root (Ví dụ: host iNet trỏ thẳng `public_html/` = Repo Root), do đó **Bắt buộc** các thư mục `app/`, `config/`, `routes/`, `storage/` phải chứa file `.htaccess` với nội dung `Deny from all` để chặn người dùng từ Internet truy cập trực tiếp vào source code.
- **Một cổng duy nhất (Single Entry Point):** File `index.php` nằm ở gốc tiếp nhận toàn bộ request. File `.htaccess` ở thư mục gốc định tuyến mọi thứ (không chứa file vật lý) về `index.php`.