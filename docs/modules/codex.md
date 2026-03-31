# Module: CodeX

## 1. Vị trí & Thành phần Hệ thống
- **View**: `app/Views/codex.php` (Giao diện quản lý snippet code).
- **Controller**: `MainController.php` (Phương thức `codex`).
- **Data Source**: *(Dự kiến: bảng `codex` trong MySQL)*.
- **JS**: Logic lọc theo ngôn ngữ và search snippet nằm trong `codex.php`.

## 2. Business Logic (Chức năng cốt lõi)
- **Bộ Lọc Ngôn Ngữ**: Sidebar trái cho phép lọc snippet theo JavaScript, PHP, CSS, HTML, v.v.
- **Danh Sách Snippet**: Cột giữa hiển thị tiêu đề, mô tả ngắn và metadata (số dòng, số ký tự). Tích hợp hệ thống phân trang chuẩn hóa (Pagination).
- **Xem Trước Code (Preview)**: Bảng bên phải hiển thị nội dung code đầy đủ với:
    - Nút Sao chép (Copy to clipboard).
    - Thao tác Sửa (Edit) và Xóa (Delete).
- **Quản lý Danh Mục (Category Manager)**: Hệ thống quản lý ngôn ngữ/loại code với giao diện 2 bước (Danh sách & Biểu mẫu) và tính năng Color Preview.
- **Code Editor Wrapper**: Giao diện nhập liệu code chuyên nghiệp với thanh trạng thái hiển thị số dòng và ký tự theo thời gian thực.

## 3. Lịch sử thay đổi (Changelog)
- **[2026-03-31]** Triển khai hệ thống **Phân trang chuẩn (Standardized Pagination)** cho danh sách Snippet.
- **[2026-03-30]** Nâng cấp **Quản lý Danh Mục Snippet**: Đồng bộ giao diện 2 bước và tính năng xem trước màu sắc Tag.
- **[2026-03-29]** Thiết kế giao diện 3 cột chuyên nghiệp dành cho quản lý code (Sidebar/List/Preview).
- **[2026-03-29]** Tích hợp font `Fira Code` cho khối mã nguồn.
- **[2026-03-29]** Triển khai giao diện các bộ lọc ngôn ngữ và bộ đếm số lượng snippet.
- **[2026-03-29]** Đồng bộ hóa class `.open` cho dropdown "Loại Code" đảm bảo hoạt động chính xác tương thích với global JS.

## 4. Gợi ý Tối ưu (Future Optimizations)
1. **Highlight Syntax**: Tích hợp thư viện `Prism.js` hoặc `Highlight.js` để tô màu code.
2. **Backend Integration**: Chuyển đổi từ dữ liệu mockup sang Database thực tế qua Fetch API.
3. **Download Snippet**: Cho phép tải snippet trực tiếp dưới dạng file tương ứng với ngôn ngữ.
