---
name: "CodeGeneration"
description: "Kỹ năng viết code mới hoặc thêm tính năng, đảm bảo chuẩn architecture của Antigravity (PHP thuần/Framework, thao tác DB an toàn, giao diện mượt màng)."
---

# 🛠️ SKILL: CODE_GENERATION

Kỹ năng này chịu trách nhiệm tạo cấu trúc code mới, module mới hoặc thêm tính năng vào các thành phần hiện có.

## Triggers
- "Tạo trang/tính năng mới..."
- "Viết cho tôi hàm xử lý/giao diện..."
- "Tạo bảng MySQL cho module..."
- "Thêm chức năng..."
- "Thêm/tạo module..."

## Workflow
1. **Analyze (Phân tích):** Đọc yêu cầu và tham chiếu chuẩn dự án để biết vị trí đặt file (VD: thư mục `app/Views` cho HTML/CSS, `app/Models` hoặc `app/Controllers` cho PHP xử lý logic).
2. **Plan (Lập kế hoạch):** Đề xuất cấu trúc thư mục, tên file (`.php`, `.css`, `.js`, `.sql`). In ra một tóm tắt ngắn (Bullet points) trước khi code.
3. **Execute (Thực thi):**
   - **PHP:** Tuân thủ chuẩn PSR-12. Tách biệt logic xử lý và HTML (không `echo` HTML bừa bãi trong controller).
   - **MySQL:** LUÔN SỬ DỤNG PDO với Prepared Statements để tương tác DB. Tuyệt đối KHÔNG nối chuỗi SQL trực tiếp để phòng chống SQL Injection.
   - **CSS:** Viết CSS ra file riêng, ưu tiên sử dụng class có hệ thống (VD: BEM hoặc theo chuẩn dự án), hạn chế tối đa inline-style.
   - **JavaScript:** Ưu tiên Vanilla JS (ES6+). Sử dụng `fetch` hoặc `axios` cho AJAX.
4. **Self-Review (Tự đánh giá):** Kiểm tra xem code có vi phạm quy tắc bảo mật hay không (đặc biệt là XSS qua việc `htmlspecialchars` khi in dữ liệu ra view).
