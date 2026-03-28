# Module: CodeX

## 1. Vị trí & Thành phần Hệ thống
- **View**: `app/Views/codex.php` (Giao diện quản lý snippet code).
- **Controller**: `MainController.php` (Phương thức `codex`).
- **Data Source**: *(Dự kiến: bảng `codex` trong MySQL)*.
- **JS**: Logic lọc theo ngôn ngữ và search snippet nằm trong `codex.php`.

## 2. Business Logic (Chức năng cốt lõi)
- **Bộ Lọc Ngôn Ngữ**: Sidebar trái cho phép lọc snippet theo JavaScript, PHP, CSS, HTML, v.v.
- **Danh Sách Snippet**: Cột giữa hiển thị tiêu đề, mô tả ngắn và metadata (số dòng, số ký tự).
- **Xem Trước Code (Preview)**: Bảng bên phải hiển thị nội dung code đầy đủ với:
    - Nút Sao chép (Copy to clipboard).
    - Thao tác Sửa (Edit) và Xóa (Delete).

## 3. Lịch sử thay đổi (Changelog)
- **[Mới hoàn thành]** Thiết kế giao diện 3 cột chuyên nghiệp dành cho quản lý code (Sidebar/List/Preview).
- **[Mới hoàn thành]** Tích hợp font `Fira Code` cho khối mã nguồn.
- **[Mới hoàn thành]** Triển khai giao diện các bộ lọc ngôn ngữ và bộ đếm số lượng snippet.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Highlight Syntax**: Tích hợp thư viện `Prism.js` hoặc `Highlight.js` để tô màu code.
2. **Backend Integration**: Chuyển đổi từ dữ liệu mockup sang Database thực tế qua Fetch API.
3. **Download Snippet**: Cho phép tải snippet trực tiếp dưới dạng file tương ứng với ngôn ngữ.
