<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        // Data injected from PHP Models
        const PHP_DATA = {
            projects: <?php echo json_encode($projects ?? []); ?>,
            hostings: <?php echo json_encode($hostings ?? []); ?>
        };
    </script>
    <script src="/js/shared-data.js"></script>
</head>
<body>
    <!-- Global Loader -->
    <div id="global-loader" class="global-loader">
        <div class="loader-spinner"></div>
        <div class="loader-text">Đang tải...</div>
    </div>

    <div class="app-container">
        <!-- Sidebar -->
        <?php 
            $activePage = 'projects';
            include APP_DIR . '/Views/partials/sidebar.php'; 
        ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <?php 
                $pageTitle = 'Quản Lý Project';
                $pageSubtitle = 'Quản lý tất cả project của bạn';
                include APP_DIR . '/Views/partials/header.php'; 
            ?>

            <!-- Bulk Action Bar -->
            <div class="bulk-action-bar" id="bulkActionBar">
                <div class="bab-left">
                    <i class="ph-fill ph-check-circle"></i>
                    <span id="selectedCountText">Đã chọn 0 project</span>
                </div>
                <div class="bab-right">
                    <button class="btn-deselect" onclick="deselectAllProjects()">Bỏ chọn</button>
                    <button class="btn-bulk-delete" onclick="promptBulkDelete()">
                        <i class="ph ph-trash"></i>
                        <span id="bulkDeleteBtnText">Xóa 0</span>
                    </button>
                </div>
            </div>

            <div class="content-body">
                <!-- Browser Honeypot for anti-autofill -->
                <div style="display: none;">
                    <input type="text" name="username">
                    <input type="password" name="password">
                </div>
                <!-- Toolbar -->
                <div class="pj-toolbar">
                    <div class="pj-search-wrap">
                        <i class="ph ph-magnifying-glass pj-search-icon"></i>
                        <input type="text" class="pj-search-input" id="p_search_v2" name="q_search_field" placeholder="Tìm kiếm theo tên, khách hàng, mô tả..." autocomplete="chrome-off">
                    </div>
                    <div class="pj-toolbar-right">
                        <div class="pj-filter-wrapper">
                            <button class="pj-filter-btn" id="statusFilterBtn" onclick="toggleFilterDropdown()">
                                <i class="ph ph-funnel-simple"></i>
                                <span id="filterLabel">Lọc bởi trạng thái</span>
                                <i class="ph ph-caret-down"></i>
                            </button>
                            <div class="pj-dropdown" id="filterDropdown">
                                <div class="pj-dropdown-item active" onclick="setFilter('', 'Lọc bởi trạng thái', this)">Tất cả</div>
                                <div class="pj-dropdown-item" onclick="setFilter('planning', 'Lên Kế Hoạch', this)">Lên Kế Hoạch</div>
                                <div class="pj-dropdown-item" onclick="setFilter('doing', 'Đang Thực Hiện', this)">Đang Thực Hiện</div>
                                <div class="pj-dropdown-item" onclick="setFilter('testing', 'Chờ Nghiệm Thu', this)">Chờ Nghiệm Thu</div>
                                <div class="pj-dropdown-item" onclick="setFilter('done', 'Hoàn Thành', this)">Hoàn Thành</div>
                                <div class="pj-dropdown-item" onclick="setFilter('paused', 'Tạm Dừng', this)">Tạm Dừng</div>
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
                                <th width="40"><input type="checkbox" class="cb-custom" id="selectAllProjects" onclick="toggleSelectAll(this)"></th>
                                <th>TÊN PROJECT</th>
                                <th>KHÁCH HÀNG</th>
                                <th>GIÁ TRỊ</th>
                                <th>NGÀY TẠO</th>
                                <th>TRẠNG THÁI</th>
                                <th width="80" class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody id="projectTableBody">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="logs-pagination-row" id="paginationRow" style="display: none;">
                    <span class="logs-count" id="paginationCount">Hiển thị <b>0</b> đến <b>0</b> trong tổng số <b>0</b> kết quả</span>
                    <div class="logs-pagination" id="paginationButtons">
                        <!-- Buttons populated by JS -->
                    </div>
                </div>
            </div>
        </main>
    </div>

