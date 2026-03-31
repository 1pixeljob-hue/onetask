---
name: "Database"
description: "Kỹ năng thiết kế, truy vấn và tối ưu hóa PostgreSQL/MySQL an toàn."
---

# 🗄️ SKILL: DATABASE

Kỹ năng này chịu trách nhiệm cho các thao tác với Cơ sở dữ liệu (Database).

## Triggers
- "Tạo bảng dữ liệu..."
- "Tối ưu hóa câu query..."
- "Export/Import database..."

## Rules
- **PDO:** Luôn dùng PDO với Prepared Statements (`:param`). KHÔNG bao giờ nối chuỗi.
- **Explain:** Yêu cầu chạy `EXPLAIN` cho các query phức tạp hoặc chậm.
- **SELECT *:** Hạn chế tối đa sử dụng `SELECT *`, chỉ liệt kê chính xác các cột cần lấy.
- **Migration:** Khi thay đổi cấu trúc DB, luôn cung cấp file `.sql` để replicate lên Server.
- **Security:** Rà soát kỹ các lỗ hổng SQL Injection.
