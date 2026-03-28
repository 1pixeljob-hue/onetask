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
- **Tính năng Xóa Hàng Loạt (Bulk Delete)**: 
    - Người dùng có thể chọn nhiều dự án qua checkbox.
    - Thanh tác vụ (Bulk Action Bar) hiển thị số lượng dự án đã chọn.
    - Một modal xác nhận sẽ xuất hiện trước khi thực hiện xóa qua API.

## 3. Lịch sử thay đổi (Changelog)
- **[Mới hoàn thành]** Xây dựng Layout giao diện quản lý dự án, search bar, và lọc trạng thái.
- **[Mới hoàn thành]** Tích hợp Floating Action Menu (Xem chi tiết, Chỉnh sửa, Xóa đơn lẻ).
- **[Mới hoàn thành]** Triển khai tính năng **Xóa Hàng Loạt (Bulk Delete)**:
    - Thêm UI Bulk Action Bar.
    - Thêm API deleteBulk trong Model và Controller.
    - Tích hợp logic selection và AJAX Fetch API.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Phân quyền**: Thêm kiểm tra quyền hạn trước khi thực hiện xóa (chỉ Admin mới được xóa).
2. **Server-side Sorting**: Thêm tính năng sắp xếp theo giá trị hoặc ngày tạo từ phía Backend.
3. **Chi tiết tiến độ**: Mở rộng module để quản lý các task con bên trong mỗi project.
