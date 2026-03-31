# Module: Passwords

## 1. Vị trí & Thành phần Hệ thống
- **Model**: `app/Models/PasswordModel.php` (Xử lý truy vấn MySQL qua PDO: CRUD mật khẩu).
- **Controller**: `app/Controllers/MainController.php` (Endpoint tiếp nhận AJAX request từ frontend để lưu/xóa dữ liệu).
- **View**: `app/Views/passwords.php` (Giao diện chính, chứa HTML Modal và Grid hiển thị).
- **Routes**: `routes/web.php` (Định tuyến API `/passwords/save` và `/passwords/delete`).
- **CSS**: Inline `<style>` trong `passwords.php` (Custom styling cho Modal, Toast, Card Layout bám sát mockup).
- **JS**: Script xử lý Client-side nằm cuối file `passwords.php` (Xử lý render Grid động, Search, Pagination, Password Generator, và AJAX Fetch).
- **Database Table**: Bảng `passwords` (Cấu trúc: `id`, `title`, `url`, `category`, `username`, `password`, `notes`, `created_at`).

## 2. Business Logic (Chức năng cốt lõi)
- **Quản lý CREDENTIALS**: Lưu trữ thông tin đăng nhập bao gồm Tiêu đề, Website, Username, Password và Ghi chú.
- **Quản lý Danh Mục (Category Manager)**: Hệ thống quản lý danh mục động (CRUD) với giao diện chuyên nghiệp (List & Form layout), cho phép người dùng tự định nghĩa tên và màu sắc (Hex) cho từng loại mật khẩu. Tích hợp bộ chọn màu (Color Picker) và xem trước (Preview) trực quan.
- **Tạo Mật Khẩu Mạnh (Secure Generator)**: Tích hợp trình tạo chuỗi ngẫu nhiên 16 ký tự (bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt) ngay trong Modal thêm mới.
- **Bảo mật Hiển thị**: Mặc định mật khẩu được ẩn, hỗ trợ xem nhanh hoặc Copy vào Clipboard với hiệu ứng xác nhận.
- **Tìm kiếm & Phân trang**: Hỗ trợ tìm kiếm real-time và hệ thống phân trang chuẩn hóa (Pagination).

## 3. Lịch sử thay đổi (Changelog)
- **[2026-03-31]** Triển khai hệ thống **Phân trang chuẩn (Standardized Pagination)** cho danh sách mật khẩu.
- **[2026-03-30]** Nâng cấp **Quản lý Danh Mục**: Chuyển sang giao diện 2 bước (Danh sách & Biểu mẫu) với tính năng Color Preview cao cấp.
- **[2026-03-29]** Xây dựng **PasswordModel** và **CategoryModel** chuẩn MVC, thực hiện CRUD an toàn bằng PDO.
- **[2026-03-29]** Triển khai API Endpoints đồng bộ dữ liệu qua Fetch API cho cả Mật khẩu và Danh mục.
- **[2026-03-29]** Hoàn thiện **Modal Quản Lý Danh Mục** với tính năng chọn màu sắc tùy biến và xem trước Tag trực quan bám sát thiết kế.
- **[2026-03-29]** Chuyển đổi Grid hiển thị sang cơ chế **State-based Rendering**: Tự động cập nhật giao diện ngay khi lưu/xóa dữ liệu mà không cần tải lại trang (Zero-reload UX).
- **[2026-03-29]** Tích hợp hệ thống **Toast Notifications** đồng bộ.
- **[2026-03-29]** Chuẩn hóa giao diện **Modal Header**: Chuyển style cục bộ sang global cho toàn hệ thống.
- **[2026-03-29]** Đồng bộ hệ thống **Dropdown Select**: Cập nhật Danh mục Password bằng UI cao cấp mới.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Mã hóa Dữ liệu (Encryption)**: Hiện tại mật khẩu đang được lưu ở dạng bản rõ (plaintext) trong DB. Cần triển khai mã hóa đối xứng (Symmetric Encryption - ví dụ: AES-256) trước khi lưu để đảm bảo an toàn tuyệt đối nếu DB bị rò rỉ.
2. **Module Category Manager**: Cho phép người dùng tự định nghĩa và quản lý các danh mục mật khẩu thay vì fix cứng 3 loại như hiện tại.
3. **Password Health Check**: Cảnh báo người dùng khi mật khẩu quá yếu hoặc đã lâu không thay đổi (Dựa trên `created_at`).
4. **Master Password**: Yêu cầu nhập mật khẩu Master hoặc mã PIN trước khi cho phép "Xem" hoặc "Copy" mật khẩu nhạy cảm.