<!-- Modal Thêm Project Mới -->
<div class="modal-overlay" id="addProjectModal" onclick="closeAddProjectModalOverlay(event)">
    <div class="modal-box scrollable">
        <div class="modal-header">
            <div class="modal-title-wrap">
                <div class="modal-icon-brand">
                    <i id="modalBrandIcon" class="ph-fill ph-folders"></i>
                </div>
                <h3 class="modal-title" id="modalTitle">Thêm Project Mới</h3>
            </div>
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
                <div class="pj-modal-select" id="mProjectStatusSelect" data-input-id="mProjectStatus">
                    <div class="pj-modal-select-trigger" onclick="togglePjModalSelect(this)">
                        <span>Lên Kế Hoạch</span>
                        <i class="ph ph-caret-down trigger-chevron"></i>
                    </div>
                    <div class="pj-modal-select-menu pj-dropdown" style="width: 100%; right: auto; left: 0;">
                        <div class="pj-dropdown-item active" data-value="planning">
                            <span>Lên Kế Hoạch</span>
                        </div>
                        <div class="pj-dropdown-item" data-value="doing">
                            <span>Đang Thực Hiện</span>
                        </div>
                        <div class="pj-dropdown-item" data-value="testing">
                            <span>Chờ Nghiệm Thu</span>
                        </div>
                        <div class="pj-dropdown-item" data-value="done">
                            <span>Hoàn Thành</span>
                        </div>
                        <div class="pj-dropdown-item" data-value="paused">
                            <span>Tạm Dừng</span>
                        </div>
                    </div>
                    <!-- Actual hidden input for form data -->
                    <input type="hidden" id="mProjectStatus" value="planning">
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
                <div id="projectValueDisplay" class="modal-price-hint">0 VNĐ</div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-cancel" onclick="closeAddProjectModal()">Hủy</button>
            <button class="modal-btn-submit" onclick="submitProjectForm()">Thêm Mới</button>
        </div>
    </div>
</div>

<!-- Shared Row Action Dropdown -->
<!-- Modal Chi Tiết Project -->
<div class="modal-overlay" id="detailProjectModal" onclick="closeDetailProjectModal(event)">
    <div class="modal-box scrollable">
        <div class="modal-header-gradient">
            <h3 class="modal-title-light"><i class="ph ph-folders"></i> Chi Tiết Project</h3>
            <button class="modal-close-light" onclick="closeDetailProjectModalBtn()"><i class="ph ph-x"></i></button>
        </div>
        <div class="modal-body">
            <div class="detail-top-row" style="margin-bottom: 24px;">
                <div id="dpStatusBadge">
                    <!-- Dynamic Status -->
                </div>
                <div class="modal-value-large">
                    <i class="ph ph-currency-circle-dollar"></i>
                    <span id="dpValue">0 VNĐ</span>
                </div>
            </div>

            <!-- Section 1: Thông Tin Chung (Gray Card) -->
            <div class="detail-gray-card">
                <div class="detail-group">
                    <span class="detail-label">Tên Project</span>
                    <span class="detail-val" id="dpName" style="font-size: 16px;">Tên dự án...</span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Mô Tả</span>
                    <div class="detail-desc-box" id="dpDesc">
                        Không có mô tả.
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="detail-group">
                        <span class="detail-label-flex"><i class="ph ph-user"></i> Khách Hàng</span>
                        <span class="dgc-val" id="dpCustomer">Khách hàng...</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label-flex"><i class="ph ph-phone"></i> Số Điện Thoại</span>
                        <span class="dgc-val" id="dpPhone">N/A</span>
                    </div>
                </div>

                <div class="detail-group">
                    <span class="detail-label-flex"><i class="ph ph-calendar-blank"></i> Ngày Tạo</span>
                    <span class="dgc-val" id="dpDate">--/--/----</span>
                </div>
            </div>

            <!-- Section 2: Quản Trị (White Card) -->
            <div class="modal-section-header with-border" style="margin-top: 20px;">
                <span class="modal-section-title"><i class="ph ph-lock" style="margin-right: 6px;"></i> Thông Tin Quản Trị</span>
            </div>

            <div class="detail-copy-group">
                <div class="detail-group">
                    <span class="detail-label">URL</span>
                    <div class="detail-copy-row">
                        <div class="detail-copy-field">
                            <i class="ph ph-link"></i>
                            <span id="dpAdminUrl">https://...</span>
                        </div>
                        <button class="btn-copy-small" onclick="copyTextFrom('dpAdminUrl')">
                            <i class="ph ph-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="detail-group">
                    <span class="detail-label">Tài Khoản</span>
                    <div class="detail-copy-row">
                        <div class="detail-copy-field">
                            <span id="dpAdminUser">admin</span>
                        </div>
                        <button class="btn-copy-small" onclick="copyTextFrom('dpAdminUser')">
                            <i class="ph ph-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="detail-group">
                    <span class="detail-label">Mật Khẩu</span>
                    <div class="detail-copy-row">
                        <div class="detail-copy-field" style="color: #64748b;">
                            <span id="dpAdminPass">********</span>
                        </div>
                        <button class="btn-copy-small" onclick="copyTextFrom('dpAdminPass')">
                            <i class="ph ph-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="detail-footer">
            <button class="btn-edit-full" onclick="openEditProjectFromDetail()">
                <i class="ph ph-pencil-simple"></i> Chỉnh Sửa
            </button>
            <button class="btn-delete-outline" onclick="deleteProjectFromDetail()">
                <i class="ph ph-trash"></i>
            </button>
        </div>
    </div>
