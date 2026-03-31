---
name: "Debug"
description: "Kỹ năng phân tích log, gỡ lỗi logic và thực thi quy trình M.V.C.A để ổn định hệ thống."
---

# 🐛 SKILL: DEBUG (M.V.C.A)

Kỹ năng này áp dụng tư duy "THE M.V.C.A BUG FIXING WORKFLOW" để giải quyết các sự cố.

## Triggers
- "Sửa lỗi này..."
- "Trắng trang / Lỗi 500..."
- "Lỗi SQL syntax..." / "Vỡ layout CSS..." / "Lỗi Console JS..."
- Lệnh `/fix-issue`

## ⚙️ M.V.C.A EXECUTION
1. **[BƯỚC 1] Tiếp nhận, Khám nghiệm & Phân loại (Triage):** Kiểm tra Log, Payload, các bước tái tạo lỗi. Khoanh vùng layer [View/Controller/Model].
2. **[BƯỚC 2] Bắt bệnh & Rà soát Bảo mật (Root Cause & Security Audit):** Định vị dòng code lỗi. Kiểm duyệt SQL Injection/XSS.
3. **[BƯỚC 3] Đề xuất Code sửa lỗi (Code Fix & Refactor):** Tuân thủ PSR-12, PDO Prepared Statements. CHỈ cung cấp đoạn code thay đổi kèm comment `// ... existing code ...`.
4. **[BƯỚC 4] Kịch bản Kiểm thử & Khôi phục (Test & Rollback):** Hướng dẫn verify, xóa cache và lưu ý tác dụng phụ.
5. **[BƯỚC 5] Đóng gói (Deploy Preparation):** Đề xuất Git Commit chuẩn và bài học rút ra.

## Critical Constraints
- KHÔNG CHẠY SQL MÙ: Yêu cầu `EXPLAIN` nếu query chậm.
- KHÔNG PHÁ VỠ KIẾN TRÚC: Từ chối các bản "dirty hack".
- KIỂM DUYỆT TRƯỚC KHI TRẢ LỜI: Đảm bảo code sạch và an toàn.
