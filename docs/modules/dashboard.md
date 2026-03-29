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
- **[2026-03-29]** Đồng bộ toàn diện biểu đồ với module Báo cáo: Hiển thị 3 đường (**Projects, Hosting, Tổng**) thay vì chỉ Projects.
- **[2026-03-29]** Tích hợp logic xử lý `getMonthlyBreakdown` từ `shared-data.js` để đảm bảo số liệu nhất quán 100% giữa Dashboard và Reports.
- **[2026-03-29]** Chuyển đổi biểu đồ doanh thu sang dạng **Đường (Line Chart)** với hiệu ứng Gradient Teal cao cấp.
- **[2026-03-29]** Tích hợp đường cong mềm mại (`tension: 0.4`) và nhãn trục Y rút gọn (5M, 10M...) như bản thiết kế.
- **[2026-03-29]** Tích hợp dữ liệu thật 12 tháng từ Database vào biểu đồ mới.
- **[2026-03-29]** Nâng cấp phần **Tiến độ Dự án** với giao diện sạch và Badge trạng thái mới (Refined Badges).
- **[2026-03-29]** Thêm thẻ **Milestone Footer cao cấp** trang trí góc dưới màn hình tiến độ.
- **[2026-03-29]** Tối ưu hóa layout Dashboard với tỉ lệ vàng (biểu đồ doanh thu rộng nhỉnh hơn 2 lần danh sách tiến độ).
- **[2026-03-29]** Chuẩn hóa định dạng giá trị tài chính sang định dạng rút gọn ($1.2M, $0.5B) trong biểu đồ.
- **[Mới hoàn thành]** Tích hợp lời chào động (Chào buổi sáng/trưa/chiều/tối) dựa trên giờ hệ thống.
- **[Mới hoàn thành]** Sửa lỗi tràn giao diện (horizontal overflow) bằng cách áp dụng Responsive Grid (`auto-fit`) và khóa tràn toàn hệ thống.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Dynamic Activity Feed**: Chuyển "Hoạt Động Gần Đây" từ mockup sang dữ liệu thực từ bảng `logs`.
2. **Chart Thống Kê Nhanh**: Thêm biểu đồ nhỏ (Sparkline) cho doanh thu ngay trên Dashboard.
3. **Báo Cáo Sơ Bộ**: Hiển thị bảng tổng hợp chi phí gia hạn hosting trong tháng hiện tại.
