# Module: Hosting

## 1. Vị trí & Thành phần Hệ thống
- **View**: `app/Views/hostings.php` (Giao diện chính, HTML cấu trúc bảng và Modals).
- **CSS**: `css/style.css` (Định dạng styling cho toàn bộ module: Bảng, Modal Thêm/Sửa/Chi tiết, Action Dropdown, Custom Toast Loading).
- **JS**: Script xử lý Client-side nằm cuối file `hostings.php` (Xử lý DOM, logic đóng mở Modal, floating Action Menu, Toast notification, format giá tiền, lọc trạng thái).
- **Controller/Model**: *(Chưa triển khai - Đang dùng cấu trúc tĩnh frontend)*.
- **Database Table**: *(Dự kiến: bảng `hostings` trong MySQL)*.

## 2. Business Logic (Chức năng cốt lõi)
- Quản lý danh sách Hosting gồm các trường thông tin: Tên, Domain, Nhà cung cấp, Mức giá, Thời gian đăng ký và thời gian sử dụng.
- **Tự động tính trạng thái (Logic Client-side)**: Dựa trên "Ngày Hết Hạn" so với thời gian hiện tại của hệ thống:
  - <= 15 ngày: `Sắp hết hạn` (Cảnh báo màu Cam).
  - < 0 ngày: `Đã hết hạn` (Cảnh báo màu Đỏ).
  - Còn lại: `Đang hoạt động` (Màu Xanh lá).
- **Cấu trúc Form (Thêm/Sửa)**: Tái sử dụng một UI Modal chung cho cả thao tác "Thêm Hosting" và "Chỉnh Sửa Hosting". JS tự động điền sẵn dữ liệu và đổi UI button (từ màu Teal sang Xanh dương).
- **UX Giao tiếp**:
  - Modal "Xác nhận xóa" tùy chỉnh có icon cảnh báo (Red Warning) không sử dụng `confirm()` mặc định của trình duyệt.
  - Flow Action Toast: Mỗi hành động Thêm/Sửa/Xóa đều gọi UI Toast Loading (`1.2s`) -> Toast Success Anim (`2.0s`) trượt mượt mà từ góc phải dưới màn hình.

## 3. Lịch sử thay đổi (Changelog)
- **[Mới hoàn thành]** Xây dựng Layout giao diện danh sách xuất bản, search bar full-width và filter dropdown chuẩn thiết kế.
- **[Mới hoàn thành]** Tích hợp Floating Action Menu (`.row-action-menu`) (Xem chi tiết, Chỉnh sửa, Xóa) mở thông minh không bị tràn viền.
- **[Mới hoàn thành]** Hoàn thiện **Modal Chi Tiết Hosting** với giao diện header gradient, card thông tin dạng Grid, tích hợp nút "Chỉnh Sửa/Xóa" bám sát thao tác.
- **[Mới hoàn thành]** Nâng cấp UX bằng **Custom Delete Modal** (Giao diện "Xác nhận xóa hosting").
- **[Mới hoàn thành]** Xây dựng module **Toast Action**: Trượt từ Bottom-Right, hiển thị thông báo Loading Spinner và tự động chuyển sang trạng thái Success (Checkmark) áp dụng nhất quán cho Thêm, Cập nhật, và Xóa.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Chuyển hóa DB & API**: Xây dựng backend kiến trúc MVC (Controller & Model kết nối MySQL) và chuyển toàn bộ các hàm JS như `addHosting()`, `updateHosting()`, `deleteAction()` sang dạng FETCH API thay đổi số liệu thực.
2. **Server-side Pagination & Filter**: Chuyển logic search và phân trang dữ liệu về Backend để tối ưu hiệu năng browser khi danh sách hosting lớn dần lên hàng ngàn records.
3. **Bảo mật XSS**: Khi implement fetch API lấy dữ liệu từ Backend, cần tuân thủ rule mã hóa chuỗi khi in ra trong `generateRowHTML()` để chống XSS.
