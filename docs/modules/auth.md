# Module: Đăng nhập (Authentication)

## 1. Vị trí & Thành phần Hệ thống
- **View**: `app/Views/login.php` (Giao diện đăng nhập).
- **Controller**: `AuthController.php` (Xử lý logic xác thực, session, cookie).
- **Model**: `UserModel.php` (Tương tác với bảng `users`).
- **CSS**: `css/auth.css` (Style riêng cho trang Login).
- **Database**: Bảng `users` (Chứa thông tin tài khoản admin).

## 2. Chức năng chính
- **Đăng nhập đa năng**: Hỗ trợ đăng nhập bằng cả **Tên tài khoản** hoặc **Email**.
- **Remember Me (Ghi nhớ đăng nhập)**: Duy trì trạng thái đăng nhập trong **24 giờ** thông qua Secure Cookie và Token.
- **Bảo mật**:
    - Mật khẩu được mã hóa bằng thuật toán `Bcrypt` (chuẩn `password_hash`).
    - Chống SQL Injection bằng PDO Prepared Statements.
    - Bảo vệ toàn bộ Dashboard bằng phương thức `checkAuth`.
    - Hiện/ẩn mật khẩu tích hợp ngay tại form.

## 3. Thông tin tài khoản mặc định
- **Tên tài khoản**: `quydev`
- **Email**: `1pixel.job@gmail.vn`
- **Mật khẩu**: `Spencil@123`

## 4. Lịch sử thay đổi (Changelog)
- **[2026-03-31]** Khởi tạo hệ thống đăng nhập bảo mật cho Onetask.
- **[2026-03-31]** Thiết kế giao diện Login dựa trên Project Hub.
- **[2026-03-31]** Triển khai cơ chế Remember Me 24h.

## 5. Hướng dẫn sử dụng cho Developer
Để bảo vệ một Controller mới, hãy thêm đoạn mã sau vào hàm `__construct()`:
```php
use App\Controllers\AuthController;

public function __construct() {
    AuthController::checkAuth();
    // ... code khác
}
```
