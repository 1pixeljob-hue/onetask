---
description: "Quy trình sửa lỗi và review code theo chuẩn M.V.C.A (Model-View-Controller-Audit)."
---

# 🚨 WORKFLOW: THE M.V.C.A BUG FIXING

Áp dụng khi nhận được yêu cầu sửa lỗi hoặc review code từ người dùng.

## ⚙️ QUY TRÌNH THỰC THI CHÍNH (CORE EXECUTION)

### [BƯỚC 1] Tiếp nhận, Khám nghiệm & Phân loại (Triage)
- **Kiểm tra Dữ kiện:** Yêu cầu Log (PHP error, Console), Payload Request/Response, và Các bước tái tạo (Reproduce).
- **Phân loại Layer:** Xác định lỗi nằm ở [VIEW], [CONTROLLER] hay [MODEL].

### [BƯỚC 2] Bắt bệnh & Rà soát Bảo mật (Root Cause & Security Audit)
- **Định vị:** Chỉ ra dòng code/hàm gây lỗi.
- **Bảo vệ MVC:** Cảnh báo nếu vi phạm (vd: SQL trong Controller) và yêu cầu tách đúng tầng.
- **Security Audit:** Kiểm tra SQL Injection, XSS, CSRF. Fix ngay lập tức nếu phát hiện rủi ro.

### [BƯỚC 3] Đề xuất Code sửa lỗi (Code Fix & Refactor)
- **Tiêu chuẩn:** PSR-12, "Early Return", tránh lồng lặp vô nghĩa.
- **MySQL:** Dùng Prepared Statements (PDO), tối ưu Index, hạn chế `SELECT *`.
- **Trình bày:** Chỉ cung cấp đoạn code thay đổi kèm comment `// ... existing code ...`.

### [BƯỚC 4] Kịch bản Kiểm thử & Khôi phục (Test & Rollback)
- **Verify:** Hướng dẫn 1-2 bước verify kết quả.
- **Clear Cache:** Lệnh xóa cache phù hợp (Opcache, Browser cache).
- **Rollback:** Cảnh báo tác dụng phụ (side-effects) và cách khôi phục code cũ.

### [BƯỚC 5] Đóng gói (Deploy Preparation)
- **Git Commit:** Đề xuất commit `FIX: [Module] - [Hành động] (Ticket ID)`.
- **Post-mortem:** Giải thích lý do gốc rễ gây ra bug để team rút kinh nghiệm.

---

## 🚫 CÁC RÀNG BUỘC TỐI THƯỢNG (CRITICAL CONSTRAINTS)
1. **KHÔNG CHẠY SQL MÙ:** Yêu cầu `EXPLAIN` cho query chậm.
2. **KHÔNG PHÁ VỠ KIẾN TRÚC:** Từ chối "dirty hack".
3. **KIỂM DUYỆT TRƯỚC KHI TRẢ LỜI:** Đảm bảo an toàn và không rác.

---

## 📝 ĐỊNH DẠNG ĐẦU RA BẮT BUỘC (MANDATORY OUTPUT FORMAT)
Mọi phản hồi fix bug phải tuân thủ cấu trúc Markdown sau:

### 🔍 [1 & 2] Phân loại & Nguyên nhân gốc rễ
- **Tầng bị ảnh hưởng:** [Model / View / Controller]
- **Nguyên nhân cốt lõi:** [Giải thích ngắn gọn]
- **Rà soát Bảo mật/MVC:** [An toàn/CẢNH BÁO ĐỎ]

### 🛠️ [3] Giải pháp & Code đề xuất
- **Tư duy tối ưu:** [Giải thích logic và chuẩn PSR]
[... Code Block ...]

### 🧪 [4] Kịch bản Kiểm thử & Khôi phục
1. **Cách kiểm tra:** [Steps]
2. **Xóa bộ nhớ đệm:** [Command]
3. **Rủi ro & Rollback:** [Notes]

### 📦 [5] Git Commit & Post-mortem
- **Commit:** `FIX: [Module] - [Hành động]`
- **Bài học:** [Summary]
