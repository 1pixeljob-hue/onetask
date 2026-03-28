# Module: Cài Đặt (Settings)

## 1. Vị trí & Thành phần Hệ thống
- **View**: `app/Views/settings.php` (Giao diện cấu hình hệ thống).
- **Controller**: `MainController.php` (Phương thức `settings`).
- **Tích hợp**: `Supabase`, `Google Calendar API`.

## 2. Business Logic (Chức năng cốt lõi)
- **Database Status**: Theo dõi trạng thái kết nối và thống kê nhanh dữ liệu từ database chính (Hostings, Projects...).
- **Cấu Hình Thông Báo**: Cho phép tùy chỉnh khoảng thời gian cảnh báo trước khi hết hạn hosting (mặc định 30 ngày).
- **Đồng Bộ Lịch (Google Calendar)**: Tích hợp nút kết nối tài khoản Google để tự động tạo sự kiện nhắc nhở (7 ngày, 1 ngày, và ngày hết hạn) cho mỗi hosting.

## 3. Lịch sử thay đổi (Changelog)
- **[Mới hoàn thành]** Xây dựng Layout Cài đặt trực quan với các Card quản lý riêng biệt.
- **[Mới hoàn thành]** Triển khai giao diện kết nối database (Supabase) và hiển thị chỉ số Real-time.
- **[Mới hoàn thành]** Tích hợp khu vực cấu hình Google Calendar với hướng dẫn chi tiết các loại nhắc nhở.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Quản Lý User**: Thêm phần danh sách người dùng và phân quyền (Admin, Editor, Viewer).
2. **Backup dữ liệu**: Nút sao lưu nhanh toàn bộ database ra file SQL trực tiếp từ Settings.
3. **Webhook Notifications**: Tích hợp gửi thông báo hết hạn qua Telegram hoặc Slack thay vì chỉ qua Calendar.
