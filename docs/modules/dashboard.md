# Module: Dashboard

## 1. Vị trí & Thành phần Hệ thống
- **View**: `app/Views/index.php` (Giao diện tổng quan hệ thống).
- **Controller**: `MainController.php` (Phương thức `dashboard`).
- **Data Source**: Kết hợp dữ liệu từ `ProjectModel` và `HostingModel`.
- **JS**: Logic tính toán stats nằm trong IIFE cuối file `index.php`.

## 2. Business Logic (Chức năng cốt lõi)
- **Stats Grid**: Hiển thị 5 chỉ số quan trọng:
    - Tổng số Hosting và số lượng đang hoạt động.
    - Số lượng Hosting sắp hết hạn (cảnh báo).
    - Dự án đang thực hiện (`doing`).
    - Dự án chờ nghiệm thu (`testing`) kèm giá trị tài chính.
    - Tổng doanh thu từ các dự án đã hoàn thành.
- **Tiến độ Dự Án**: Liệt kê các project có trạng thái `doing` hoặc `testing` giúp admin theo dõi nhanh.
- **Hoạt Động Gần Đây**: Log các hành động mới nhất của hệ thống (hiện tại là mockup tĩnh).
- **Hành Động Nhanh (Quick Actions)**: Các phím tắt để mở Modal thêm mới ở bất kỳ đâu.

## 3. Lịch sử thay đổi (Changelog)
- **[Mới hoàn thành]** Tích hợp logic tính toán Stats từ `PHP_DATA` (dữ liệu thực từ MySQL).
- **[Mới hoàn thành]** Chuyển đổi Column 1 "Tình Trạng Hosting" từ mockup sang dữ liệu thực.
- **[Mới hoàn thành]** Tối ưu logic tính toán **Doanh Thu**: Chỉ tính từ dự án đã hoàn thành (`done`) và hosting chưa hết hạn.
- **[Mới hoàn thành]** Hiển thị danh sách dự án đang thực hiện động dựa trên trạng thái thực tế.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Dynamic Activity Feed**: Chuyển "Hoạt Động Gần Đây" từ mockup sang dữ liệu thực từ bảng `logs`.
2. **Chart Thống Kê Nhanh**: Thêm biểu đồ nhỏ (Sparkline) cho doanh thu ngay trên Dashboard.
3. **Báo Cáo Sơ Bộ**: Hiển thị bảng tổng hợp chi phí gia hạn hosting trong tháng hiện tại.