</div>

    <div class="delete-toast" id="deleteToast">
        <div class="toast-spinner" id="dtSpinner"></div>
        <div id="dtSuccessIcon" style="display:none; color: #10b981; font-size: 22px; align-items: center; justify-content: center;"><i class="ph-fill ph-check-circle"></i></div>
        <div id="dtErrorIcon" style="display:none; color: #ef4444; font-size: 22px; align-items: center; justify-content: center;"><i class="ph-fill ph-x-circle"></i></div>
        <span id="dtMessage" style="color: #1e293b; font-weight: 600;">Đang xử lý...</span>
    </div>

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
            <button class="modal-btn-submit" id="cdmConfirmBtn" style="flex: 1; background: #fe2c2c; justify-content: center; border-radius: 8px; font-weight: 600; padding: 10px;" onclick="confirmDeleteAction()">OK</button>
        </div>
    </div>
</div>

<script>
let selectedProjects = new Set();
let currentPage = 1;
const itemsPerPage = 10;
let filteredProjects = [];

function clearErrors() {
    document.querySelectorAll('.modal-input-error').forEach(el => el.classList.remove('modal-input-error'));
}

function markError(id, isCustom = false) {
    const el = document.getElementById(id);
    if (!el) return;
    if (isCustom) {
        const container = el.closest('.pj-modal-select');
        if (container) {
            container.classList.add('modal-input-error');
            const trigger = container.querySelector('.pj-modal-select-trigger');
            if (trigger) trigger.focus();
        }
    } else {
        el.classList.add('modal-input-error');
        el.focus();
    }
}

function showToast(msg, icon = 'dtSpinner') {
    const t = document.getElementById('deleteToast');
    const m = document.getElementById('dtMessage');
    const s = document.getElementById('dtSpinner');
    const succ = document.getElementById('dtSuccessIcon');
    const err = document.getElementById('dtErrorIcon');
    if (!t) return;
    m.textContent = msg;
    t.classList.add('show');
    
    s.style.display = 'none';
    succ.style.display = 'none';
    err.style.display = 'none';

    if (icon === 'success') {
        succ.style.display = 'flex';
    } else if (icon === 'error') {
        err.style.display = 'flex';
    } else {
        s.style.display = 'block';
    }
}

function hideToast() {
    const t = document.getElementById('deleteToast');
    if (t) t.classList.remove('show');
}

function updateBulkActionBar() {
    const bar = document.getElementById('bulkActionBar');
    const countText = document.getElementById('selectedCountText');
    const deleteBtnText = document.getElementById('bulkDeleteBtnText');
    const count = selectedProjects.size;

    if (count > 0) {
        countText.textContent = `Đã chọn ${count} project`;
        deleteBtnText.textContent = `Xóa ${count}`;
        bar.classList.add('show');
    } else {
        bar.classList.remove('show');
    }
}

