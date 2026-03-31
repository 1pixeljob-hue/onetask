---
description: "Định nghĩa các bộ kỹ năng (Skills) và luồng công việc (Workflows) tự động cho AI Agent trong dự án Antigravity."
version: "1.5.0"
project: "Antigravity"
author: "1Pixel Team"
tech_stack: "PHP, MySQL, CSS, JavaScript"
---

# 🚀 BỘ KỸ NĂNG AGENT: DỰ ÁN ANTIGRAVITY (Full-stack Edition)

File này định nghĩa các Skills (Kỹ năng) mà Agent tự động kích hoạt dựa trên ngữ cảnh (Context) hoặc Lệnh (Trigger) từ người dùng. Mọi hành động phải tuân thủ nghiêm ngặt các quy tắc trong cấu trúc dự án và đặc thù của Tech Stack: PHP, MySQL, CSS, JavaScript.

---

## 🛠️ SKILL 1: CODE_GENERATION 

**Mô tả:** Kỹ năng viết code mới hoặc thêm tính năng, đảm bảo chuẩn architecture của Antigravity (PHP thuần/Framework, thao tác DB an toàn, giao diện mượt mà).

**Triggers (Tự động kích hoạt khi nghe):**
- "Tạo trang/tính năng mới..."
- "Viết cho tôi hàm xử lý/giao diện..."
- "Tạo bảng MySQL cho module..."

**Workflow (Quy trình thực thi):**
1. **Analyze (Phân tích):** Đọc yêu cầu và tham chiếu chuẩn dự án để biết vị trí đặt file (VD: thư mục `app/Views` cho HTML/CSS, `app/Models` hoặc `app/Controllers` cho PHP xử lý logic).
2. **Plan (Lập kế hoạch):** Đề xuất cấu trúc thư mục, tên file (`.php`, `.css`, `.js`, `.sql`). In ra một tóm tắt ngắn (Bullet points) trước khi code.
3. **Execute (Thực thi):**
   - **PHP:** Tuân thủ chuẩn PSR-12. Tách biệt logic xử lý và HTML (không `echo` HTML bừa bãi trong controller).
   - **MySQL:** LUÔN SỬ DỤNG PDO với Prepared Statements để tương tác DB. Tuyệt đối KHÔNG nối chuỗi SQL trực tiếp để phòng chống SQL Injection.
   - **CSS:** Viết CSS ra file riêng, ưu tiên sử dụng class có hệ thống (VD: BEM hoặc theo chuẩn dự án), hạn chế tối đa inline-style.
   - **JavaScript:** Ưu tiên Vanilla JS (ES6+). Sử dụng `fetch` hoặc `axios` cho AJAX.
4. **Self-Review (Tự đánh giá):** Kiểm tra xem code có vi phạm quy tắc bảo mật hay không (đặc biệt là XSS qua việc `htmlspecialchars` khi in dữ liệu ra view).

---

## 🐛 SKILL 2: ZERO_GRAVITY_DEBUG

**Mô tả:** Kỹ năng phân tích log PHP, gỡ lỗi truy vấn MySQL, hoặc fix JS/CSS, tìm nguyên nhân gốc rễ và đưa ra giải pháp triệt để.

**Triggers:**
- "Sửa lỗi này..."
- "Trắng trang / Lỗi 500..."
- "Lỗi SQL syntax..." / "Vỡ layout CSS..." / "Lỗi Console JS..."
- Lệnh `/fix-issue`

**Workflow (Quy trình thực thi):**
1. **Trace (Truy vết):** Dựa vào log lỗi PHP (`php_error.log`), Console Browser, thông báo lỗi PDO Exception để khoanh vùng file.
2. **Diagnose (Chẩn đoán):** Không đoán mò. Phân tích nguyên nhân logic (Lỗi do sai kiểu dữ liệu PHP, query MySQL chậm/sai logic, hoặc biến JS undefined).
3. **Propose (Đề xuất):** Đưa ra phương án sửa chữa tối ưu nhất.
4. **Resolve (Giải quyết):** Xuất ra đoạn code hoàn chỉnh đã được fix, kèm giải thích ngắn gọn tại sao lỗi xảy ra.

---

## 🚢 SKILL 3: ORBIT_DEPLOYMENT

**Mô tả:** Hỗ trợ quy trình chuẩn bị release web PHP, migrate database MySQL lên server.

**Triggers:**
- "Chuẩn bị deploy..."
- "Export database..."
- Lệnh `/deploy`

