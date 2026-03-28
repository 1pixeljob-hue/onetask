# Module: Báo Cáo (Reports)

## 1. Vị trí & Thành phần Hệ thống
- **View**: `app/Views/reports.php` (Giao diện báo cáo và thống kê).
- **Controller**: `MainController.php` (Phương thức `reports`).
- **Data Source**: Kết hợp dữ liệu từ `ProjectModel` và `HostingModel`.
- **Thư viện ngoài**: `Chart.js` (Hiển thị biểu đồ).
- **JS**: Logic lọc theo năm và render biểu đồ nằm trong `reports.php`.

## 2. Business Logic (Chức năng cốt lõi)
- **Report Cards**: 4 thẻ tóm tắt doanh thu năm hiện tại:
    - Tổng Doanh Thu (So sánh tăng trưởng với năm trước).
    - Doanh Thu Projects (Tăng trưởng và tỷ trọng).
    - Doanh Thu Hosting (Tăng trưởng và tỷ trọng).
    - Trung bình Doanh Thu/Đơn hàng.
- **Biểu đồ (Charts)**:
    - Cơ cấu doanh thu (Donut Chart).
    - So sánh doanh thu theo năm (Bar Chart).
    - Xu hướng doanh thu từng tháng (Line Chart).
- **Bảng Chi Tiết**:
    - Thống kê chi tiết theo từng tháng của năm được chọn.
    - Bảng tổng hợp các chỉ số theo từng năm hoạt động.

## 3. Lịch sử thay đổi (Changelog)
- **[Mới hoàn thành]** Xây dựng Layout Báo cáo với các thẻ chỉ số (KPI Cards).
- **[Mới hoàn thành]** Tích hợp `Chart.js` và render 3 biểu đồ dữ liệu thực từ backend.
- **[Mới hoàn thành]** Triển khai tính năng **Lọc theo Năm** (Year Filter) đồng bộ toàn bộ báo cáo.
- **[Mới hoàn thành]** Hoàn thiện các bảng thống kê chi tiết tháng/năm.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Xuất Báo Cáo**: Triển khai tính năng xuất dữ liệu ra file Excel hoặc PDF.
2. **So Sánh Tùy Biến**: Cho phép chọn 2 năm bất kỳ để so sánh trực tiếp trên biểu đồ.
3. **Phân Tích Chi Phí**: Thêm phần báo cáo lợi nhuận bằng cách trừ đi chi phí vận hành (hosting gốc, nhân sự).
