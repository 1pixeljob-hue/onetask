<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        // Data injected from PHP Models
        const CUSTOMERS = <?php echo json_encode($customers ?? []); ?>;
    </script>
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
            $activePage = 'customers';
            include APP_DIR . '/Views/partials/sidebar.php'; 
        ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <?php 
                $pageTitle = 'Quản Lý Khách Hàng';
                $pageSubtitle = 'Quản lý danh sách khách hàng của bạn';
                include APP_DIR . '/Views/partials/header.php'; 
            ?>

            <!-- Bulk Action Bar -->
            <div class="bulk-action-bar" id="bulkActionBar">
                <div class="bab-left">
                    <i class="ph-fill ph-check-circle"></i>
                    <span id="selectedCountText">Đã chọn 0 khách hàng</span>
                </div>
                <div class="bab-right">
                    <button class="btn-deselect" onclick="deselectAllCustomers()">Bỏ chọn</button>
                    <button class="btn-bulk-delete" onclick="promptBulkDelete()">
                        <i class="ph ph-trash"></i>
                        <span id="bulkDeleteBtnText">Xóa 0</span>
                    </button>
                </div>
            </div>

            <div class="content-body">
                <!-- Toolbar -->
                <div class="pj-toolbar">
                    <div class="pj-search-wrap">
                        <i class="ph ph-magnifying-glass pj-search-icon"></i>
                        <input type="text" class="pj-search-input" id="c_search" placeholder="Tìm kiếm theo tên, SĐT, email, công ty..." autocomplete="off">
                    </div>
                    <div class="pj-toolbar-right">
                        <button class="pj-add-btn" onclick="openAddCustomerModal()">
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
                                <th width="40"><input type="checkbox" class="cb-custom" id="selectAllCustomers" onclick="toggleSelectAll(this)"></th>
                                <th>TÊN KHÁCH HÀNG</th>
                                <th>LOẠI</th>
                                <th>THÔNG TIN LIÊN HỆ</th>
                                <th>CÔNG TY / MST</th>
                                <th>NGÀY TẠO</th>
                                <th width="80" class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody id="customerTableBody">
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

<!-- Modal Thêm Khách Hàng Mới -->
<div class="modal-overlay" id="addCustomerModal" onclick="closeAddCustomerModalOverlay(event)">
    <div class="modal-box scrollable">
        <div class="modal-header">
            <div class="modal-title-wrap">
                <div class="modal-icon-brand">
                    <i id="modalBrandIcon" class="ph-fill ph-user-circle"></i>
                </div>
                <h3 class="modal-title" id="modalTitle">Thêm Khách Hàng Mới</h3>
            </div>
            <button class="modal-close" onclick="closeAddCustomerModal()"><i class="ph ph-x"></i></button>
        </div>
        <div class="modal-body">
            <div class="modal-section-header">
                <span class="modal-section-title">Phân Loại & Thông Tin</span>
            </div>

            <div class="modal-field full">
                <label class="modal-label">Loại Khách Hàng</label>
                <div class="type-selector-wrap">
                    <label class="type-option">
                        <input type="radio" name="customer_type" value="individual" checked onchange="toggleCustomerTypeFields()">
                        <div class="type-card">
                            <i class="ph ph-user"></i>
                            <span>Cá Nhân</span>
                        </div>
                    </label>
                    <label class="type-option">
                        <input type="radio" name="customer_type" value="company" onchange="toggleCustomerTypeFields()">
                        <div class="type-card">
                            <i class="ph ph-buildings"></i>
                            <span>Công Ty</span>
                        </div>
                    </label>
                </div>
            </div>
            
            <div class="modal-field full">
                <label id="nameLabel" class="modal-label">Tên Khách Hàng <span class="req">*</span></label>
                <input type="text" class="modal-input" id="mCustomerName" placeholder="VD: Nguyễn Văn A">
            </div>

            <div id="companyFields" style="display: none;">
                <div class="modal-row">
                    <div class="modal-field">
                        <label class="modal-label">Người Đại Diện</label>
                        <input type="text" class="modal-input" id="mCustomerRepresentative" placeholder="VD: Ông Nguyễn Văn B">
                    </div>
                    <div class="modal-field">
                        <label class="modal-label">Mã Số Thuế</label>
                        <input type="text" class="modal-input" id="mCustomerTaxId" placeholder="VD: 0101234567">
                    </div>
                </div>
            </div>

            <div class="modal-row">
                <div class="modal-field">
                    <label class="modal-label">Số Điện Thoại</label>
                    <input type="text" class="modal-input" id="mCustomerPhone" placeholder="VD: 0912345678">
                </div>
                <div class="modal-field">
                    <label class="modal-label">Email</label>
                    <input type="email" class="modal-input" id="mCustomerEmail" placeholder="VD: email@example.com">
                </div>
            </div>

            <div class="modal-field full">
                <label class="modal-label">Công Ty / Tổ Chức</label>
                <input type="text" class="modal-input" id="mCustomerCompany" placeholder="VD: 1Pixel Co., Ltd">
            </div>

            <div class="modal-field full">
                <label class="modal-label">Địa Chỉ</label>
                <input type="text" class="modal-input" id="mCustomerAddress" placeholder="VD: 123 Đường ABC, Quận XYZ, TP. HCM">
            </div>

            <div class="modal-field full">
                <label class="modal-label">Ghi Chú</label>
                <textarea class="modal-textarea" id="mCustomerNotes" placeholder="Ghi chú thêm về khách hàng..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn-cancel" onclick="closeAddCustomerModal()">Hủy</button>
            <button class="modal-btn-submit" onclick="submitCustomerForm()">Thêm Mới</button>
        </div>
    </div>