**Workflow (Quy trình thực thi):**
1. **Pre-flight Check:** Tự động kiểm tra file `composer.json` / `package.json` xem có thư viện nào cần update không.
2. **Database Migration:** Tạo file `.sql` chứa các câu lệnh `CREATE TABLE` hoặc `ALTER TABLE` cần thiết cho bản cập nhật lần này. Nhắc người dùng backup DB cũ trước khi chạy.
3. **Env Check:** Liệt kê các biến cấu hình cần cập nhật trên Server (dựa vào file `config.example.php` hoặc `.env`), đặc biệt là thông tin kết nối Database.
4. **Build Script:** Cung cấp hướng dẫn upload file (FTP/Git pull), chạy lệnh cần thiết, và phân quyền thư mục (CHMOD) đúng chuẩn.

---

## 🛡️ SKILL 4: ARCHITECTURE_REVIEW

**Mô tả:** Review code PHP/JS, tối ưu hóa Database MySQL, dọn dẹp CSS.

**Triggers:**
- "Review đoạn code PHP/JS này..."
- "Tối ưu hóa câu query này..."
- Lệnh `/review`

**Workflow (Quy trình thực thi):**
1. **Quét tổng thể:** 
   - Đánh giá câu query MySQL (có bị N+1 query không, có cần đánh Index không).
   - Đánh giá logic PHP/JS (có rò rỉ bộ nhớ, vòng lặp vô ích không).
   - Đánh giá CSS (có lặp lại code, dư thừa selector không).
2. **Feedback Format:** Luôn trả về kết quả theo cấu trúc:
   - 🔴 **Critical:** Lỗi bảo mật (SQL Injection, XSS) hoặc sập hệ thống.
   - 🟡 **Warning:** Code smell, thiếu tối ưu DB (Missing Index, `SELECT *`), code lộn xộn.
   - 🟢 **Praise:** Khen ngợi đoạn code/query viết thông minh.
   - 🛠️ **Refactored Code:** Cung cấp phiên bản file đã tối ưu hóa nhất.

---

## 📝 SKILL 5: MODULE_DOCUMENTATION

**Mô tả:** Kỹ năng tự động tạo, cập nhật và quản lý file `.md` cho từng module riêng biệt để Agent hiểu rõ chức năng, lịch sử thay đổi và có cơ sở đề xuất tối ưu.

**Triggers:**
- "Tạo module mới [Tên Module]..."
- "Cập nhật tài liệu cho module..."
- "Skill 5 [Tên Module]"
- Lệnh `/doc` hoặc `/update-doc`

**Workflow (Quy trình thực thi):**
1. **Khởi tạo/Đọc hiểu (Initialize/Read):** Agent tự động tìm hoặc tạo file `[ten_module].md` nằm trong thư mục `docs/modules/` (hoặc thư mục tài liệu tương ứng).
2. **Ghi chép (Documenting):** Đảm bảo file `.md` có chức năng chính (Business logic) và thành phần liên quan (Controller, Model, View, JS, CSS, Database Table).
3. **Lịch sử (Changelog):** Cập nhật ngắn gọn những gì "đã làm" hoặc "vừa fix".
4. **Gợi ý Tối ưu (Future Optimization):** Đề xuất 1-2 phương án có thể tối ưu trong tương lai.

---

## 🧪 SKILL 6: TEST_COVERAGE (Bảo Chứng Chất Lượng)

**Mô tả:** Tự động sinh ra các kịch bản kiểm thử (Test Cases) để đảm bảo độ ổn định của hệ thống.

**Triggers:**
- "Viết test cho file này..."
- Lệnh `/test`

**Workflow (Quy trình thực thi):**
1. **Đọc hiểu logic:** Phân tích kỹ thuật của Controller/Model/Service/JS Function cần test.
2. **Xác định Edge Cases:** Phân tích các trường hợp biên, dữ liệu rỗng, dữ liệu sai kiểu, tấn công XSS/SQL Injection.
3. **Sinh code test:** Sinh ra file Test (VD: dùng PHPUnit cho PHP hoặc Jest/Mocha cho JS) với các assertions chuẩn xác, mô phỏng (mock) dữ liệu đầy đủ.

---

## 🌿 SKILL 7: GIT_MASTER

**Mô tả:** Chuẩn hóa quy trình commit và quản lý phiên bản source code chuyên nghiệp.

**Triggers:**
- "Viết commit message cho đống code vừa rồi"
- "đẩy lên git"
- Lệnh `/commit`