function toggleSelectAll(masterCb) {
    const checkboxes = document.querySelectorAll('.data-table tbody .cb-custom');
    selectedProjects.clear();
    
    checkboxes.forEach(cb => {
        const row = cb.closest('tr');
        if (row.style.display !== 'none') {
            cb.checked = masterCb.checked;
            if (cb.checked) {
                const id = row.getAttribute('data-id');
                if (id) selectedProjects.add(id);
            }
        } else {
            cb.checked = false;
        }
    });
    updateBulkActionBar();
}

function handleRowSelection(cb) {
    const row = cb.closest('tr');
    const id = row.getAttribute('data-id');
    if (cb.checked) {
        if (id) selectedProjects.add(id);
    } else {
        if (id) selectedProjects.delete(id);
        document.getElementById('selectAllProjects').checked = false;
    }
    updateBulkActionBar();
}

function deselectAllProjects() {
    document.getElementById('selectAllProjects').checked = false;
    document.querySelectorAll('.data-table .cb-custom').forEach(cb => cb.checked = false);
    selectedProjects.clear();
    updateBulkActionBar();
}

function promptBulkDelete() {
    const count = selectedProjects.size;
    if (count === 0) return;
    
    const confirmModal = document.getElementById('confirmDeleteModal');
    document.getElementById('cdmProjectName').innerHTML = `<strong>${count} bản ghi đã chọn</strong>`;
    confirmModal.classList.add('active');
}

