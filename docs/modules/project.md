# Module: Project

## 1. Vị trí & Thành phần Hệ thống
- **View**: `app/Views/projects.php`
- **JS**: Logic xử lý AJAX, Modal, và Bulk selection nằm cuối file `projects.php`.
- **Controller**: `MainController.php` (Các phương thức `saveProject`, `deleteProject`, `deleteProjectsBulk`).
- **Model**: `ProjectModel.php` (Tương tác database `projects`).

## 2. Business Logic (Chức năng cốt lõi)
- Quản lý danh sách dự án: Tên, khách hàng, giá trị, ngày tạo, trạng thái.
- **Trạng thái (Status)**: 
    - `Lên Kế Hoạch`
    - `Đang Thực Hiện`
    - `Chờ Nghiệm Thu`
    - `Hoàn Thành`
    - `Tạm Dừng` (Xám)
- **Tính năng Xóa Hàng Loạt (Bulk Delete)**: 
    - Người dùng có thể chọn nhiều dự án qua checkbox.
    - Thanh tác vụ (Bulk Action Bar) hiển thị số lượng dự án đã chọn.
    - Một modal xác nhận sẽ xuất hiện trước khi thực hiện xóa qua API.
- **Phân Trang**: Tăng cường hiệu năng với hệ thống phân trang chuẩn hóa (Pagination).

## 3. Lịch sử thay đổi (Changelog)
- **[2026-03-31]** Triển khai hệ thống **Phân trang chuẩn (Standardized Pagination)** cho danh sách dự án.
- **[2026-03-30]** Bổ sung trạng thái `Tạm Dừng` (Paused) và đồng bộ logic lọc tại Dashboard.
- **[2026-03-30]** Sửa lỗi **Modal Scrolling**: Cho phép cuộn nội dung trong Modal "Thêm Dự Án Mới" khi vượt quá chiều cao màn hình.
- **[2026-03-29]** Đồng bộ hệ thống **Màu sắc trạng thái (Status Colors)**: Tương thích với Dashboard V2.
- **[2026-03-29]** Cập nhật **Badge Chờ Nghiệm Thu**: Chuyển từ màu Tím sang màu Cam (Orange) rực rỡ và chuyên nghiệp.
- **[2026-03-29]** Chuẩn hóa **Badge Hoàn Thành**: Thiết kế lại với màu Xanh lá (Green) nền nhạt chữ đậm.
- **[2026-03-29]** Đồng bộ bộ class `.st-` tiền tố dùng trong Modal Detail cho nhất quán với Table List.
- **[2026-03-29]** Xây dựng Layout giao diện quản lý dự án, search bar, và lọc trạng thái.
- **[2026-03-29]** Tích hợp Floating Action Menu (Xem chi tiết, Chỉnh sửa, Xóa đơn lẻ).
- **[2026-03-29]** Triển khai tính năng **Xóa Hàng Loạt (Bulk Delete)**.
- **[2026-03-29]** Chuẩn hóa giao diện **Modal Header**: Tích hợp Brand-boxed icon và đồng bộ hóa UI trên toàn module.
- **[2026-03-29]** Đồng bộ hệ thống **Dropdown Select**: Sử dụng `.pj-dropdown` cao cấp thay thế cho select mặc định.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Phân quyền**: Thêm kiểm tra quyền hạn trước khi thực hiện xóa (chỉ Admin mới được xóa).
2. **Server-side Sorting**: Thêm tính năng sắp xếp theo giá trị hoặc ngày tạo từ phía Backend.
3. **Chi tiết tiến độ**: Mở rộng module để quản lý các task con bên trong mỗi project.
