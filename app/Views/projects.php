<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <span class="logo-icon">1P</span>
                    <div class="logo-text">
                        <h2>1Pixel</h2>
                        <p>Quản lý công việc tập trung</p>
                    </div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="/" class="nav-item">
                    <i class="ph ph-squares-four"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/hostings" class="nav-item">
                    <i class="ph ph-hard-drives"></i>
                    <span>Hostings</span>
                </a>
                <a href="/projects" class="nav-item active">
                    <i class="ph ph-folders"></i>
                    <span>Projects</span>
                </a>
                <a href="/reports" class="nav-item">
                    <i class="ph ph-chart-bar"></i>
                    <span>B&#225;o C&#225;o</span>
                </a>
                <a href="/passwords" class="nav-item">
                    <i class="ph ph-key"></i>
                    <span>Passwords</span>
                </a>
                <a href="/codex" class="nav-item">
                    <i class="ph ph-code"></i>
                    <span>CodeX</span>
                </a>
                <a href="/logs" class="nav-item">
                    <i class="ph ph-file-text"></i>
                    <span>Logs</span>
                </a>
                <a href="/settings" class="nav-item">
                    <i class="ph ph-gear"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="support-box">
                    <h4>Cần hỗ trợ?</h4>
                    <p>Liên hệ với chúng tôi để được hỗ trợ</p>
                    <button class="btn-primary">Liên Hệ</button>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <h1>Quản Lý Project</h1>
                    <p>Quản lý tất cả project của bạn</p>
                </div>
                <div class="header-right">
                    <button class="btn-icon">
                        <i class="ph ph-bell"></i>
                        <span class="badge">1</span>
                    </button>
                    <div class="user-profile">
                        <div class="avatar">QD</div>
                        <div class="user-info">
                            <span class="user-name">Quy Dev</span>
                            <span class="user-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content-body">
                <!-- Toolbar -->
                <div class="pj-toolbar">
                    <div class="pj-search-wrap">
                        <i class="ph ph-magnifying-glass pj-search-icon"></i>
                        <input type="text" class="pj-search-input" id="projectSearch" placeholder="Tìm kiếm theo tên, khách hàng, mô tả...">
                    </div>
                    <div class="pj-toolbar-right">
                        <div class="pj-filter-wrapper">
                            <button class="pj-filter-btn" id="statusFilterBtn" onclick="toggleFilterDropdown()">
                                <i class="ph ph-funnel-simple"></i>
                                <span id="filterLabel">Tất cả trạng thái</span>
                                <i class="ph ph-caret-down"></i>
                            </button>
                            <div class="pj-dropdown" id="filterDropdown">
                                <div class="pj-dropdown-item active" onclick="setFilter('', 'Tất cả trạng thái', this)">Tất cả</div>
                                <div class="pj-dropdown-item" onclick="setFilter('doing', 'Đang Thực Hiện', this)">Đang Thực Hiện</div>
                                <div class="pj-dropdown-item" onclick="setFilter('testing', 'Chờ Nghiệm Thu', this)">Chờ Nghiệm Thu</div>
                                <div class="pj-dropdown-item" onclick="setFilter('done', 'Hoàn Thành', this)">Hoàn Thành</div>
                            </div>
                        </div>
                        <button class="pj-add-btn" onclick="openAddProjectModal()">
                            <i class="ph ph-plus"></i>
                            Thêm Mới
                        </button>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="40"><input type="checkbox" class="cb-custom"></th>
                                <th>TÊN PROJECT</th>
                                <th>KHÁCH HÀNG</th>
                                <th>GIÁ TRỊ</th>
                                <th>NGÀY TẠO</th>
                                <th>TRẠNG THÁI</th>
                                <th width="80" class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Row 1 -->
                            <tr data-status="doing">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Thêm sản phẩm cho web Trái Cây Lâm Thành</div>
                                    <div class="cell-sub"><i class="ph ph-link"></i> https://lamthanhfruit.myshopify.com/a...</div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-user-circle color-gray"></i>
                                        <span>Khánh Linh</span>
                                    </div>
                                </td>
                                <td><span class="val-badge"><i class="ph ph-currency-circle-dollar"></i> 3.5M</span></td>
                                <td>
                                    <div class="date-info text-main"><i class="ph ph-calendar-blank"></i> 10/03/2026</div>
                                </td>
                                <td><span class="status-badge doing"><i class="ph ph-circle-notch"></i> Đang Thực Hiện</span></td>
                                <td class="text-center"><button class="btn-action"><i class="ph ph-dots-three"></i></button></td>
                            </tr>

                            <!-- Row 2 -->
                            <tr data-status="testing">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Onelaw Code section tài liệu kèm iframe view</div>
                                    <div class="cell-sub"><i class="ph ph-link"></i> https://onelawvn.com/adminxxxx</div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-user-circle color-gray"></i>
                                        <span>Onelaw</span>
                                    </div>
                                </td>
                                <td><span class="val-badge"><i class="ph ph-currency-circle-dollar"></i> 1.5M</span></td>
                                <td>
                                    <div class="date-info text-main"><i class="ph ph-calendar-blank"></i> 05/03/2026</div>
                                </td>
                                <td><span class="status-badge testing"><i class="ph ph-clock"></i> Chờ Nghiệm Thu</span></td>
                                <td class="text-center"><button class="btn-action"><i class="ph ph-dots-three"></i></button></td>
                            </tr>

                            <!-- Row 3 -->
                            <tr data-status="testing">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Thiết kế web Nam Việt Food Land</div>
                                    <div class="cell-sub"><i class="ph ph-link"></i> https://namviethoodland.com/nam-l...</div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-user-circle color-gray"></i>
                                        <span>Anh Nguyễn Sư</span>
                                    </div>
                                </td>
                                <td><span class="val-badge"><i class="ph ph-currency-circle-dollar"></i> 4.0M</span></td>
                                <td>
                                    <div class="date-info text-main"><i class="ph ph-calendar-blank"></i> 03/03/2026</div>
                                </td>
                                <td><span class="status-badge testing"><i class="ph ph-clock"></i> Chờ Nghiệm Thu</span></td>
                                <td class="text-center"><button class="btn-action"><i class="ph ph-dots-three"></i></button></td>
                            </tr>

                            <!-- Row 4 -->
                            <tr data-status="done">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Hỗ trợ chị Hạnh xử lý web Phú Thành</div>
                                    <div class="cell-sub"><i class="ph ph-link"></i> https://phuthanh.net/phu-admin</div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-user-circle color-gray"></i>
                                        <span>Phú Thành</span>
                                    </div>
                                </td>
                                <td><span class="val-badge"><i class="ph ph-currency-circle-dollar"></i> 1.0M</span></td>
                                <td>
                                    <div class="date-info text-main"><i class="ph ph-calendar-blank"></i> 05/03/2026</div>
                                </td>
                                <td><span class="status-badge success"><i class="ph ph-check-circle"></i> Hoàn Thành</span></td>
                                <td class="text-center"><button class="btn-action"><i class="ph ph-dots-three"></i></button></td>
                            </tr>

                            <!-- Row 5 -->
                            <tr data-status="done">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Sayoung - Đăng ký website với Bộ Công Thương</div>
                                    <div class="cell-sub"><i class="ph ph-link"></i> https://dichvucong.moit.gov.vn/Log...</div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-user-circle color-gray"></i>
                                        <span>Sayoung</span>
                                    </div>
                                </td>
                                <td><span class="val-badge"><i class="ph ph-currency-circle-dollar"></i> 2.5M</span></td>
                                <td>
                                    <div class="date-info text-main"><i class="ph ph-calendar-blank"></i> 25/01/2026</div>
                                </td>
                                <td><span class="status-badge success"><i class="ph ph-check-circle"></i> Hoàn Thành</span></td>
                                <td class="text-center"><button class="btn-action"><i class="ph ph-dots-three"></i></button></td>
                            </tr>

                            <!-- Row 6 -->
                            <tr data-status="done">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Thiết kế Landing cho Onelaw.vn</div>
                                    <div class="cell-sub"><i class="ph ph-link"></i> https://onelaw.vn/adminxxxx</div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-user-circle color-gray"></i>
                                        <span>A Hùng</span>
                                    </div>
                                </td>
                                <td><span class="val-badge"><i class="ph ph-currency-circle-dollar"></i> 1.0M</span></td>
                                <td>
                                    <div class="date-info text-main"><i class="ph ph-calendar-blank"></i> 24/01/2026</div>
                                </td>
                                <td><span class="status-badge success"><i class="ph ph-check-circle"></i> Hoàn Thành</span></td>
                                <td class="text-center"><button class="btn-action"><i class="ph ph-dots-three"></i></button></td>
                            </tr>

                            <!-- Row 7 -->
                            <tr data-status="done">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Thiết kế web Pearlcenter</div>
                                    <div class="cell-sub"><i class="ph ph-link"></i> https://pearlcenter.vn/pro-login</div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-user-circle color-gray"></i>
                                        <span>A Hùng</span>
                                    </div>
                                </td>
                                <td><span class="val-badge"><i class="ph ph-currency-circle-dollar"></i> 5.0M</span></td>
                                <td>
                                    <div class="date-info text-main"><i class="ph ph-calendar-blank"></i> 22/01/2026</div>
                                </td>
                                <td><span class="status-badge success"><i class="ph ph-check-circle"></i> Hoàn Thành</span></td>
                                <td class="text-center"><button class="btn-action"><i class="ph ph-dots-three"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="logs-pagination-row">
                    <span class="logs-count">Hiển thị: 1 - 7 / 42 hostings</span>
                    <div class="logs-pagination">
                        <button class="pg-btn"><i class="ph ph-caret-left"></i></button>
                        <button class="pg-btn active">1</button>
                        <button class="pg-btn">2</button>
                        <button class="pg-btn">3</button>
                        <button class="pg-btn"><i class="ph ph-caret-right"></i></button>
                    </div>
                </div>
            </div>
        </main>
    </div>