function toggleFilterDropdown() {
    document.getElementById('filterDropdown').classList.toggle('open');
}
function setFilter(val, label, el) {
    document.getElementById('filterLabel').textContent = label;
    document.querySelectorAll('.pj-dropdown-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('filterDropdown').classList.remove('open');
    
    // Reset selection and page
    deselectAllProjects();
    currentPage = 1;
    
    // Update global PROJECTS filter state if needed, but here we just filter the DOM or re-init
    // For consistency with other modules, we'll re-run initProjectsTable with filtered data
    initProjectsTable();
}

document.addEventListener('DOMContentLoaded', () => {
    // Initial data setup
    filteredProjects = [...(typeof PROJECTS !== 'undefined' ? PROJECTS : [])];
    initProjectsTable();
});

function initProjectsTable() {
    const tbody = document.getElementById('projectTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    
    // Get current filter and search
    const statusFilter = document.querySelector('.pj-dropdown-item.active').dataset.value || '';
    const searchVal = document.getElementById('p_search_v2').value.toLowerCase();
    
    // Apply filters
    filteredProjects = PROJECTS.filter(p => {
        const matchesStatus = !statusFilter || (p.status || '').toLowerCase() === statusFilter;
        const matchesSearch = !searchVal || 
            (p.name || '').toLowerCase().includes(searchVal) || 
            (p.customer || '').toLowerCase().includes(searchVal);
        return matchesStatus && matchesSearch;
    });

    const totalItems = filteredProjects.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
    // Handle empty state
    if (totalItems === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center" style="padding: 40px; color: #64748b;">Không có dữ liệu phù hợp</td></tr>';
        document.getElementById('paginationRow').style.display = 'none';
        return;
    }

    // Slicing for pagination
    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = Math.min(startIdx + itemsPerPage, totalItems);
    const pageData = filteredProjects.slice(startIdx, endIdx);

    pageData.forEach((p) => {
        try {
            const tr = document.createElement('tr');
            populateRow(tr, p);
            tr.onclick = (e) => {
                if (!e.target.closest('input') && !e.target.closest('button')) {
                    openProjectDetail(tr);
                }
            };
            tbody.appendChild(tr);
        } catch (err) {
            console.error('Error rendering project row:', err, p);
        }
    });

    renderPagination(totalItems, totalPages);
}

function renderPagination(totalItems, totalPages) {
    const row = document.getElementById('paginationRow');
    const countText = document.getElementById('paginationCount');
    const buttonsContainer = document.getElementById('paginationButtons');

    if (totalItems <= itemsPerPage) {
        row.style.display = 'none';
        return;
    }

    row.style.display = 'flex';
    const start = (currentPage - 1) * itemsPerPage + 1;
    const end = Math.min(currentPage * itemsPerPage, totalItems);
    
    countText.innerHTML = `Hiển thị <b>${start}</b> đến <b>${end}</b> trong tổng số <b>${totalItems}</b> kết quả`;

    let html = `
        <button class="pg-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">
            <i class="ph ph-caret-left"></i>
        </button>`;

    for (let i = 1; i <= totalPages; i++) {
        html += `<button class="pg-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
    }

    html += `
        <button class="pg-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">
            <i class="ph ph-caret-right"></i>
        </button>`;
    
    buttonsContainer.innerHTML = html;
}

function changePage(page) {
    currentPage = page;
    initProjectsTable();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.pj-filter-wrapper')) {
        const dd = document.getElementById('filterDropdown');
        if (dd) dd.classList.remove('open');
    }
});
document.getElementById('p_search_v2').addEventListener('input', function() {
    currentPage = 1;
    initProjectsTable();
});

let currentActionMode = 'add';
let currentRowToEdit = null;

function openAddProjectModal() {
    currentActionMode = 'add';
    currentRowToEdit = null;
    resetProjectForm();
    
    document.getElementById('modalTitle').textContent = 'Thêm Project Mới';
    document.getElementById('modalBrandIcon').className = 'ph-fill ph-folders';
    document.querySelector('#addProjectModal .modal-btn-submit').textContent = 'Thêm Mới';
    
    document.getElementById('addProjectModal').classList.add('active');
    document.querySelectorAll('.pj-filter-btn').forEach(b => b.classList.remove('active'));
    document.body.style.overflow = 'hidden';
}

function openEditProjectModal(tr) {
    currentActionMode = 'edit';
    currentRowToEdit = tr;
    
    // Extract data
    const name = tr.querySelector('.cell-main').textContent.trim();
    const customer = tr.querySelector('.provider-info span').textContent.trim();
    const status = tr.getAttribute('data-status');
    const desc = tr.getAttribute('data-desc') || '';
    const phone = tr.getAttribute('data-phone') || '';
    const adminUrl = tr.getAttribute('data-admin-url') || '';
    const adminUser = tr.getAttribute('data-admin-user') || '';
    const adminPass = tr.getAttribute('data-admin-pass') || '';
    const value = tr.getAttribute('data-value') || 0;
    
    // Get raw date (DD/MM/YYYY -> YYYY-MM-DD)
    const dateStr = tr.querySelector('.date-info').textContent.trim();
    const [d, m, y] = dateStr.split('/');
    const rawDate = `${y}-${m}-${d}`;

    // Fill form
    document.getElementById('mProjectName').value = name;
    document.getElementById('mProjectStatus').value = status;
    document.getElementById('mProjectDesc').value = desc;
    document.getElementById('mProjectDate').value = rawDate;
    document.getElementById('mCustomerName').value = customer;
    document.getElementById('mCustomerPhone').value = phone;
    document.getElementById('mAdminLink').value = adminUrl;
    document.getElementById('mAdminUser').value = adminUser;
    document.getElementById('adminPassword').value = adminPass;
    document.getElementById('projectValue').value = value;
    
    // Update Custom Select UI
    const customSelect = document.getElementById('mProjectStatusSelect');
    const option = customSelect.querySelector(`.pj-dropdown-item[data-value="${status}"]`);
    if (option) {
        const trigger = customSelect.querySelector('.pj-modal-select-trigger');
        const triggerLabel = trigger.querySelector('span');
        const triggerIcon = trigger.querySelector('i:first-child');
        
        if (triggerLabel) triggerLabel.textContent = option.querySelector('span').textContent.trim();
        
        customSelect.querySelectorAll('.pj-dropdown-item').forEach(i => i.classList.remove('active'));
        option.classList.add('active');
    }
    
    updateProjectValueDisplay(document.getElementById('projectValue'));

    // Update Modal UI
    document.getElementById('modalTitle').textContent = 'Chỉnh Sửa Project';
    document.getElementById('modalBrandIcon').className = 'ph-fill ph-pencil-simple';
    document.querySelector('#addProjectModal .modal-btn-submit').textContent = 'Cập Nhật';
    
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
    closeConfirmDeleteBtn();
    if (rowToDelete) {
        const id = rowToDelete.getAttribute('data-id');
        const name = document.getElementById('cdmProjectName').textContent;
        
        showActionToast('Đang xóa dự án...', `Đã xóa dự án "${name}"`, async () => {
            try {
                const response = await fetch('/projects/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                const result = await response.json();
                if (result.status === 'success' || result.success) {
                    showToast('Xóa dự án thành công', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('Lỗi khi xóa: ' + (result.message || 'Không xác định'), 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Có lỗi xảy ra khi kết nối với máy chủ.', 'error');
            }
        });
    } else if (selectedProjects.size > 0) {
        const ids = Array.from(selectedProjects);
        showActionToast('Đang xóa các dự án...', `Đã xóa ${ids.length} dự án`, async () => {
            try {
                const response = await fetch('/projects/bulk-delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ids: ids })
                });
                const result = await response.json();
                if (result.status === 'success' || result.success) {
                    ids.forEach(id => {
                        const tr = document.querySelector(`.data-table tbody tr[data-id="${id}"]`);
                        if (tr) tr.remove();
                    });
                    selectedProjects.clear();
                    selectedProjects.clear();
                    updateBulkActionBar();
                    document.getElementById('selectAllProjects').checked = false;
                    showToast(`Đã xóa các dự án đã chọn`, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('Lỗi khi xóa: ' + (result.message || 'Không xác định'), 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Có lỗi xảy ra khi kết nối với máy chủ.', 'error');
            }
        });
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
            
            const menuWidth = 160;
            let leftPos = rect.right + window.scrollX - menuWidth - 4;
            menu.style.left = Math.max(4, leftPos) + 'px';
            
            menu.classList.add('open');
            menu._trigger = btn;
        }
        e.stopPropagation();
        return;
    }

    if (e.target.closest('.ram-item')) {
        const item = e.target.closest('.ram-item');
        const tr = menu._trigger ? menu._trigger.closest('tr') : null;
        menu.classList.remove('open');

        if (tr) {
            if (item.classList.contains('ram-view')) {
                openProjectDetail(tr);
            } else if (item.classList.contains('ram-edit')) {
                openEditProjectModal(tr);
            } else if (item.classList.contains('ram-delete')) {
                rowToDelete = tr;
                const name = tr.querySelector('.cell-main').textContent.trim();
                document.getElementById('cdmProjectName').textContent = name;
                document.getElementById('confirmDeleteModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }
        return;
    }

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
    if (currentActionMode === 'add') {
        addProject();
    } else {
        updateProject();
    }
}

async function addProject() {
    const data = getFormData();
    if (!data) return;

    showToast('Đang thêm dự án...');
    try {
        const response = await fetch('/projects/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.status === 'success' || result.success) {
            showToast('Thêm dự án thành công!', 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            showToast('Lỗi khi thêm: ' + (result.message || 'Không xác định'), 'error');
        }
    } catch (err) {
        console.error(err);
        showToast('Có lỗi xảy ra khi kết nối với máy chủ.', 'error');
    }
}

async function updateProject() {
    const data = getFormData();
    if (!data || !currentRowToEdit) return;
    
    // Thêm ID vào data để backend biết là cập nhật
    data.id = currentRowToEdit.getAttribute('data-id');

    showToast('Đang cập nhật dự án...');
    try {
        const response = await fetch('/projects/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.status === 'success' || result.success) {
            showToast('Cập nhật thành công!', 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            showToast('Lỗi khi cập nhật: ' + (result.message || 'Không xác định'), 'error');
        }
    } catch (err) {
        console.error(err);
        showToast('Có lỗi xảy ra khi kết nối với máy chủ.', 'error');
    }
}

function getFormData() {
    const name = document.getElementById('mProjectName').value.trim();
    const status = document.getElementById('mProjectStatus').value;
    const desc = document.getElementById('mProjectDesc').value.trim();
    const date = document.getElementById('mProjectDate').value;
    const customer = document.getElementById('mCustomerName').value.trim();
    const phone = document.getElementById('mCustomerPhone').value.trim();
    const adminUrl = document.getElementById('mAdminLink').value.trim();
    const adminUser = document.getElementById('mAdminUser').value.trim();
    const adminPass = document.getElementById('adminPassword').value;
    const value = parseInt(document.getElementById('projectValue').value) || 0;

    clearErrors();
    if (!name) { showToast('Vui lòng nhập tên dự án!', 'error'); markError('mProjectName'); return null; }
    if (!date) { showToast('Vui lòng chọn ngày tạo!', 'error'); markError('mProjectDate'); return null; }
    if (!customer) { showToast('Vui lòng nhập tên khách hàng!', 'error'); markError('mCustomerName'); return null; }

    return { name, link: adminUrl, status, desc, date, customer, phone, adminUrl, adminUser, adminPass, value };
}

function populateRow(row, data) {
    const statusInfo = {
        'planning': { cls: 'planning', icon: 'ph-calendar-blank', label: 'Lên Kế Hoạch' },
        'doing': { cls: 'doing', icon: 'ph-clock', label: 'Đang Thực Hiện' },
        'testing': { cls: 'testing', icon: 'ph-circle-dashed', label: 'Chờ Nghiệm Thu' },
        'done': { cls: 'done', icon: 'ph-check-circle', label: 'Hoàn Thành' },
        'paused': { cls: 'paused', icon: 'ph-pause-circle', label: 'Tạm Dừng' }
    };
    
    // Robust status matching
    const statusKey = (data.status || '').toLowerCase().trim();
    const s = statusInfo[statusKey] || { 
        cls: 'doing', 
        icon: 'ph-clock', 
        label: data.status || 'Đang Thực Hiện' 
    };
    
    // Debug label if it's mismatching
    if (statusKey === 'planning' && s.label !== 'Lên Kế Hoạch') {
        console.warn('Status mapping mismatch for planning:', s);
    }
    
    let formattedDate = 'N/A';
    if (data.date) {
        const parts = data.date.split('-');
        if (parts.length === 3) {
            formattedDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
    }

    let formattedVal = '0';
    const valObj = parseFloat(data.value) || 0;
    if (valObj >= 1000000) {
        formattedVal = (valObj / 1000000).toFixed(1).replace('.0', '') + 'M';
    } else if (valObj >= 1000) {
        formattedVal = (valObj / 1000).toFixed(0) + 'K';
    } else {
        formattedVal = valObj.toLocaleString('vi-VN');
    }

    // Store attributes
    row.setAttribute('data-id', data.id || '');
    row.setAttribute('data-status', data.status);
    row.setAttribute('data-desc', data.desc);
    row.setAttribute('data-phone', data.phone);
    row.setAttribute('data-admin-url', data.adminUrl);
    row.setAttribute('data-admin-user', data.adminUser);
    row.setAttribute('data-admin-pass', data.adminPass);
    row.setAttribute('data-value', data.value);

    row.innerHTML = `
        <td><input type="checkbox" class="cb-custom" onclick="handleRowSelection(this)"></td>
        <td>
            <div class="cell-main">${data.name}</div>
            <div class="cell-sub"><i class="ph ph-link"></i> ${data.adminUrl || 'N/A'}</div>
        </td>
        <td>
            <div class="provider-info">
                <i class="ph ph-user-circle color-gray"></i>
                <span>${data.customer}</span>
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
}

function openProjectDetail(tr) {
    currentRowToEdit = tr;
    const name = tr.querySelector('.cell-main').textContent.trim();
    const customer = tr.querySelector('.provider-info span').textContent.trim();
    const date = tr.querySelector('.date-info').textContent.trim();
    const statusBadge = tr.querySelector('.status-badge');
    const value = parseInt(tr.getAttribute('data-value')) || 0;
    
    const desc = tr.getAttribute('data-desc') || 'Không có mô tả.';
    const phone = tr.getAttribute('data-phone') || 'N/A';
    const adminUrl = tr.getAttribute('data-admin-url') || 'N/A';
    const adminUser = tr.getAttribute('data-admin-user') || 'N/A';
    const adminPass = tr.getAttribute('data-admin-pass') || 'N/A';

    // Populate
    document.getElementById('dpName').textContent = name;
    document.getElementById('dpCustomer').textContent = customer;
    document.getElementById('dpDate').textContent = date;
    document.getElementById('dpValue').textContent = value.toLocaleString('vi-VN') + ' VNĐ';
    document.getElementById('dpDesc').textContent = desc;
    document.getElementById('dpPhone').textContent = phone;
    document.getElementById('dpAdminUrl').textContent = adminUrl;
    document.getElementById('dpAdminUser').textContent = adminUser;
    document.getElementById('dpAdminPass').textContent = adminPass;

    // Status
    const statusContainer = document.getElementById('dpStatusBadge');
    statusContainer.innerHTML = `<span class="${statusBadge.className}">${statusBadge.innerHTML}</span>`;

    document.getElementById('detailProjectModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeDetailProjectModalBtn() {
    document.getElementById('detailProjectModal').classList.remove('active');
    document.body.style.overflow = '';
}

function closeDetailProjectModal(e) {
    if (e.target === document.getElementById('detailProjectModal')) closeDetailProjectModalBtn();
}

function copyTextFrom(elementId) {
    const text = document.getElementById(elementId).textContent;
    if (text === 'N/A') return;
    
    navigator.clipboard.writeText(text).then(() => {
        showActionToast(null, 'Đã sao chép vào bộ nhớ tạm!', null, true);
    });
}

function showActionToast(loadingMsg, successMsg, callback, immediateSuccess = false) {
    const toast = document.getElementById('deleteToast');
    const msg = document.getElementById('dtMessage');
    const spinner = document.getElementById('dtSpinner');
    const successIcon = document.getElementById('dtSuccessIcon');
    
    if (immediateSuccess) {
        spinner.style.display = 'none';
        successIcon.style.display = 'block';
        msg.textContent = successMsg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2000);
        return;
    }

    // Reset state
    spinner.style.display = 'block';
    successIcon.style.display = 'none';
    msg.textContent = loadingMsg;
    toast.classList.add('show');
    
    // Simulate delay
    setTimeout(() => {
        if (callback) callback();
        
        // Success state
        spinner.style.display = 'none';
        successIcon.style.display = 'block';
        msg.textContent = successMsg;
        
        // Hide toast
        setTimeout(() => {
            toast.classList.remove('show');
        }, 2000);
        
    }, 800);
}

function openEditProjectFromDetail() {
    closeDetailProjectModalBtn();
    if (currentRowToEdit) {
        openEditProjectModal(currentRowToEdit);
    }
}

function deleteProjectFromDetail() {
    closeDetailProjectModalBtn();
    rowToDelete = currentRowToEdit;
    const name = rowToDelete.querySelector('.cell-main').textContent.trim();
    document.getElementById('cdmProjectName').textContent = name;
    document.getElementById('confirmDeleteModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function resetProjectForm() {
    document.getElementById('mProjectName').value = '';
    // Reset Custom Select
    const customSelect = document.getElementById('mProjectStatusSelect');
    if (customSelect) {
        const trigger = customSelect.querySelector('.pj-modal-select-trigger');
        const defaultOption = customSelect.querySelector('.pj-dropdown-item[data-value="planning"]');
        
        if (trigger && defaultOption) {
            trigger.querySelector('span').textContent = defaultOption.textContent.trim();
            const icon = defaultOption.querySelector('i');
            const triggerIcon = trigger.querySelector('i:first-child');
            if (icon && triggerIcon) triggerIcon.className = icon.className;
        }

        // Explicitly set hidden input and remove active from others
        const hiddenInput = document.getElementById('mProjectStatus');
        if (hiddenInput) {
            hiddenInput.value = 'planning';
            console.log('resetProjectForm: Set #mProjectStatus to "planning"');
        }
        
        customSelect.querySelectorAll('.pj-dropdown-item').forEach(opt => {
            opt.classList.toggle('active', opt.dataset.value === 'planning');
        });
    }

    document.getElementById('projectValueDisplay').textContent = '0 VNĐ';
    
    // Reset password field type
    const passInput = document.getElementById('adminPassword');
    passInput.type = 'password';
    const icon = passInput.nextElementSibling.querySelector('i');
    if (icon) {
        icon.classList.remove('ph-eye-slash');
        icon.classList.add('ph-eye');
    }
}

function updateProjectValueDisplay(input) {
    const val = parseInt(input.value) || 0;
    document.getElementById('projectValueDisplay').textContent = val.toLocaleString('vi-VN') + ' VNĐ';
}

function togglePasswordVisibility(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('ph-eye');
        icon.classList.add('ph-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('ph-eye-slash');
        icon.classList.add('ph-eye');
    }
}
</script>
</body>
</html>
