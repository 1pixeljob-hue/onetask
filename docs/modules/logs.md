# Module: Logs

## 1. Vị trí & Thành phần Hệ thống
- **View**: `app/Views/logs.php` (Giao diện theo dõi hành động).
- **Controller**: `MainController.php` (Phương thức `logs`).
- **Data Source**: *(Dự kiến: bảng `logs` trong MySQL)*.
- **JS**: Logic lọc theo Module, Action và Search nằm trong `logs.php`.

## 2. Business Logic (Chức năng cốt lõi)
- **Lọc Dữ Liệu**: Thanh công cụ cho phép lọc theo Module (Project, Hosting, Passwords...) và loại hành động (Cập nhật, Tạo mới, Xóa).
- **Danh Sách Log**: Bảng chi tiết bao gồm:
    - Loại Module (Phân loại theo icon).
    - Hành động (Badge màu xanh cho Cập nhật, xanh lá cho Tạo mới, đỏ cho Xóa).
    - Tên Item bị tác động.
    - Người thực hiện và Thời gian.
- **Thao Tác**: Cho phép xem chi tiết thay đổi (View) hoặc xóa log (Delete).

## 3. Lịch sử thay đổi (Changelog)
- **[Mới hoàn thành]** Xây dựng Layout bảng Logs với thiết kế sạch sẽ và dễ nhìn.
- **[Mới hoàn thành]** Triển khai logic lọc Client-side cho tất cả các cột.
- **[Mới hoàn thành]** Phân chia trạng thái hành động bằng các Badge màu sắc trực quan.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Dữ liệu Thực**: Chuyển đổi từ dữ liệu mockup sang Database thực tích hợp với mọi hành động lưu/xóa trong hệ thống.
2. **Khôi phục (Restore)**: Tính năng tự động khôi phục dữ liệu đã xóa khi click vào log hành động "Xoá".
3. **Phân trang Backend**: Xử lý phân trang phía máy chủ khi số lượng bản ghi log tăng cao.