</div>

<!-- Modal Chi Tiết Khách Hàng -->
<div class="modal-overlay" id="detailCustomerModal" onclick="closeDetailCustomerModal(event)">
    <div class="modal-box scrollable">
        <div class="modal-header-gradient">
            <h3 class="modal-title-light"><i class="ph ph-user-circle"></i> Chi Tiết Khách Hàng</h3>
            <button class="modal-close-light" onclick="closeDetailCustomerModalBtn()"><i class="ph ph-x"></i></button>
        </div>
        <div class="modal-body">
            <div class="detail-gray-card">
                <div class="detail-group">
                    <span class="detail-label">Tên Khách Hàng</span>
                    <span class="detail-val" id="dcName" style="font-size: 18px; font-weight: 700;">Nguyễn Văn A</span>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="detail-group">
                        <span class="detail-label-flex"><i class="ph ph-phone"></i> Số Điện Thoại</span>
                        <span class="dgc-val" id="dcPhone">N/A</span>
                    </div>
                    <div class="detail-group">
                        <span class="detail-label-flex"><i class="ph ph-envelope"></i> Email</span>
                        <span class="dgc-val" id="dcEmail">N/A</span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="detail-group">
                        <span class="detail-label-flex"><i class="ph ph-identification-card"></i> Loại</span>
                        <span class="dgc-val" id="dcTypeBadge">N/A</span>
                    </div>
                    <div class="detail-group" id="dcTaxIdGroup">
                        <span class="detail-label-flex"><i class="ph ph-hash"></i> Mã Số Thuế</span>
                        <span class="dgc-val" id="dcTaxId">N/A</span>
                    </div>
                </div>

                <div class="detail-group" id="dcRepGroup">
                    <span class="detail-label-flex"><i class="ph ph-user-gear"></i> Người Đại Diện</span>
                    <span class="dgc-val" id="dcRepresentative">N/A</span>
                </div>

                <div class="detail-group" id="dcCompanyGroup">
                    <span class="detail-label-flex"><i class="ph ph-buildings"></i> Công Ty</span>
                    <span class="dgc-val" id="dcCompany">N/A</span>
                </div>

                <div class="detail-group">
                    <span class="detail-label-flex"><i class="ph ph-map-pin"></i> Địa Chỉ</span>
                    <span class="dgc-val" id="dcAddress">N/A</span>
                </div>

                <div class="detail-group">
                    <span class="detail-label-flex"><i class="ph ph-calendar-blank"></i> Ngày Tạo</span>
                    <span class="dgc-val" id="dcDate">--/--/----</span>
                </div>
            </div>

            <div class="modal-section-header with-border" style="margin-top: 20px;">
                <span class="modal-section-title"><i class="ph ph-note" style="margin-right: 6px;"></i> Ghi Chú</span>
            </div>
            <div class="detail-desc-box" id="dcNotes" style="min-height: 80px; background: #f8fafc; padding: 12px; border-radius: 8px; font-size: 14px; color: #475569;">
                Không có ghi chú.
            </div>
        </div>
        <div class="detail-footer">
            <button class="btn-edit-full" onclick="openEditCustomerFromDetail()">
                <i class="ph ph-pencil-simple"></i> Chỉnh Sửa
            </button>
            <button class="btn-delete-outline" onclick="deleteCustomerFromDetail()">
                <i class="ph ph-trash"></i>
            </button>
        </div>
    </div>