<!-- Modal Thêm Project Mới -->
<div class="modal-overlay" id="addProjectModal" onclick="closeAddProjectModalOverlay(event)">
    <div class="modal-box scrollable">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle"><i class="ph ph-folders"></i> Thêm Project Mới</h3>
            <button class="modal-close" onclick="closeAddProjectModal()"><i class="ph ph-x"></i></button>
        </div>
        <div class="modal-body">
            <!-- Section 1: Thông Tin Project -->
            <div class="modal-section-header">
                <span class="modal-section-title">Thông Tin Project</span>
            </div>
            
            <div class="modal-field full">
                <label class="modal-label">Tên Project <span class="req">*</span></label>
                <input type="text" class="modal-input" id="mProjectName" placeholder="VD: Website Thương Mại Điện Tử">
            </div>

            <div class="modal-field full">
                <label class="modal-label">Trạng Thái <span class="req">*</span></label>
                <div class="modal-select-wrapper">
                    <select class="modal-input modal-select-status" id="mProjectStatus">
                        <option value="planning">Lên Kế Hoạch</option>
                        <option value="doing" selected>Đang Thực Hiện</option>
                        <option value="testing">Chờ Nghiệm Thu</option>
                        <option value="done">Hoàn Thành</option>
                    </select>
                </div>
            </div>

            <div class="modal-field full">
                <label class="modal-label">Mô Tả</label>
                <textarea class="modal-textarea" id="mProjectDesc" placeholder="Mô tả chi tiết về project..."></textarea>
            </div>

            <div class="modal-field full">
                <label class="modal-label">Ngày Tạo <span class="req">*</span></label>
                <input type="date" class="modal-input" id="mProjectDate">
                <p class="modal-field-hint">Ngày bắt đầu thực hiện dự án</p>
            </div>

            <!-- Section 2: Thông Tin Khách Hàng -->
            <div class="modal-section-header with-border">
                <span class="modal-section-title">Thông Tin Khách Hàng</span>
            </div>
            
            <div class="modal-row">
                <div class="modal-field">
                    <label class="modal-label">Tên Khách Hàng <span class="req">*</span></label>
                    <input type="text" class="modal-input" id="mCustomerName" placeholder="VD: Nguyễn Văn A">
                </div>
                <div class="modal-field">
                    <label class="modal-label">Số Điện Thoại</label>
                    <input type="text" class="modal-input" id="mCustomerPhone" placeholder="VD: 0912345678">
                </div>
            </div>

            <!-- Section 3: Thông Tin Quản Trị -->
            <div class="modal-section-header with-border">
                <span class="modal-section-title">Thông Tin Quản Trị</span>
            </div>
            
            <div class="modal-field full">
                <label class="modal-label">Đường Dẫn Admin</label>
                <input type="text" class="modal-input" id="mAdminLink" placeholder="VD: https://example.com/admin">
            </div>

            <div class="modal-row">
                <div class="modal-field">
                    <label class="modal-label">Tài Khoản Admin</label>
                    <input type="text" class="modal-input" id="mAdminUser" placeholder="VD: admin">
                </div>
                <div class="modal-field">
                    <label class="modal-label">Mật Khẩu Admin</label>
                    <div class="modal-input-group">
                        <input type="password" class="modal-input" id="adminPassword" placeholder="********">
                        <button type="button" class="modal-input-icon-btn" onclick="togglePasswordVisibility('adminPassword', this)">
                            <i class="ph ph-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section 4: Thông Tin Tài Chính -->
            <div class="modal-section-header with-border">
                <span class="modal-section-title">Thông Tin Tài Chính</span>
            </div>
            
            <div class="modal-field full">
                <label class="modal-label">Giá Trị Dự Án (VNĐ)</label>
                <div class="modal-input-group">
                    <i class="ph ph-currency-circle-dollar modal-input-prefix"></i>
                    <input type="number" class="modal-input with-prefix" id="projectValue" value="0" oninput="updateProjectValueDisplay(this)">
                </div>
                <div id="projectValueDisplay" class="modal-value-hint">0 VNĐ</div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-cancel" onclick="closeAddProjectModal()">Hủy</button>
            <button class="modal-btn-submit" onclick="submitProjectForm()">Thêm Mới</button>
        </div>
    </div>
