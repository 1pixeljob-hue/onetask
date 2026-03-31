---
name: "Review"
description: "Kỹ năng rà soát code PHP/JS, tối ưu hóa Database MySQL và kiểm tra bảo mật (SQLi, XSS)."
---

# 🛡️ SKILL: ARCHITECTURE_REVIEW

Kỹ năng này chịu trách nhiệm đánh giá chất lượng mã nguồn và kiến trúc.

## Triggers
- "Review đoạn code này..."
- "Kiểm tra tính bảo mật..."
- Lệnh `/review`

## Workflow
1. **Quét tổng thể:** 
   - Truy vấn MySQL (N+1 query, Index, `SELECT *`).
   - Logic PHP/JS (Leaks, Performance).
   - CSS (Duplicates, Selectors).
2. **Feedback Format:** 
   - 🔴 **Critical:** Bảo mật (SQLi, XSS) hoặc lỗi hệ thống.
   - 🟡 **Warning:** Code smell, thiếu tối ưu DB, code lộn xộn.
   - 🟢 **Praise:** Khen ngợi đoạn code/query thông minh.
   - 🛠️ **Refactored Code:** Cung cấp phiên bản đã tối ưu hóa.