</div>

<!-- Row Action Menu -->
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

<!-- Toast & Confirm Delete -->
<div class="delete-toast" id="deleteToast">
    <div class="toast-spinner" id="dtSpinner"></div>
    <div id="dtSuccessIcon" style="display:none; color: #10b981; font-size: 22px;"><i class="ph-fill ph-check-circle"></i></div>
    <div id="dtErrorIcon" style="display:none; color: #ef4444; font-size: 22px;"><i class="ph-fill ph-x-circle"></i></div>
    <span id="dtMessage" style="color: #1e293b; font-weight: 600;">Đang xử lý...</span>
</div>

<div class="modal-overlay" id="confirmDeleteModal" onclick="closeConfirmDelete(event)">
    <div class="modal-box" style="max-width: 420px; padding: 24px; border-radius: 16px;">
        <button class="modal-close" style="position: absolute; right: 20px; top: 20px;" onclick="closeConfirmDeleteBtn()"><i class="ph ph-x"></i></button>
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <div style="color: #ef4444; font-size: 24px;"><i class="ph ph-warning"></i></div>
            <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Xác nhận xóa khách hàng</h3>
        </div>
        <p style="margin-bottom: 24px; color: #4b5563; font-size: 14px;">
            Bạn có chắc chắn muốn xóa "<strong id="cdmCustomerName"></strong>"?<br> Hành động này không thể hoàn tác.
        </p>
        <div style="display: flex; gap: 12px;">
            <button class="modal-btn-cancel" style="flex: 1;" onclick="closeConfirmDeleteBtn()">Cancel</button>
            <button class="modal-btn-submit" id="cdmConfirmBtn" style="flex: 1; background: #fe2c2c;" onclick="confirmDeleteAction()">OK</button>
        </div>
    </div>
</div>

<script>
let selectedCustomers = new Set();
let currentPage = 1;
const itemsPerPage = 10;
let filteredCustomers = [];
let currentActionMode = 'add';
let currentRowToEdit = null;
let rowToDelete = null;

document.addEventListener('DOMContentLoaded', () => {
    filteredCustomers = [...(typeof CUSTOMERS !== 'undefined' ? CUSTOMERS : [])];
    initCustomerTable();
    
    document.getElementById('c_search').addEventListener('input', () => {
        currentPage = 1;
        initCustomerTable();
    });

    // Sidebar Active Fix (If needed)
});