</div>

<!-- Shared Row Action Dropdown -->
<div class="row-action-menu" id="rowActionMenu">
    <button class="ram-item ram-view">
        <i class="ph ph-eye"></i> Xem Chi Tiết
    </button>
    <button class="ram-item ram-edit">
        <i class="ph ph-pencil-simple"></i> Chỉnh Sửa
    </button>
    <div class="ram-divider"></div>
    <button class="ram-item ram-delete">
        <i class="ph ph-trash"></i> Xóa
    </button>
</div>

<!-- Modal Xác nhận Xóa -->
<div class="modal-overlay" id="confirmDeleteModal" onclick="closeConfirmDelete(event)">
    <div class="modal-box" style="max-width: 420px; padding: 24px; border-radius: 16px;">
        <button class="modal-close" style="position: absolute; right: 20px; top: 20px;" onclick="closeConfirmDeleteBtn()"><i class="ph ph-x"></i></button>
        
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <div style="color: #ef4444; font-size: 24px; display: flex; align-items: center;"><i class="ph ph-warning"></i></div>
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--text-main);">Xác nhận xóa project</h3>
        </div>
        
        <p style="margin: 0 0 24px 0; color: #4b5563; font-size: 14px; line-height: 1.5;">
            Bạn có chắc chắn muốn xóa project "<strong id="cdmProjectName"></strong>"?<br> Hành động này không thể hoàn tác.
        </p>
        
        <div style="display: flex; gap: 12px;">
            <button class="modal-btn-cancel" style="flex: 1; justify-content: center; border-radius: 8px; font-weight: 600; padding: 10px;" onclick="closeConfirmDeleteBtn()">Cancel</button>
            <button class="modal-btn-submit" style="flex: 1; background: #fe2c2c; justify-content: center; border-radius: 8px; font-weight: 600; padding: 10px;" onclick="confirmDeleteAction()">OK</button>
        </div>
    </div>
