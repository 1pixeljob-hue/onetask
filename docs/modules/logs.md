# Module: Logs

## 1. Vị trí & Thành phần Hệ thống
- **View**: `app/Views/logs.php` (Giao diện theo dõi hành động).
- **Controller**: `MainController.php` (Phương thức `logs`, `addLog`).
- **Data Source**: Bảng `activity_logs` trong MySQL.
- **Model**: `LogModel.php` (Xử lý truy vấn, tự động tạo bảng nếu thiếu).

## 2. Business Logic (Chức năng cốt lõi)
- **Theo dõi Thời gian thực**: Tự động ghi lại các hành động Thêm/Sửa/Xóa từ tất cả các module khác.
- **Ghi Log Hàng loạt**: Hỗ trợ ghi log tóm tắt cho các thao tác chọn nhiều items (Bulk delete).
- **Lọc & Tìm kiếm**: Thanh công cụ cho phép lọc theo Module, Loại hành động và từ khóa.
- **Phân trang Backend**: Xử lý dữ liệu lớn bằng phân trang phía máy chủ (15 bản ghi/trang).

## 3. Lịch sử thay đổi (Changelog)
- **[Mới hoàn thành]** Chuyển đổi từ dữ liệu mockup sang Database thực tích hợp toàn hệ thống.
- **[Mới hoàn thành]** Triển khai cơ chế bọc `try...catch` và chỉ ghi log khi thao tác dữ liệu thành công.
- **[Mới hoàn thành]** Tích hợp ghi log cho thao tác "Xóa hàng loạt" trong Project và Hosting.
- **[Mới hoàn thành]** Xây dựng Layout bảng Logs với thiết kế sạch sẽ, responsive và Badge màu sắc trực quan.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Khôi phục (Restore)**: Lưu trữ trạng thái dữ liệu cũ vào cột `details` (JSON) để hỗ trợ tính năng Hoàn tác/Khôi phục.
2. **Dọn dẹp định kỳ**: Tự động xóa log cũ (ví dụ: sau 30 ngày) để tối ưu dung lượng Database.
3. **Export CSV**: Cho phép xuất báo cáo lịch sử hoạt động ra file Excel/CSV.
