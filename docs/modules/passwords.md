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
- **Phân loại (Category)**: Hỗ trợ 3 nhóm chính: `Email` (Blue), `Tài khoản` (Red), `Khác` (Purple) để dễ nhận diện trực quan.
- **Tạo Mật Khẩu Mạnh (Secure Generator)**: Tích hợp trình tạo chuỗi ngẫu nhiên 16 ký tự (bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt) ngay trong Modal thêm mới.
- **Bảo mật Hiển thị**: Mặc định mật khẩu trong Grid được ẩn (`type="password"`). Người dùng có thể nhấn icon Eye để xem nhanh hoặc Copy vào Clipboard.
- **Tìm kiếm & Phân trang**:
  - Tìm kiếm theo thời gian thực (Real-time Search) qua Tiêu đề, Username hoặc Website.
  - Phân trang tự động (8 items/page) để đảm bảo hiệu suất khi danh sách mật khẩu lớn.

## 3. Lịch sử thay đổi (Changelog)
- **[Mới hoàn thành]** Xây dựng **PasswordModel** chuẩn MVC, thực hiện các thao tác CRUD an toàn bằng PDO Prepared Statements.
- **[Mới hoàn thành]** Triển khai API Endpoints đồng bộ dữ liệu giữa Frontend và Backend qua Fetch API.
- **[Mới hoàn thành]** Hoàn thiện **Modal Thêm Mật Khẩu Mới** theo mockup, tích hợp logic "Tạo mật khẩu mạnh" và Validate dữ liệu Client-side.
- **[Mới hoàn thành]** Chuyển đổi Grid hiển thị từ HTML tĩnh sang **Dynamic Rendering** bằng JavaScript, giúp việc cập nhật/xóa dữ liệu diễn ra mượt mà không cần tải lại trang.
- **[Mới hoàn thành]** Tích hợp hệ thống **Toast Notifications** (Loading/Success/Error) bám sát các hành động Lưu và Xóa.
- **[Mới hoàn thành]** Nâng cấp UX với tính năng **Copy to Clipboard** có hiệu ứng Checkmark xác nhận tại từng trường thông tin.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Mã hóa Dữ liệu (Encryption)**: Hiện tại mật khẩu đang được lưu ở dạng bản rõ (plaintext) trong DB. Cần triển khai mã hóa đối xứng (Symmetric Encryption - ví dụ: AES-256) trước khi lưu để đảm bảo an toàn tuyệt đối nếu DB bị rò rỉ.
2. **Module Category Manager**: Cho phép người dùng tự định nghĩa và quản lý các danh mục mật khẩu thay vì fix cứng 3 loại như hiện tại.
3. **Password Health Check**: Cảnh báo người dùng khi mật khẩu quá yếu hoặc đã lâu không thay đổi (Dựa trên `created_at`).
4. **Master Password**: Yêu cầu nhập mật khẩu Master hoặc mã PIN trước khi cho phép "Xem" hoặc "Copy" mật khẩu nhạy cảm.