</div>

<script>
function toggleFilterDropdown() {
    document.getElementById('filterDropdown').classList.toggle('open');
}
function setFilter(val, label, el) {
    document.getElementById('filterLabel').textContent = label;
    document.querySelectorAll('.pj-dropdown-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('filterDropdown').classList.remove('open');
    document.querySelectorAll('.data-table tbody tr').forEach(row => {
        if (!val) { row.style.display = ''; return; }
        row.style.display = row.dataset.status === val ? '' : 'none';
    });
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.pj-filter-wrapper')) {
        const dd = document.getElementById('filterDropdown');
        if (dd) dd.classList.remove('open');
    }
});
document.getElementById('projectSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.data-table tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

// Modal Controls
function openAddProjectModal() {
    document.getElementById('addProjectModal').classList.add('active');
    document.querySelectorAll('.pj-filter-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('addProjectModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeAddProjectModal() {
    document.getElementById('addProjectModal').classList.remove('active');
    document.body.style.overflow = '';
}

function closeConfirmDeleteBtn() {
    document.getElementById('confirmDeleteModal').classList.remove('active');
    document.body.style.overflow = '';
}
function closeConfirmDelete(e) {
    if (e.target === document.getElementById('confirmDeleteModal')) closeConfirmDeleteBtn();
}

let rowToDelete = null;
function confirmDeleteAction() {
    if (rowToDelete) {
        rowToDelete.remove();
        closeConfirmDeleteBtn();
    }
}

document.addEventListener('click', function(e) {
    const menu = document.getElementById('rowActionMenu');

    // Toggle row action menu
    if (e.target.closest('.btn-action')) {
        const btn = e.target.closest('.btn-action');
        const isOpen = menu.classList.contains('open') && menu._trigger === btn;
        menu.classList.remove('open');
        if (!isOpen) {
            const rect = btn.getBoundingClientRect();
            menu.style.top  = (rect.bottom + window.scrollY + 6) + 'px';
            
            // Replicate logic from hosting
            const menuWidth = 160;
            let leftPos = rect.right + window.scrollX - menuWidth - 4;
            menu.style.left = Math.max(4, leftPos) + 'px';
            
            menu.classList.add('open');
            menu._trigger = btn;
        }
        e.stopPropagation();
        return;
    }

    // Click logic for menu items
    if (e.target.closest('.ram-item')) {
        const item = e.target.closest('.ram-item');
        const tr = menu._trigger ? menu._trigger.closest('tr') : null;
        menu.classList.remove('open');

        if (tr) {
            if (item.classList.contains('ram-delete')) {
                rowToDelete = tr;
                const name = tr.querySelector('.cell-main').textContent.trim();
                document.getElementById('cdmProjectName').textContent = name;
                document.getElementById('confirmDeleteModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            // Add view/edit logic if needed later
        }
        return;
    }

    // Close on outside click
    if (!e.target.closest('#rowActionMenu')) {
        menu.classList.remove('open');
    }
});
function closeAddProjectModalOverlay(e) {
    if (e.target === document.getElementById('addProjectModal')) closeAddProjectModal();
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('ph-eye', 'ph-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('ph-eye-slash', 'ph-eye');
    }
}

function updateProjectValueDisplay(input) {
    const display = document.getElementById('projectValueDisplay');
    const val = parseInt(input.value) || 0;
    display.textContent = val.toLocaleString('vi-VN') + ' VNĐ';
}

function submitProjectForm() {
    const name = document.getElementById('mProjectName').value.trim();
    const status = document.getElementById('mProjectStatus').value;
    const date = document.getElementById('mProjectDate').value;
    const customer = document.getElementById('mCustomerName').value.trim();
    const valRaw = parseInt(document.getElementById('projectValue').value) || 0;
    const link = document.getElementById('mAdminLink').value.trim() || 'N/A';

    if (!name || !date || !customer) {
        alert('Vui lòng điền đầy đủ các thông tin bắt buộc (*)');
        return;
    }

    // Status Mapping
    const statusInfo = {
        'planning': { cls: 'warning', icon: 'ph-calendar-plus', label: 'Lên Kế Hoạch' },
        'doing': { cls: 'doing', icon: 'ph-clock', label: 'Đang Thực Hiện' },
        'testing': { cls: 'testing', icon: 'ph-flask', label: 'Chờ Nghiệm Thu' },
        'done': { cls: 'done', icon: 'ph-check-circle', label: 'Hoàn Thành' }
    };
    const s = statusInfo[status];

    // Format Date (YYYY-MM-DD to DD/MM/YYYY)
    const [y, m, d] = date.split('-');
    const formattedDate = `${d}/${m}/${y}`;

    // Format Value (e.g. 3,500,000 -> 3.5M)
    let formattedVal = '0';
    if (valRaw >= 1000000) {
        formattedVal = (valRaw / 1000000).toFixed(1).replace('.0', '') + 'M';
    } else if (valRaw >= 1000) {
        formattedVal = (valRaw / 1000).toFixed(0) + 'K';
    } else {
        formattedVal = valRaw.toString();
    }

    const tbody = document.querySelector('.data-table tbody');
    const newRow = document.createElement('tr');
    newRow.setAttribute('data-status', status);
    newRow.innerHTML = `
        <td><input type="checkbox" class="cb-custom"></td>
        <td>
            <div class="cell-main">${name}</div>
            <div class="cell-sub"><i class="ph ph-link"></i> ${link}</div>
        </td>
        <td>
            <div class="provider-info">
                <i class="ph ph-user-circle color-gray"></i>
                <span>${customer}</span>
            </div>
        </td>
        <td><span class="val-badge"><i class="ph ph-currency-circle-dollar"></i> ${formattedVal}</span></td>
        <td>
            <div class="date-info text-main"><i class="ph ph-calendar-blank"></i> ${formattedDate}</div>
        </td>
        <td>
            <span class="status-badge ${s.cls}">
                <i class="ph ${s.icon}"></i>
                ${s.label}
            </span>
        </td>
        <td class="text-center"><button class="btn-action"><i class="ph ph-dots-three"></i></button></td>
    `;

    tbody.prepend(newRow);
    closeAddProjectModal();
    resetProjectForm();
}

function resetProjectForm() {
    document.getElementById('mProjectName').value = '';
    document.getElementById('mProjectStatus').value = 'doing';
    document.getElementById('mProjectDesc').value = '';
    document.getElementById('mProjectDate').value = '';
    document.getElementById('mCustomerName').value = '';
    document.getElementById('mCustomerPhone').value = '';
    document.getElementById('mAdminLink').value = '';
    document.getElementById('mAdminUser').value = '';
    document.getElementById('adminPassword').value = '';
    document.getElementById('projectValue').value = 0;
    document.getElementById('projectValueDisplay').textContent = '0 VNĐ';
}
</script>
</body>
</html>