**Workflow (Quy trình thực thi):**
1. **Đọc Diff:** Tự động tóm tắt những thay đổi vừa làm trong phiên làm việc.
2. **Định dạng Conventional Commits:** Luôn trả về commit message theo chuẩn `type(scope): subject`. Các type bao gồm:
   - `feat:` (Thêm tính năng mới)
   - `fix:` (Sửa lỗi)
   - `docs:` (Cập nhật tài liệu)
   - `style:` (Format code, thiếu dấu chấm phẩy...)
   - `refactor:` (Viết lại code nhưng không đổi logic)
   - `perf:` (Tối ưu hiệu năng)
   - `test:` (Thêm test case)
   - `chore:` (Bảo trì hệ thống)

---

# ⚡ HƯỚNG DẪN DÀNH CHO AGENT (SYSTEM DIRECTIVE)

**Ngôn ngữ giao tiếp (QUAN TRỌNG):** TRẢ LỜI HOÀN TOÀN BẰNG TIẾNG VIỆT 100% trong mọi tương tác.

**Tôn trọng thuật ngữ:** GIỮ NGUYÊN các thuật ngữ chuyên môn IT bằng tiếng Anh (ví dụ: Controller, Model, SQL Injection, Deploy, Refactor, Component, API, JSON, Request, Response...). Tuyệt đối KHÔNG dịch khiên cưỡng.

**Cấu trúc Hệ thống (System Structure):** Tuân thủ tuyệt đối kiến trúc MVC (Model - View - Controller). Controller KHÔNG ĐƯỢC chứa truy vấn SQL trực tiếp. Model KHÔNG ĐƯỢC trả về HTML.

**Quy tắc Xử lý Lỗi (Error Handling):** 
- Mọi thao tác Database (PDO) hoặc gọi External API (cURL/Fetch) BẮT BUỘC phải được bọc trong block `try...catch`.
- KHÔNG BAO GIỜ in lỗi hệ thống trực tiếp ra View (người dùng cuối). Phải ghi log (Error Logging) ẩn bên dưới và trả về câu thông báo thân thiện: "Hệ thống đang bận, vui lòng thử lại sau".

**Tiêu chuẩn API (API Standards):** 
- Mọi endpoint API phải trả về JSON chuẩn xác: `{"status": "success|error", "data": {}, "message": "..."}`.
- Sử dụng đúng HTTP Status Codes (200 OK, 201 Created, 400 Bad Request, 401 Unauthorized, 404 Not Found, 500 Internal Server Error).

**Quy chuẩn Đặt tên (Naming Conventions):**
- **PHP:** Class/Interface dùng `PascalCase` (VD: `UserController`). Biến/Hàm dùng `camelCase` (VD: `$userData`, `getUser()`). Hằng số dùng `UPPER_SNAKE_CASE` (VD: `MAX_LIMIT`). Tuân thủ PSR-12.
- **MySQL:** Tên Bảng và Cột luôn dùng `snake_case` (VD: `user_accounts`, `created_at`). Khóa ngoại phải có hậu tố `_id` (VD: `user_id`).
- **CSS:** Class và ID ưu tiên cấu trúc BEM hoặc kebab-case (VD: `btn-primary`, `card__header--active`).
- **JavaScript:** Biến/Hàm dùng `camelCase` (VD: `fetchData`, `handleButtonClick`). Class dùng `PascalCase`.

**Nhận thức Context qua Module:** Trước khi sửa code của một module bất kỳ, Agent PHẢI chủ động đọc file `.md` của module đó trong thư mục `docs/modules/` (nếu có).

**Ghi chép Thay đổi (Documentation Sync):** Mọi thay đổi (sửa lỗi, cập nhật tính năng, điều chỉnh giao diện/logic) tại bất kỳ module nào **BẮT BUỘC** phải được ghi lại ngay lập tức vào phần `Lịch sử thay đổi (Changelog)` trong file `.md` tương ứng tại thư mục `docs/modules/`. Điều này đảm bảo tài liệu luôn song hành cùng mã nguồn.

**Tuân thủ Cấu trúc Dự án (Structure Compliance):** Mọi thao tác tạo file, tổ chức thư mục và bố trí code (MVC, CSS, JS, Assets) **BẮT BUỘC** phải tuân thủ nghiêm ngặt các quy định tại file `project-structure.md`. Tuyệt đối không tự ý thay đổi kiến trúc thư mục đã đề ra.

**Bảo mật Tối đa:** KHÔNG BAO GIỜ hardcode MySQL credentials (root / password) hoặc API Keys. Mọi input (POST/GET) đều phải coi là rủi ro và cần validate/sanitize nghiêm ngặt.