function initCustomerTable() {
    const tbody = document.getElementById('customerTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    
    const searchVal = document.getElementById('c_search').value.toLowerCase();
    
    filteredCustomers = CUSTOMERS.filter(c => {
        return !searchVal || 
            (c.name || '').toLowerCase().includes(searchVal) || 
            (c.phone || '').toLowerCase().includes(searchVal) || 
            (c.email || '').toLowerCase().includes(searchVal) ||
            (c.company || '').toLowerCase().includes(searchVal);
    });

    const totalItems = filteredCustomers.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
    if (totalItems === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center" style="padding: 40px; color: #64748b;">Không có dữ liệu khách hàng</td></tr>';
        document.getElementById('paginationRow').style.display = 'none';
        return;
    }

    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = Math.min(startIdx + itemsPerPage, totalItems);
    const pageData = filteredCustomers.slice(startIdx, endIdx);

    pageData.forEach(c => {
        const tr = document.createElement('tr');
        populateRow(tr, c);
        tr.onclick = (e) => {
            if (!e.target.closest('input') && !e.target.closest('button')) {
                openCustomerDetail(tr);
            }
        };
        tbody.appendChild(tr);
    });

    renderPagination(totalItems, totalPages);
}

function populateRow(row, data) {
    let formattedDate = 'N/A';
    if (data.created_at) {
        formattedDate = new Date(data.created_at).toLocaleDateString('vi-VN');
    }

    row.setAttribute('data-id', data.id);
    row.setAttribute('data-type', data.type || 'individual');
    row.setAttribute('data-phone', data.phone || '');
    row.setAttribute('data-email', data.email || '');
    row.setAttribute('data-tax-id', data.tax_id || '');
    row.setAttribute('data-representative', data.representative || '');
    row.setAttribute('data-address', data.address || '');
    row.setAttribute('data-company', data.company || '');
    row.setAttribute('data-notes', data.notes || '');

    const typeBadge = data.type === 'company' 
        ? '<span class="badge-type company"><i class="ph ph-buildings"></i> Công Ty</span>'
        : '<span class="badge-type individual"><i class="ph ph-user"></i> Cá Nhân</span>';

    row.innerHTML = `
        <td><input type="checkbox" class="cb-custom" onclick="handleRowSelection(this)"></td>
        <td><div class="cell-main">${data.name}</div></td>
        <td>${typeBadge}</td>
        <td>
            <div class="cell-sub" style="margin-bottom: 4px;"><i class="ph ph-phone"></i> ${data.phone || 'N/A'}</div>
            <div class="cell-sub"><i class="ph ph-envelope"></i> ${data.email || 'N/A'}</div>
        </td>
        <td>
            <div class="text-main">${data.company || 'N/A'}</div>
            ${data.tax_id ? `<div class="cell-sub" style="font-size: 11px; margin-top: 2px;">MST: ${data.tax_id}</div>` : ''}
        </td>
        <td><div class="date-info"><i class="ph ph-calendar-blank"></i> ${formattedDate}</div></td>
        <td class="text-center"><button class="btn-action"><i class="ph ph-dots-three"></i></button></td>
    `;
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

    let html = `<button class="pg-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})"><i class="ph ph-caret-left"></i></button>`;
    for (let i = 1; i <= totalPages; i++) {
        html += `<button class="pg-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
    }
    html += `<button class="pg-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})"><i class="ph ph-caret-right"></i></button>`;
    buttonsContainer.innerHTML = html;
}

function changePage(page) {
    currentPage = page;
    initCustomerTable();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Bulk Actions
function updateBulkActionBar() {
    const bar = document.getElementById('bulkActionBar');
    const countText = document.getElementById('selectedCountText');
    const deleteBtnText = document.getElementById('bulkDeleteBtnText');
    const count = selectedCustomers.size;

    if (count > 0) {
        countText.textContent = `Đã chọn ${count} khách hàng`;
        deleteBtnText.textContent = `Xóa ${count}`;
        bar.classList.add('show');
    } else {
        bar.classList.remove('show');
    }
}

function toggleSelectAll(masterCb) {
    const checkboxes = document.querySelectorAll('.data-table tbody .cb-custom');
    selectedCustomers.clear();
    checkboxes.forEach(cb => {
        cb.checked = masterCb.checked;
        if (cb.checked) {
            const id = cb.closest('tr').getAttribute('data-id');
            selectedCustomers.add(id);
        }
    });
    updateBulkActionBar();
}

function handleRowSelection(cb) {
    const id = cb.closest('tr').getAttribute('data-id');
    if (cb.checked) {
        selectedCustomers.add(id);
    } else {
        selectedCustomers.delete(id);
        document.getElementById('selectAllCustomers').checked = false;
    }
    updateBulkActionBar();
}

function deselectAllCustomers() {
    document.getElementById('selectAllCustomers').checked = false;
    document.querySelectorAll('.data-table .cb-custom').forEach(cb => cb.checked = false);
    selectedCustomers.clear();
    updateBulkActionBar();
}

// Modals
function openAddCustomerModal() {
    currentActionMode = 'add';
    currentRowToEdit = null;
    resetCustomerForm();
    document.getElementById('modalTitle').textContent = 'Thêm Khách Hàng Mới';
    document.getElementById('modalBrandIcon').className = 'ph-fill ph-user-circle';
    document.querySelector('#addCustomerModal .modal-btn-submit').textContent = 'Thêm Mới';
    document.getElementById('addCustomerModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function openEditCustomerModal(tr) {
    currentActionMode = 'edit';
    currentRowToEdit = tr;
    
    const type = tr.getAttribute('data-type') || 'individual';
    document.querySelector(`input[name="customer_type"][value="${type}"]`).checked = true;
    toggleCustomerTypeFields();

    document.getElementById('mCustomerName').value = tr.querySelector('.cell-main').textContent;
    document.getElementById('mCustomerPhone').value = tr.getAttribute('data-phone');
    document.getElementById('mCustomerEmail').value = tr.getAttribute('data-email');
    document.getElementById('mCustomerTaxId').value = tr.getAttribute('data-tax-id');
    document.getElementById('mCustomerRepresentative').value = tr.getAttribute('data-representative');
    document.getElementById('mCustomerCompany').value = tr.getAttribute('data-company');
    document.getElementById('mCustomerAddress').value = tr.getAttribute('data-address');
    document.getElementById('mCustomerNotes').value = tr.getAttribute('data-notes');

    document.getElementById('modalTitle').textContent = 'Chỉnh Sửa Khách Hàng';
    document.getElementById('modalBrandIcon').className = 'ph-fill ph-pencil-simple';
    document.querySelector('#addCustomerModal .modal-btn-submit').textContent = 'Cập Nhật';
    document.getElementById('addCustomerModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeAddCustomerModal() {
    document.getElementById('addCustomerModal').classList.remove('active');
    document.body.style.overflow = '';
}

function closeAddCustomerModalOverlay(e) {
    if (e.target === document.getElementById('addCustomerModal')) closeAddCustomerModal();
}

function resetCustomerForm() {
    document.querySelector('input[name="customer_type"][value="individual"]').checked = true;
    toggleCustomerTypeFields();
    document.getElementById('mCustomerName').value = '';
    document.getElementById('mCustomerPhone').value = '';
    document.getElementById('mCustomerEmail').value = '';
    document.getElementById('mCustomerTaxId').value = '';
    document.getElementById('mCustomerRepresentative').value = '';
    document.getElementById('mCustomerCompany').value = '';
    document.getElementById('mCustomerAddress').value = '';
    document.getElementById('mCustomerNotes').value = '';
}

function toggleCustomerTypeFields() {
    const type = document.querySelector('input[name="customer_type"]:checked').value;
    const companyFields = document.getElementById('companyFields');
    const companyInput = document.getElementById('mCustomerCompany').closest('.modal-field');
    const nameLabel = document.getElementById('nameLabel');

    if (type === 'company') {
        companyFields.style.display = 'block';
        companyInput.style.display = 'block';
        nameLabel.textContent = 'Tên Công Ty *';
    } else {
        companyFields.style.display = 'none';
        companyInput.style.display = 'none';
        nameLabel.textContent = 'Họ Và Tên *';
    }
}

function openCustomerDetail(tr) {
    currentRowToEdit = tr;
    const type = tr.getAttribute('data-type');
    document.getElementById('dcName').textContent = tr.querySelector('.cell-main').textContent;
    document.getElementById('dcPhone').textContent = tr.getAttribute('data-phone') || 'N/A';
    document.getElementById('dcEmail').textContent = tr.getAttribute('data-email') || 'N/A';
    
    document.getElementById('dcTaxId').textContent = tr.getAttribute('data-tax-id') || 'N/A';
    document.getElementById('dcRepresentative').textContent = tr.getAttribute('data-representative') || 'N/A';
    document.getElementById('dcCompany').textContent = tr.getAttribute('data-company') || 'N/A';
    
    document.getElementById('dcTypeBadge').innerHTML = type === 'company' 
        ? '<span class="badge-type company" style="margin-left:0;"><i class="ph ph-buildings"></i> Công Ty</span>'
        : '<span class="badge-type individual" style="margin-left:0;"><i class="ph ph-user"></i> Cá Nhân</span>';

    // Show/Hide groups based on type
    document.getElementById('dcTaxIdGroup').style.display = type === 'company' ? 'block' : 'none';
    document.getElementById('dcRepGroup').style.display = type === 'company' ? 'block' : 'none';
    document.getElementById('dcCompanyGroup').style.display = type === 'company' ? 'block' : 'none';

    document.getElementById('dcAddress').textContent = tr.getAttribute('data-address') || 'N/A';
    document.getElementById('dcDate').textContent = tr.querySelector('.date-info').textContent;
    document.getElementById('dcNotes').textContent = tr.getAttribute('data-notes') || 'Không có ghi chú.';

    document.getElementById('detailCustomerModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeDetailCustomerModalBtn() {
    document.getElementById('detailCustomerModal').classList.remove('active');
    document.body.style.overflow = '';
}

function closeDetailCustomerModal(e) {
    if (e.target === document.getElementById('detailCustomerModal')) closeDetailCustomerModalBtn();
}

// CRUD Actions
async function submitCustomerForm() {
    const name = document.getElementById('mCustomerName').value.trim();
    if (!name) {
        alert('Vui lòng nhập tên khách hàng');
        return;
    }

    const data = {
        id: currentRowToEdit ? currentRowToEdit.getAttribute('data-id') : null,
        type: document.querySelector('input[name="customer_type"]:checked').value,
        name: name,
        phone: document.getElementById('mCustomerPhone').value.trim(),
        email: document.getElementById('mCustomerEmail').value.trim(),
        tax_id: document.getElementById('mCustomerTaxId').value.trim(),
        representative: document.getElementById('mCustomerRepresentative').value.trim(),
        company: document.getElementById('mCustomerCompany').value.trim(),
        address: document.getElementById('mCustomerAddress').value.trim(),
        notes: document.getElementById('mCustomerNotes').value.trim()
    };

    showToast('Đang lưu khách hàng...');
    try {
        const response = await fetch('/customers/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.status === 'success') {
            showToast('Lưu thành công!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Lỗi: ' + (result.message || 'Không xác định'), 'error');
        }
    } catch (err) {
        console.error(err);
        showToast('Lỗi kết nối máy chủ', 'error');
    }
}

function promptBulkDelete() {
    const count = selectedCustomers.size;
    if (count === 0) return;
    rowToDelete = null; // Signal bulk delete
    document.getElementById('cdmCustomerName').textContent = `${count} khách hàng đã chọn`;
    document.getElementById('confirmDeleteModal').classList.add('active');
}

function deleteCustomerFromDetail() {
    closeDetailCustomerModalBtn();
    rowToDelete = currentRowToEdit;
    document.getElementById('cdmCustomerName').textContent = rowToDelete.querySelector('.cell-main').textContent;
    document.getElementById('confirmDeleteModal').classList.add('active');
}

function closeConfirmDeleteBtn() {
    document.getElementById('confirmDeleteModal').classList.remove('active');
}

function closeConfirmDelete(e) {
    if (e.target === document.getElementById('confirmDeleteModal')) closeConfirmDeleteBtn();
}

async function confirmDeleteAction() {
    closeConfirmDeleteBtn();
    
    if (rowToDelete) {
        // Single Delete
        const id = rowToDelete.getAttribute('data-id');
        showToast('Đang xóa khách hàng...');
        try {
            const response = await fetch('/customers/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });
            const result = await response.json();
            if (result.status === 'success') {
                showToast('Đã xóa thành công', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Lỗi khi xóa', 'error');
            }
        } catch (err) {
            showToast('Lỗi kết nối', 'error');
        }
    } else {
        // Bulk Delete
        const ids = Array.from(selectedCustomers);
        showToast(`Đang xóa ${ids.length} khách hàng...`);
        try {
            const response = await fetch('/customers/bulk-delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: ids })
            });
            const result = await response.json();
            if (result.status === 'success') {
                showToast('Xóa thành công!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Lỗi khi xóa hàng loạt', 'error');
            }
        } catch (err) {
            showToast('Lỗi kết nối', 'error');
        }
    }
}

// Utility
function showToast(msg, icon = 'dtSpinner') {
    const t = document.getElementById('deleteToast');
    const m = document.getElementById('dtMessage');
    const s = document.getElementById('dtSpinner');
    const succ = document.getElementById('dtSuccessIcon');
    const err = document.getElementById('dtErrorIcon');
    
    m.textContent = msg;
    t.classList.add('show');
    
    s.style.display = 'none';
    succ.style.display = 'none';
    err.style.display = 'none';

    if (icon === 'success') succ.style.display = 'block';
    else if (icon === 'error') err.style.display = 'block';
    else s.style.display = 'block';
}

// Row Actions Toggle (Directly from projects.php logic for consistency)
document.addEventListener('click', function(e) {
    const menu = document.getElementById('rowActionMenu');
    if (!menu) return;

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
                openCustomerDetail(tr);
            } else if (item.classList.contains('ram-edit')) {
                openEditCustomerModal(tr);
            } else if (item.classList.contains('ram-delete')) {
                rowToDelete = tr;
                const name = tr.querySelector('.cell-main').textContent.trim();
                document.getElementById('cdmCustomerName').textContent = name;
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
</script>

<?php include APP_DIR . '/Views/partials/footer.php'; ?>
</body>
</html>
