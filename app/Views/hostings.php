<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostings - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        // Data injected from PHP Models
        const PHP_DATA = {
            projects: <?php echo json_encode($projects ?? []); ?>,
            hostings: <?php echo json_encode($hostings ?? []); ?>,
            hosting_renewals: <?php echo json_encode($hosting_renewals ?? []); ?>
        };
    </script>
    <script src="/js/shared-data.js"></script>
    <style>
        /* Renewal History Styles */
        .renewal-history-section { border-top: 1px dashed var(--border-color); padding-top: 20px; }
        .renewal-history-list { display: flex; flex-direction: column; gap: 10px; margin-top: 8px; max-height: 200px; overflow-y: auto; padding-right: 4px; }
        .renewal-item { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px; display: flex; justify-content: space-between; align-items: center; transition: all 0.2s; }
        .renewal-item:hover { border-color: var(--primary-color); background: #fff; }
        .ri-left { display: flex; flex-direction: column; gap: 2px; }
        .ri-date { font-size: 12px; font-weight: 700; color: var(--text-main); }
        .ri-period { font-size: 11px; color: var(--text-muted); }
        .ri-right { text-align: right; }
        .ri-amount { font-size: 13px; font-weight: 700; color: #10b981; }
        .ri-notes { font-size: 10px; color: var(--text-muted); margin-top: 2px; font-style: italic; }
        
        .btn-renew-full { flex: 1; background: #f0f9ff; color: #0ea5e9; border: 1px solid #bae6fd; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; }
        .btn-renew-full:hover { background: #0ea5e9; color: #fff; border-color: #0ea5e9; }
        
        .renewal-history-list::-webkit-scrollbar { width: 4px; }
        .renewal-history-list::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
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
            $activePage = 'hostings';
            include APP_DIR . '/Views/partials/sidebar.php'; 
        ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <?php 
                $pageTitle = 'Danh Sách Hosting';
                $pageSubtitle = 'Quản lý tất cả hosting của bạn';
                include APP_DIR . '/Views/partials/header.php'; 
            ?>
            
            <!-- Bulk Action Bar -->
            <div class="bulk-action-bar" id="bulkActionBar">
                <div class="bab-left">
                    <i class="ph-fill ph-check-circle"></i>
                    <span id="selectedCountText">Đã chọn 0 hosting</span>
                </div>
                <div class="bab-right">
                    <button class="btn-deselect" onclick="deselectAllHostings()">Bỏ chọn</button>
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
                        <input type="text" id="h_search_v2" name="q_search_field" class="pj-search-input" placeholder="Tìm kiếm theo tên, domain, nhà cung cấp..." autocomplete="chrome-off">
                    </div>
                    <div class="pj-toolbar-right">
                        <div class="pj-filter-wrapper">
                            <button class="pj-filter-btn" onclick="toggleHostingFilter()">
                                <i class="ph ph-funnel-simple"></i>
                                <span id="hostingFilterLabel">Lọc bởi trạng thái</span>
                                <i class="ph ph-caret-down"></i>
                            </button>
                            <div class="pj-dropdown" id="hostingFilterDropdown">
                                <div class="pj-dropdown-item active" onclick="setHostingFilter('', 'Lọc bởi trạng thái', this)">Tất cả</div>
                                <div class="pj-dropdown-item" onclick="setHostingFilter('success', 'Hoạt động', this)">Hoạt động</div>
                                <div class="pj-dropdown-item" onclick="setHostingFilter('warning', 'Sắp hết hạn', this)">Sắp hết hạn</div>
                            </div>
                        </div>
                        <button class="pj-add-btn" onclick="openHostingModal()">
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
                                <th width="40"><input type="checkbox" class="cb-custom" id="selectAllHostings" onclick="toggleSelectAll(this)"></th>
                                <th>TÊN HOSTING</th>
                                <th>DOMAIN</th>
                                <th>NHÀ CUNG CẤP</th>
                                <th>NGÀY HẾT HẠN</th>
                                <th>TRẠNG THÁI</th>
                                <th width="80" class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody id="hostingTableBody">
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

            </div> <!-- end content-body -->

        </main>
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

<script>
let currentActionMode = 'add';
let currentRowToEdit = null;
let currentSearchTerm = '';
let currentStatusFilter = '';
let selectedHostings = new Set();
let currentPage = 1;
const itemsPerPage = 10;

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
    const count = selectedHostings.size;

    if (count > 0) {
        countText.textContent = `Đã chọn ${count} hosting`;
        deleteBtnText.textContent = `Xóa ${count}`;
        bar.classList.add('show');
    } else {
        bar.classList.remove('show');
    }
}

function toggleSelectAll(masterCb) {
    const checkboxes = document.querySelectorAll('.data-table tbody .cb-custom');
    selectedHostings.clear();
    
    checkboxes.forEach(cb => {
        const row = cb.closest('tr');
        if (row.style.display !== 'none') {
            cb.checked = masterCb.checked;
            if (cb.checked) {
                const id = row.getAttribute('data-id');
                if (id) selectedHostings.add(id);
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
        if (id) selectedHostings.add(id);
    } else {
        if (id) selectedHostings.delete(id);
        document.getElementById('selectAllHostings').checked = false;
    }
    updateBulkActionBar();
}

function deselectAllHostings() {
    document.getElementById('selectAllHostings').checked = false;
    document.querySelectorAll('.data-table .cb-custom').forEach(cb => cb.checked = false);
    selectedHostings.clear();
    updateBulkActionBar();
}

function promptBulkDelete() {
    const count = selectedHostings.size;
    if (count === 0) return;
    
    const confirmModal = document.getElementById('confirmDeleteModal');
    document.getElementById('cdmHostingName').innerHTML = `<strong>${count} bản ghi đã chọn</strong>`;
    confirmModal.classList.add('active');
}

function toggleHostingFilter() {
    document.getElementById('hostingFilterDropdown').classList.toggle('open');
}

document.addEventListener('DOMContentLoaded', () => {
    initHostingsTable();
    
    // Search input event
    document.getElementById('hostingSearchInput').addEventListener('input', (e) => {
        currentSearchTerm = e.target.value.toLowerCase().trim();
        applyFilters();
    });
});

function initHostingsTable() {
    const tbody = document.getElementById('hostingTableBody');
    tbody.innerHTML = '';
    
    HOSTINGS.forEach(h => {
        const row = document.createElement('tr');
        row.setAttribute('data-id', h.id);
        row.setAttribute('data-price', h.price || 0);
        row.setAttribute('data-reg-date', h.regDate || '');
        row.setAttribute('data-notes', h.notes || '');
        const status = getStatusFromDate(h.expDate);
        row.innerHTML = generateRowHTML(h.name, h.domain, h.provider, h.expDate, status, h.regDate);
        tbody.appendChild(row);
    });
}

function applyFilters() {
    const rows = Array.from(document.querySelectorAll('.data-table tbody tr'));
    let filteredRows = [];

    rows.forEach(row => {
        let showSearch = true;
        let showStatus = true;
        
        if (currentSearchTerm) {
            const name = row.querySelector('.cell-main').textContent.toLowerCase();
            const domain = row.querySelector('.domain-info span').textContent.toLowerCase();
            if (!name.includes(currentSearchTerm) && !domain.includes(currentSearchTerm)) {
                showSearch = false;
            }
        }
        
        if (currentStatusFilter) {
            const badge = row.querySelector('.status-badge');
            if (!badge || !badge.classList.contains(currentStatusFilter)) {
                showStatus = false;
            }
        }
        
        if (showSearch && showStatus) {
            filteredRows.push(row);
        } else {
            row.style.display = 'none';
        }
    });

    const totalItems = filteredRows.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    // Reset to page 1 if current page is out of bounds
    if (currentPage > totalPages && totalPages > 0) currentPage = 1;

    // Handle pagination visibility
    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = startIdx + itemsPerPage;

    filteredRows.forEach((row, idx) => {
        if (idx >= startIdx && idx < endIdx) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
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
    const rows = Array.from(document.querySelectorAll('.data-table tbody tr'));
    const totalItems = rows.filter(r => {
         // Re-run basic filter check to know total pages correctly
         let showS = true, showSt = true;
         if (currentSearchTerm) {
            const name = r.querySelector('.cell-main').textContent.toLowerCase();
            const domain = r.querySelector('.domain-info span').textContent.toLowerCase();
            if (!name.includes(currentSearchTerm) && !domain.includes(currentSearchTerm)) showS = false;
         }
         if (currentStatusFilter) {
            const badge = r.querySelector('.status-badge');
            if (!badge || !badge.classList.contains(currentStatusFilter)) showSt = false;
         }
         return showS && showSt;
    }).length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    if (page < 1 || page > totalPages) return;
    currentPage = page;
    applyFilters();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Initial call
window.addEventListener('DOMContentLoaded', applyFilters);

function setHostingFilter(val, label, el) {
    document.getElementById('hostingFilterLabel').textContent = label;
    document.querySelectorAll('#hostingFilterDropdown .pj-dropdown-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('hostingFilterDropdown').classList.remove('open');
    
    currentStatusFilter = val;
    applyFilters();
}

document.getElementById('h_search_v2').addEventListener('input', function(e) {
    currentSearchTerm = e.target.value.toLowerCase().trim();
    applyFilters();
});

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
            menu.style.left = (rect.right  + window.scrollX - menu.offsetWidth - 4) + 'px';
            // Adjust if overflows right
            const menuRight = rect.right + window.scrollX;
            menu.style.left = Math.max(4, menuRight - 160) + 'px';
            menu.classList.add('open');
            menu._trigger = btn;
        }
        e.stopPropagation();
        return;
    }

    // Click on "Xem Chi Tiết"
    if (e.target.closest('.ram-view')) {
        menu.classList.remove('open');
        if (menu._trigger) {
            const tr = menu._trigger.closest('tr');
            currentRowToEdit = tr;
            
            const name = tr.querySelector('.cell-main').textContent.trim();
            const domain = tr.querySelector('.domain-info span').textContent.trim();
            const provider = tr.querySelector('.provider-info span').textContent.trim();
            const expDate = tr.querySelector('.date-info').textContent.trim();
            const daysLeftEl = tr.querySelector('.date-sub');
            const daysLeft = daysLeftEl ? daysLeftEl.textContent.trim() : 'Đã hết hạn';
            const statusBadgeEl = tr.querySelector('.status-badge');
            
            const price = tr.getAttribute('data-price') || 0;
            const regDate = tr.getAttribute('data-reg-date') || '';
            const notes = tr.getAttribute('data-notes') || '';
            
            // Populate Detail Modal
            const sBadge = document.getElementById('dModalStatus');
            sBadge.className = statusBadgeEl.className;
            sBadge.innerHTML = statusBadgeEl.innerHTML;

            document.getElementById('dModalName').textContent = name;
            document.getElementById('dModalDomain').innerHTML = `<i class="ph ph-globe color-gray"></i> ${domain}`;
            document.getElementById('dModalProvider').innerHTML = `<i class="ph ph-hard-drives color-gray"></i> ${provider}`;
            document.getElementById('dModalExpDate').textContent = expDate;
            document.getElementById('dModalPrice').textContent = formatVNDFull(parseFloat(price));
            document.getElementById('dModalRegDate').textContent = formatDateVN(regDate);
            document.getElementById('dModalUsage').textContent = calculateUsageTime(regDate);
            document.getElementById('dModalNotes').textContent = notes || 'Không có ghi chú.';
            
            // Populate Renewal History
            renderRenewalHistory(tr.getAttribute('data-id'));

            const expDateEl = document.getElementById('dModalExpDate');
            expDateEl.className = 'dgc-val ' + (statusBadgeEl.classList.contains('warning') ? 'warning-text' : (statusBadgeEl.classList.contains('expired') ? 'danger-text' : 'success-text'));
            
            const daysLeftContainer = document.getElementById('dModalDaysLeft');
            daysLeftContainer.className = 'dgc-val ' + (statusBadgeEl.classList.contains('warning') ? 'warning-text' : (statusBadgeEl.classList.contains('expired') ? 'danger-text' : 'success-text'));
            daysLeftContainer.innerHTML = daysLeft.includes('Còn') ? `<i class="ph ph-clock"></i> ${daysLeft}` : daysLeft;
            
            document.getElementById('detailModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        return;
    }

    // Click on "Chỉnh Sửa"
    if (e.target.closest('.ram-edit')) {
        menu.classList.remove('open');
        if (menu._trigger) {
            const tr = menu._trigger.closest('tr');
            openEditModal(tr);
        }
        return;
    }

    // Click on "Xóa"
    if (e.target.closest('.ram-delete')) {
        menu.classList.remove('open');
        if (menu._trigger) {
            const tr = menu._trigger.closest('tr');
            promptDelete(tr);
        }
        return;
    }

    // Close row action menu on outside click
    if (!e.target.closest('#rowActionMenu')) {
        menu.classList.remove('open');
    }

    // Close filter dropdown on outside click
    if (!e.target.closest('.pj-filter-wrapper')) {
        const dd = document.getElementById('hostingFilterDropdown');
        if (dd) dd.classList.remove('open');
    }
});
</script>

<!-- Modal Thêm Hosting Mới -->
<div class="modal-overlay" id="hostingModal" onclick="closeHostingModal(event)">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title-wrap">
                <div class="modal-icon-brand">
                    <i id="modalBrandIcon" class="ph-fill ph-hard-drives"></i>
                </div>
                <h3 class="modal-title" id="modalTitle">Thêm Hosting Mới</h3>
            </div>
            <button class="modal-close" onclick="closeHostingModalBtn()"><i class="ph ph-x"></i></button>
        </div>
        <div class="modal-body">
            <div style="display: none;">
                <input type="text" name="mUsername">
                <input type="password" name="mPassword">
            </div>
            <div class="modal-field full">
                <label class="modal-label">Tên Hosting <span class="req">*</span></label>
                <input type="text" class="modal-input" id="mHostingName" placeholder="VD: Website Chính">
            </div>
            <div class="modal-row">
                <div class="modal-field">
                    <label class="modal-label">Tên Miền <span class="req">*</span></label>
                    <input type="text" class="modal-input" id="mDomain" placeholder="VD: example.com">
                </div>
                <div class="modal-field">
                    <label class="modal-label">Nhà Cung Cấp <span class="req">*</span></label>
                    <input type="text" class="modal-input" id="mProvider" placeholder="iNet" value="iNet">
                </div>
            </div>
            <div class="modal-row">
                <div class="modal-field">
                    <label class="modal-label">Ngày Đăng Ký <span class="req">*</span></label>
                    <input type="date" class="modal-input" id="mRegDate">
                </div>
                <div class="modal-field">
                    <label class="modal-label">Ngày Hết Hạn <span class="req">*</span></label>
                    <input type="date" class="modal-input" id="mExpDate">
                </div>
            </div>
            <div class="modal-field full">
                <label class="modal-label">Giá (VNĐ) <span class="req">*</span></label>
                <input type="number" class="modal-input" id="hostingPrice" value="1100000" oninput="formatPrice()">
                <div class="modal-price-hint" id="priceHint">1.100.000 VNĐ</div>
            </div>
            <div class="modal-field full">
                <label class="modal-label">Ghi Chú</label>
                <textarea class="modal-textarea" id="mHostingNotes" placeholder="Thêm ghi chú về hosting này..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-cancel" onclick="closeHostingModalBtn()">Hủy</button>
            <button class="modal-btn-submit" id="modalSubmitBtn" onclick="submitHostingForm()"><i class="ph ph-plus"></i> Thêm Mới</button>
        </div>
    </div>
</div>

<!-- Modal Chi Tiết Hosting -->
<div class="modal-overlay" id="detailModal" onclick="closeDetailModal(event)">
    <div class="modal-box" style="max-width: 520px;">
        <div class="modal-header-gradient">
            <h3 class="modal-title-light"><i class="ph ph-hard-drives"></i> Chi Tiết Hosting</h3>
            <button class="modal-close-light" onclick="closeDetailModalBtn()"><i class="ph ph-x"></i></button>
        </div>
        <div class="modal-body">
            <div class="detail-top-row">
                <span class="status-badge" id="dModalStatus"></span>
                <div class="detail-price"><i class="ph ph-money"></i> <span id="dModalPrice">1.100.000 VNĐ</span></div>
            </div>
            
            <div class="detail-group">
                <span class="detail-label">Tên Hosting</span>
                <span class="detail-val" id="dModalName">Photoeditor 24h</span>
            </div>

            <div class="detail-info-grid">
                <div class="detail-group">
                    <span class="detail-label">Domain</span>
                    <span class="detail-val-norm" id="dModalDomain"><i class="ph ph-globe color-gray"></i> https://photoeditor24h.com/</span>
                </div>
                <div class="detail-group">
                    <span class="detail-label">Nhà Cung Cấp</span>
                    <span class="detail-val-norm" id="dModalProvider"><i class="ph ph-hard-drives color-gray"></i> iNet</span>
                </div>
            </div>

            <div class="detail-gray-card">
                <div class="dgc-title"><i class="ph ph-calendar-blank"></i> Thông Tin Thời Gian</div>
                
                <div class="dgc-row">
                    <div class="detail-group">
                        <span class="detail-label">Ngày Đăng Ký</span>
                        <span class="dgc-val" id="dModalRegDate">10/04/2025</span>
                    </div>
                    <div class="detail-group" style="text-align: right;">
                        <span class="detail-label" style="text-align: right;">Ngày Hết Hạn</span>
                        <span class="dgc-val orange" style="justify-content: flex-end;" id="dModalExpDate">12/04/2026</span>
                    </div>
                </div>

                <div class="dgc-row">
                    <div class="detail-group">
                        <span class="detail-label">Thời Gian Sử Dụng</span>
                        <span class="dgc-val" id="dModalUsage">1 năm</span>
                    </div>
                    <div class="detail-group" style="text-align: right;">
                        <span class="detail-label" style="text-align: right;">Còn Lại</span>
                        <span class="dgc-val orange" style="justify-content: flex-end;" id="dModalDaysLeft"><i class="ph ph-clock"></i> 17 ngày</span>
                    </div>
                </div>
            </div>

            <div class="detail-group" style="margin-top: 20px;">
                <span class="detail-label">Ghi Chú</span>
                <div class="detail-desc-box" id="dModalNotes" style="min-height: 40px; background: #f8fafc; padding: 12px; border-radius: 8px; font-size: 14px; color: #475569; line-height: 1.6; margin-bottom: 20px;">
                    Không có ghi chú.
                </div>
            </div>

            <!-- Lịch sử gia hạn -->
            <div class="renewal-history-section">
                <div class="dgc-title" style="margin-bottom: 12px;"><i class="ph ph-clock-counter-clockwise"></i> Lịch sử gia hạn</div>
                <div id="renewalHistoryList" class="renewal-history-list">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>
        <div class="detail-footer">
            <button class="btn-renew-full" onclick="openRenewModalFromDetail()"><i class="ph ph-arrows-counter-clockwise"></i> Gia Hạn</button>
            <button class="btn-edit-full" onclick="editFromDetail()"><i class="ph ph-pencil-simple"></i> Chỉnh Sửa</button>
            <button class="btn-delete-outline" onclick="deleteFromDetail()"><i class="ph ph-trash"></i></button>
        </div>
    </div>
</div>

<!-- Modal Xác nhận Xóa -->
<div class="modal-overlay" id="confirmDeleteModal" onclick="closeConfirmDelete(event)">
    <div class="modal-box" style="max-width: 420px; padding: 24px; border-radius: 16px;">
        <button class="modal-close" style="position: absolute; right: 20px; top: 20px;" onclick="closeConfirmDeleteBtn()"><i class="ph ph-x"></i></button>
        
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <div style="color: #ef4444; font-size: 24px; display: flex; align-items: center;"><i class="ph ph-warning"></i></div>
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--text-main);">Xác nhận xóa hosting</h3>
        </div>
        
        <p style="margin: 0 0 24px 0; color: #4b5563; font-size: 14px; line-height: 1.5;">
            Bạn có chắc chắn muốn xóa hosting "<strong id="cdmHostingName"></strong>"?<br> Hành động này không thể hoàn tác.
        </p>
        
        <div style="display: flex; gap: 12px;">
            <button class="modal-btn-cancel" style="flex: 1; justify-content: center; border-radius: 8px; font-weight: 600; padding: 10px;" onclick="closeConfirmDeleteBtn()">Cancel</button>
            <button class="modal-btn-submit" style="flex: 1; background: #fe2c2c; justify-content: center; border-radius: 8px; font-weight: 600; padding: 10px;" onclick="confirmDeleteAction()">OK</button>
        </div>
    </div>
</div>

<!-- Toast Đang Xóa -->
<div class="delete-toast" id="deleteToast">
    <div class="toast-spinner" id="dtSpinner"></div>
    <div id="dtSuccessIcon" style="display:none; color: #10b981; font-size: 20px; display: flex; align-items: center;"><i class="ph-fill ph-check-circle"></i></div>
    <span id="dtMessage">Đang xóa hosting...</span>
</div>

<!-- Modal Gia Hạn Hosting -->
<div class="modal-overlay" id="renewModal" onclick="closeRenewModal(event)">
    <div class="modal-box" style="max-width: 480px;">
        <div class="modal-header">
            <div class="modal-title-wrap">
                <div class="modal-icon-brand" style="background-color: #f0f9ff; color: #0ea5e9;">
                    <i class="ph-fill ph-arrows-counter-clockwise"></i>
                </div>
                <h3 class="modal-title">Gia Hạn Hosting</h3>
            </div>
            <button class="modal-close" onclick="closeRenewModalBtn()"><i class="ph ph-x"></i></button>
        </div>
        <div class="modal-body">
            <div class="detail-group" style="margin-bottom: 20px; background: #f8fafc; padding: 12px; border-radius: 8px;">
                <span class="detail-label">Đang gia hạn cho</span>
                <span class="detail-val" id="rmHostingName" style="font-size: 16px;">Photoeditor 24h</span>
            </div>

            <div class="modal-row">
                <div class="modal-field">
                    <label class="modal-label">Ngày Gia Hạn (Từ) <span class="req">*</span></label>
                    <input type="date" class="modal-input" id="rmRegDate">
                </div>
                <div class="modal-field">
                    <label class="modal-label">Ngày Hết Hạn Mới <span class="req">*</span></label>
                    <input type="date" class="modal-input" id="rmExpDate">
                </div>
            </div>
            <div class="modal-field full">
                <label class="modal-label">Số Tiền Thanh Toán (VNĐ) <span class="req">*</span></label>
                <input type="number" class="modal-input" id="rmPrice" value="1100000" oninput="formatRenewPrice()">
                <div class="modal-price-hint" id="rmPriceHint">1.100.000 VNĐ</div>
            </div>
            <div class="modal-field full">
                <label class="modal-label">Ghi Chú</label>
                <input type="text" class="modal-input" id="rmNotes" placeholder="VD: Khách gia hạn thêm 1 năm">
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-cancel" onclick="closeRenewModalBtn()">Hủy</button>
            <button class="modal-btn-submit" style="background: #0ea5e9;" onclick="submitRenewHosting()"><i class="ph ph-check"></i> Xác Nhận Gia Hạn</button>
        </div>
    </div>
</div>

<script>
function openHostingModal() {
    currentActionMode = 'add';
    document.getElementById('modalTitle').textContent = 'Thêm Hosting Mới';
    document.getElementById('modalBrandIcon').className = 'ph-fill ph-hard-drives';
    const btn = document.getElementById('modalSubmitBtn');
    btn.innerHTML = '<i class="ph ph-plus"></i> Thêm Mới';
    btn.className = 'modal-btn-submit';
    resetModalForm();
    document.getElementById('hostingModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function openEditModal(tr) {
    currentActionMode = 'edit';
    currentRowToEdit = tr;
    
    document.getElementById('modalTitle').textContent = 'Chỉnh Sửa Hosting';
    document.getElementById('modalBrandIcon').className = 'ph-fill ph-pencil-simple';
    const btn = document.getElementById('modalSubmitBtn');
    btn.innerHTML = 'Cập Nhật';
    btn.className = 'modal-btn-submit toggle-edit-blue';
    
    const name = tr.querySelector('.cell-main').textContent.trim();
    const domain = tr.querySelector('.domain-info span').textContent.trim();
    const provider = tr.querySelector('.provider-info span').textContent.trim();
    let expDate = tr.querySelector('.date-info').textContent.trim();
    
    // Date conversions
    const expMatch = expDate.match(/(\d{2})\/(\d{2})\/(\d{4})/);
    let regDate = '';
    if (expMatch) {
       expDate = `${expMatch[3]}-${expMatch[2]}-${expMatch[1]}`;
       regDate = `${parseInt(expMatch[3])-1}-${expMatch[2]}-${expMatch[1]}`;
    }
    
    document.getElementById('mHostingName').value = name;
    document.getElementById('mDomain').value = domain.replace(/^https?:\/\//, '');
    document.getElementById('mProvider').value = provider;
    document.getElementById('mExpDate').value = expDate;
    document.getElementById('mRegDate').value = regDate;
    document.getElementById('hostingPrice').value = tr.getAttribute('data-price') || 0;
    document.getElementById('mHostingNotes').value = tr.getAttribute('data-notes') || '';
    formatPrice();
    
    document.getElementById('hostingModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeHostingModalBtn() {

    document.getElementById('hostingModal').classList.remove('active');
    document.body.style.overflow = '';
}

function editFromDetail() {
    closeDetailModalBtn();
    if (currentRowToEdit) openEditModal(currentRowToEdit);
}

function deleteFromDetail() {
    closeDetailModalBtn();
    if (currentRowToEdit) promptDelete(currentRowToEdit);
}

let rowToDelete = null;
function promptDelete(tr) {
    rowToDelete = tr;
    const name = tr.querySelector('.cell-main').textContent.trim();
    document.getElementById('cdmHostingName').textContent = name;
    document.getElementById('confirmDeleteModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeConfirmDeleteBtn() {
    document.getElementById('confirmDeleteModal').classList.remove('active');
    document.body.style.overflow = '';
}
function closeConfirmDelete(e) {
    if (e.target === document.getElementById('confirmDeleteModal')) closeConfirmDeleteBtn();
}

function confirmDeleteAction() {
    closeConfirmDeleteBtn();
    
    if (selectedHostings.size > 0) {
        const ids = Array.from(selectedHostings);
        showActionToast('Đang xóa các bản ghi...', `Đã xóa ${ids.length} hosting`, async () => {
            try {
                const response = await fetch('/hostings/bulk-delete', {
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
                    selectedHostings.clear();
                    updateBulkActionBar();
                    document.getElementById('selectAllHostings').checked = false;
                    applyFilters();
                } else {
                    showToast('Lỗi khi xóa: ' + (result.message || 'Không xác định'), 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Có lỗi xảy ra khi kết nối với máy chủ.', 'error');
            }
        });
    } else if (rowToDelete) {
        const id = rowToDelete.getAttribute('data-id');
        const hostingName = document.getElementById('cdmHostingName').textContent;
        
        showActionToast('Đang xóa hosting...', `Đã xóa hosting "${hostingName}"`, async () => {
            try {
                const response = await fetch('/hostings/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                const result = await response.json();
                if (result.status === 'success' || result.success) {
                    if (rowToDelete) {
                        rowToDelete.remove();
                        applyFilters();
                    }
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

function showActionToast(loadingMsg, successMsg, callback) {
    const toast = document.getElementById('deleteToast');
    const msg = document.getElementById('dtMessage');
    const spinner = document.getElementById('dtSpinner');
    const successIcon = document.getElementById('dtSuccessIcon');
    
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
        
    }, 1200);
}

function closeHostingModal(e) {
    if (e.target === document.getElementById('hostingModal')) closeHostingModalBtn();
}
function formatPrice() {
    const val = parseInt(document.getElementById('hostingPrice').value) || 0;
    document.getElementById('priceHint').textContent = val.toLocaleString('vi-VN') + ' VNĐ';
}

function getStatusFromDate(expDateStr) {
    if (!expDateStr) return null;
    const today = new Date(); today.setHours(0,0,0,0);
    const exp = new Date(expDateStr); exp.setHours(0,0,0,0);
    const diffDays = Math.ceil((exp - today) / (1000 * 60 * 60 * 24));
    if (diffDays < 0)   return { cls: 'expired',  label: 'Hết hạn',    icon: 'ph-x-circle',       days: null };
    if (diffDays <= 15) return { cls: 'warning',  label: 'Sắp hết hạn', icon: 'ph-warning-circle',  days: diffDays };
    return                     { cls: 'success',  label: 'Hoạt động',   icon: 'ph-check-circle',    days: diffDays };
}

function formatDateVN(dateStr) {
    if (!dateStr) return '';
    const [y, m, d] = dateStr.split('-');
    return `${d}/${m}/${y}`;
}

/**
 * Tính thời gian sử dụng dựa trên ngày đăng ký
 */
function calculateUsageTime(regDateStr) {
    if (!regDateStr) return 'Đang cập nhật';
    
    const regDate = new Date(regDateStr);
    const today = new Date();
    
    let years = today.getFullYear() - regDate.getFullYear();
    let months = today.getMonth() - regDate.getMonth();
    
    if (months < 0 || (months === 0 && today.getDate() < regDate.getDate())) {
        years--;
        months += 12;
    }
    
    if (years >= 1) {
        return `Sử dụng ${years} năm`;
    } else if (months >= 1) {
        return `Sử dụng ${months} tháng`;
    } else {
        const diffDays = Math.floor((today - regDate) / (1000 * 60 * 60 * 24));
        return diffDays > 0 ? `Sử dụng ${diffDays} ngày` : 'Mới đăng ký';
    }
}

function submitHostingForm() {
    if (currentActionMode === 'add') {
        addHosting();
    } else {
        updateHosting();
    }
}

function addHosting() {
    const name     = document.getElementById('mHostingName').value.trim();
    const domain   = document.getElementById('mDomain').value.trim();
    const provider = document.getElementById('mProvider').value.trim();
    const expDate  = document.getElementById('mExpDate').value;
    const regDate  = document.getElementById('mRegDate').value;
    const price    = document.getElementById('hostingPrice').value;
    const notes    = document.getElementById('mHostingNotes').value.trim();

    clearErrors();
    if (!name) { showToast('Vui lòng nhập tên hosting!', 'error'); markError('mHostingName'); return; }
    if (!domain) { showToast('Vui lòng nhập tên miền!', 'error'); markError('mDomain'); return; }
    if (!regDate) { showToast('Vui lòng chọn ngày đăng ký!', 'error'); markError('mRegDate'); return; }
    if (!expDate) { showToast('Vui lòng chọn ngày hết hạn!', 'error'); markError('mExpDate'); return; }
    if (!price) { showToast('Vui lòng nhập giá hosting!', 'error'); markError('hostingPrice'); return; }

    const data = { 
        name, 
        domain, 
        provider, 
        expDate, 
        regDate, 
        price,
        notes,
        usage: '1 năm' // Mặc định
    };

    showActionToast('Đang thêm hosting...', `Đã thêm hosting "${name}"`, async () => {
        try {
            const response = await fetch('/hostings/save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                closeHostingModalBtn();
                window.location.reload();
            } else {
                showToast('Lỗi khi thêm: ' + (result.message || 'Không xác định'), 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Có lỗi xảy ra khi kết nối với máy chủ.', 'error');
        }
    });
}

function updateHosting() {
    if (!currentRowToEdit) return;
    
    const regDate  = document.getElementById('mRegDate').value;
    const price    = document.getElementById('hostingPrice').value;
    const notes    = document.getElementById('mHostingNotes').value.trim();

    clearErrors();
    if (!name) { showToast('Vui lòng nhập tên hosting!', 'error'); markError('mHostingName'); return; }
    if (!domain) { showToast('Vui lòng nhập tên miền!', 'error'); markError('mDomain'); return; }
    if (!regDate) { showToast('Vui lòng chọn ngày đăng ký!', 'error'); markError('mRegDate'); return; }
    if (!expDate) { showToast('Vui lòng chọn ngày hết hạn!', 'error'); markError('mExpDate'); return; }
    if (!price) { showToast('Vui lòng nhập giá hosting!', 'error'); markError('hostingPrice'); return; }

    const data = { 
        id,
        name, 
        domain, 
        provider, 
        expDate, 
        regDate, 
        price,
        notes,
        usage: '1 năm' 
    };

    showActionToast('Đang cập nhật hosting...', `Đã cập nhật hosting "${name}"`, async () => {
        try {
            const response = await fetch('/hostings/save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                const status = getStatusFromDate(expDate);
                currentRowToEdit.innerHTML = generateRowHTML(name, domain, provider, expDate, status, regDate);
                
                // Update data attributes
                currentRowToEdit.setAttribute('data-price', price);
                currentRowToEdit.setAttribute('data-reg-date', regDate);
                currentRowToEdit.setAttribute('data-notes', notes);
                
                closeHostingModalBtn();
                applyFilters();
            } else {
                showToast(result.message || 'Lỗi khi cập nhật', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Có lỗi xảy ra khi kết nối với máy chủ.', 'error');
        }
    });
}

function generateRowHTML(name, domain, provider, expDate, status, regDate) {
    const daysText = status.days !== null
        ? `<div class="date-sub ${status.cls === 'warning' ? 'warning-text' : (status.cls === 'success' ? 'success-text' : '')}"><i class="ph ph-clock"></i> Còn ${status.days} ngày</div>`
        : `<div class="date-sub danger-text"><i class="ph ph-clock"></i> Đã hết hạn</div>`;

    const usageText = calculateUsageTime(regDate);

    return `
        <td><input type="checkbox" class="cb-custom" onclick="handleRowSelection(this)"></td>
        <td>
            <div class="cell-main">${name}</div>
            <div class="cell-sub">${usageText}</div>
        </td>
        <td>
            <div class="domain-info">
                <i class="ph ph-globe color-gray"></i>
                <span>${domain.startsWith('http') ? domain : 'https://' + domain}</span>
            </div>
        </td>
        <td>
            <div class="provider-info">
                <i class="ph ph-hard-drives color-gray"></i>
                <span>${provider}</span>
            </div>
        </td>
        <td>
            <div class="date-info ${status.cls === 'warning' ? 'warning-text' : (status.cls === 'expired' ? 'danger-text' : 'text-main')}">
                <i class="ph ph-calendar-blank"></i> ${formatDateVN(expDate)}
            </div>
            ${daysText}
        </td>

        <td>
            <span class="status-badge ${status.cls}">
                <i class="ph ${status.icon}"></i>
                ${status.label}
            </span>
        </td>
        <td class="text-center"><button class="btn-action"><i class="ph ph-dots-three"></i></button></td>
    `;
}

function resetModalForm() {
    document.getElementById('mHostingName').value = '';
    document.getElementById('mDomain').value = '';
    document.getElementById('mProvider').value = 'iNet';
    document.getElementById('mRegDate').value = '';
    document.getElementById('mExpDate').value = '';
    document.getElementById('hostingPrice').value = '1100000';
    document.getElementById('mHostingNotes').value = '';
    document.getElementById('priceHint').textContent = '1.100.000 VNĐ';
}

function closeDetailModalBtn() {
    document.getElementById('detailModal').classList.remove('active');
    document.body.style.overflow = '';
}
function closeDetailModal(e) {
    if (e.target === document.getElementById('detailModal')) closeDetailModalBtn();
}

/**
 * Hiển thị lịch sử gia hạn
 */
function renderRenewalHistory(hostingId) {
    const list = document.getElementById('renewalHistoryList');
    list.innerHTML = '<div class="text-center py-2"><div class="toast-spinner" style="width:16px;height:16px;border-width:2px;"></div></div>';
    
    // Lấy từ PHP_DATA.hosting_renewals trước (Local Cache)
    const history = PHP_DATA.hosting_renewals.filter(r => r.hosting_id == hostingId).sort((a,b) => new Date(b.reg_date) - new Date(a.reg_date));
    
    if (history.length === 0) {
        list.innerHTML = '<div class="text-center py-4 text-muted" style="font-size: 13px;">Chưa có lịch sử gia hạn</div>';
        return;
    }

    let html = '';
    history.forEach(r => {
        html += `
            <div class="renewal-item">
                <div class="ri-left">
                    <div class="ri-date">${formatDateVN(r.reg_date)}</div>
                    <div class="ri-period">${formatDateVN(r.reg_date)} - ${formatDateVN(r.exp_date)}</div>
                    ${r.notes ? `<div class="ri-notes">${r.notes}</div>` : ''}
                </div>
                <div class="ri-right">
                    <div class="ri-amount">${formatVNDFull(parseFloat(r.amount))}</div>
                </div>
            </div>
        `;
    });
    list.innerHTML = html;
}

function openRenewModalFromDetail() {
    if (!currentRowToEdit) return;
    const hId = currentRowToEdit.getAttribute('data-id');
    const hName = document.getElementById('dModalName').textContent;
    const currentExp = currentRowToEdit.querySelector('.date-info').textContent.trim();
    
    // Convert current exp to YYYY-MM-DD
    const expMatch = currentExp.match(/(\d{2})\/(\d{2})\/(\d{4})/);
    let nextReg = new Date().toISOString().split('T')[0];
    let nextExp = '';

    if (expMatch) {
       nextReg = `${expMatch[3]}-${expMatch[2]}-${expMatch[1]}`;
       nextExp = `${parseInt(expMatch[3])+1}-${expMatch[2]}-${expMatch[1]}`;
    }

    document.getElementById('rmHostingName').textContent = hName;
    document.getElementById('rmRegDate').value = nextReg;
    document.getElementById('rmExpDate').value = nextExp;
    document.getElementById('rmPrice').value = currentRowToEdit.getAttribute('data-price') || 0;
    document.getElementById('rmNotes').value = '';
    formatRenewPrice();

    document.getElementById('renewModal').classList.add('active');
}

function closeRenewModalBtn() {
    document.getElementById('renewModal').classList.remove('active');
}

function closeRenewModal(e) {
    if (e.target === document.getElementById('renewModal')) closeRenewModalBtn();
}

function formatRenewPrice() {
    const val = parseInt(document.getElementById('rmPrice').value) || 0;
    document.getElementById('rmPriceHint').textContent = val.toLocaleString('vi-VN') + ' VNĐ';
}

async function submitRenewHosting() {
    if (!currentRowToEdit) return;
    const hId = currentRowToEdit.getAttribute('data-id');
    
    const data = {
        id: hId,
        amount: document.getElementById('rmPrice').value,
        regDate: document.getElementById('rmRegDate').value,
        expDate: document.getElementById('rmExpDate').value,
        notes: document.getElementById('rmNotes').value.trim()
    };

    if (!data.regDate || !data.expDate || !data.amount) {
        showToast('Vui lòng điền đủ thông tin gia hạn!', 'error');
        return;
    }

    showActionToast('Đang thực hiện gia hạn...', 'Gia hạn thành công!', async () => {
        try {
            const response = await fetch('/hostings/renew', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                // Refresh Page to update everything (including Shared Data Cache)
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast(result.message || 'Lỗi khi gia hạn', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Lỗi kết nối máy chủ', 'error');
        }
    });
}
</script>
    <!-- Notification Toast -->
    <div class="delete-toast" id="deleteToast">
        <div class="toast-spinner" id="dtSpinner"></div>
        <div id="dtSuccessIcon" style="display:none; color: #10b981; font-size: 22px; align-items: center; justify-content: center;"><i class="ph-fill ph-check-circle"></i></div>
        <div id="dtErrorIcon" style="display:none; color: #ef4444; font-size: 22px; align-items: center; justify-content: center;"><i class="ph-fill ph-x-circle"></i></div>
        <span id="dtMessage" style="color: #1e293b; font-weight: 600;">Đang xử lý...</span>
    </div>
    <?php include APP_DIR . '/Views/partials/footer.php'; ?>
</body>
</html>
