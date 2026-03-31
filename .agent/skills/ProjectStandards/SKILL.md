---
name: "ProjectStandards"
description: "Định nghĩa các tiêu chuẩn kiến trúc, quy tắc đặt tên và bảo mật nghiêm ngặt cho dự án Antigravity."
---

# ⚡ PROJECT_STANDARDS

Các quy tắc này là bắt buộc cho mọi hành động của Agent.

## 🏛️ Cấu trúc hệ thống (Architecture)
- **MVC:** Tuân thủ Model-View-Controller.
- **Controller:** Không chứa SQL trực tiếp.
- **Model:** Không trả về HTML.
- **BaseController:** Toàn bộ Controller kế thừa để thực thi Auth.

## 🔤 Quy chuẩn đặt tên (Naming)
- **PHP:** Class `PascalCase`, Biến/Hàm `camelCase`, Hằng số `UPPER_SNAKE_CASE`.
- **MySQL:** Bảng/Cột `snake_case`, Khóa ngoại `_id`.
- **CSS:** BEM hoặc `kebab-case`.
- **JS:** Biến/Hàm `camelCase`.

## 🛡️ Bảo mật (Security)
- **SQLi:** LUÔN dùng Prepared Statements (PDO).
- **XSS:** Dùng `htmlspecialchars()` khi in dữ liệu ra View.
- **Credentials:** Không hardcode mật khẩu hay API Keys.
- **Validation:** Mọi input đều phải được sanitize.

## 🧪 Xử lý lỗi (Error Handling)
- **Try...Catch:** Bọc các thao tác DB và External API.
- **Logging:** Ghi log lỗi thay vì in lỗi trực tiếp ra người dùng.
- **API:** Trả về JSON chuẩn: `{"status": "...", "data": {}, "message": "..."}`.
