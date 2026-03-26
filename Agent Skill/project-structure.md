# 📁 CẤU TRÚC THƯ MỤC DỰ ÁN ANTIGRAVITY (PHP MVC)

Cấu trúc này được thiết kế để đảm bảo tính bảo mật (tách biệt mã nguồn và thư mục `public`) và dễ bảo trì cho các dự án sử dụng PHP thuần hoặc Framework nhẹ, kết hợp HTML/CSS/JS.

---

## 🌳 SƠ ĐỒ THƯ MỤC (DIRECTORY TREE)

```text
antigravity-project/
│
├── app/                    # 🔒 Toàn bộ logic ứng dụng (Không thể truy cập trực tiếp từ Web)
│   ├── Controllers/        # Xử lý request, gọi Model, trả về View
│   ├── Models/             # Tương tác với Database (MySQL - PDO)
│   ├── Views/              # Các file giao diện (.php) chứa HTML, gọi CSS/JS từ public
│   ├── Core/               # Các class lõi (Database Connection, Router, BaseController)
│   └── Helpers/            # Các hàm dùng chung (format_date, sanitize_input...)
│
├── public/                 # 🌐 Thư mục gốc của Web Server (Document Root)
│   ├── index.php           # Entry point duy nhất (Tất cả request đều đi qua đây)
│   ├── .htaccess           # Cấu hình rewrite URL (Chuyển hướng request về index.php)
│   ├── css/                # Các file CSS (style.css, thư viện ngoài...)
│   ├── js/                 # Các file JavaScript (app.js, xử lý AJAX...)
│   ├── images/             # Hình ảnh tĩnh (logo, banner...)
│   └── uploads/            # File người dùng upload (cần phân quyền CHMOD cẩn thận)
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

## 📋 QUY TẮC BỐ TRÍ FILE DÀNH CHO AGENT

### 1. Giao diện & Tài nguyên tĩnh (Frontend)

- **CSS:** Mọi file `.css` phải đặt trong `public/css/`. Ưu tiên chia nhỏ (VD: `header.css`, `auth.css`) và tuân thủ class quy định từ thư mục Stitch Skills.
- **JavaScript:** Mọi file `.js` đặt trong `public/js/`. Tách biệt logic xử lý DOM và gọi AJAX.
- **Views:** Code HTML/Giao diện **bắt buộc** phải nằm trong `app/Views/` với phần mở rộng là `.php`. Giao diện này không được chứa truy vấn SQL. Các file tĩnh (CSS/JS) được nhúng qua đường dẫn tuyệt đối (VD: `/css/style.css`).

### 2. Logic xử lý (Backend)

- **Controllers:** Đặt trong `app/Controllers/`. Tên file dùng `PascalCase` (VD: `UserController.php`). Chỉ nhận Request, gọi Model và `require` file View.
- **Models:** Đặt trong `app/Models/`. Tên file dùng `PascalCase` (VD: `UserModel.php`). Chỉ chứa code SQL/PDO.

### 3. Nguyên tắc Bảo mật (Security)

- **Bảo vệ thư mục lõi:** Thư mục `app/`, `config/`, `storage/` phải nằm ngoài tầm với của HTTP Request (Thường cấu hình Nginx/Apache trỏ Document Root thẳng vào thư mục `public/`).
- **Một cổng duy nhất (Single Entry Point):** Mọi URL (như `domain.com/login`, `domain.com/user/profile`) đều được `.htaccess` định tuyến về `public/index.php`. Tại đây, Router sẽ quyết định load Controller nào.