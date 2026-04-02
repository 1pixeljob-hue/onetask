<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        const LOGS = <?php echo json_encode($logs ?? []); ?>;
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
            $activePage = 'logs';
            include APP_DIR . '/Views/partials/sidebar.php'; 
        ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <?php 
                $pageTitle = 'Hệ Thống Logs';
                $pageSubtitle = 'Theo dõi toàn bộ hoạt động hệ thống';
                include APP_DIR . '/Views/partials/header.php'; 
            ?>

            <div class="content-body">

                <!-- Toolbar -->
                <div class="logs-toolbar-row">
                    <div class="pj-search-wrap logs-search">
                        <i class="ph ph-magnifying-glass pj-search-icon"></i>
                        <input type="text" class="pj-search-input" id="logsSearchInput" placeholder="Tìm kiếm theo tên item hoặc user..." value="<?= htmlspecialchars($filters['search']) ?>" oninput="applyFilters(false)">
                    </div>
                    
                    <!-- Module Filter -->
                    <div class="pj-filter-wrapper">
                        <button class="pj-filter-btn" onclick="event.stopPropagation(); toggleLogDropdown('logsModuleDropdown')">
                            <i class="ph ph-funnel-simple"></i>
                            <span id="labelModule"><?= $filters['module'] ?: 'Tất cả Module' ?></span>
                            <i class="ph ph-caret-down"></i>
                        </button>
                        <div class="pj-dropdown" id="logsModuleDropdown">
                            <div class="pj-dropdown-item <?= !$filters['module'] ? 'active' : '' ?>" onclick="setLogFilter('module', '', 'Tất cả Module', this)">Tất cả Module</div>
                            <div class="pj-dropdown-item <?= $filters['module'] == 'Project' ? 'active' : '' ?>" onclick="setLogFilter('module', 'Project', 'Project', this)">Project</div>
                            <div class="pj-dropdown-item <?= $filters['module'] == 'Hosting' ? 'active' : '' ?>" onclick="setLogFilter('module', 'Hosting', 'Hosting', this)">Hosting</div>
                            <div class="pj-dropdown-item <?= $filters['module'] == 'CodeX' ? 'active' : '' ?>" onclick="setLogFilter('module', 'CodeX', 'CodeX', this)">CodeX</div>
                            <div class="pj-dropdown-item <?= $filters['module'] == 'Passwords' ? 'active' : '' ?>" onclick="setLogFilter('module', 'Passwords', 'Passwords', this)">Passwords</div>
                        </div>
                        <input type="hidden" id="logsModuleSelect" value="<?= htmlspecialchars($filters['module']) ?>">
                    </div>

                    <!-- Action Filter -->
                    <div class="pj-filter-wrapper">
                        <button class="pj-filter-btn" onclick="event.stopPropagation(); toggleLogDropdown('logsActionDropdown')">
                            <i class="ph ph-funnel-simple"></i>
                            <span id="labelAction"><?= $filters['action'] ?: 'Tất cả Hành động' ?></span>
                            <i class="ph ph-caret-down"></i>
                        </button>
                        <div class="pj-dropdown" id="logsActionDropdown">
                            <div class="pj-dropdown-item <?= !$filters['action'] ? 'active' : '' ?>" onclick="setLogFilter('action', '', 'Tất cả Hành động', this)">Tất cả Hành động</div>
                            <div class="pj-dropdown-item <?= $filters['action'] == 'Cập nhật' ? 'active' : '' ?>" onclick="setLogFilter('action', 'Cập nhật', 'Cập nhật', this)">Cập nhật</div>
                            <div class="pj-dropdown-item <?= $filters['action'] == 'Tạo mới' ? 'active' : '' ?>" onclick="setLogFilter('action', 'Tạo mới', 'Tạo mới', this)">Tạo mới</div>
                            <div class="pj-dropdown-item <?= $filters['action'] == 'Xoá' ? 'active' : '' ?>" onclick="setLogFilter('action', 'Xoá', 'Xoá', this)">Xoá</div>
                        </div>
                        <input type="hidden" id="logsActionSelect" value="<?= htmlspecialchars($filters['action']) ?>">
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-container logs-table-container">
                    <table class="data-table logs-table" id="logsTable">
                        <thead>
                            <tr>
                                <th width="40"><input type="checkbox" class="cb-custom"></th>
                                <th>MODULE</th>
                                <th>HÀNH ĐỘNG</th>
                                <th>ITEM</th>
                                <th>USER</th>
                                <th>THỜI GIAN</th>
                                <th class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Không tìm thấy bản ghi log nào.</td>
                                </tr>
                            <?php else: ?>
                                    <?php foreach ($logs as $log): 
                                        $icon = 'ph-file-text';
                                        if ($log['module'] == 'Project') $icon = 'ph-folder';
                                        elseif ($log['module'] == 'Hosting') $icon = 'ph-hard-drives';
                                        elseif ($log['module'] == 'CodeX') $icon = 'ph-code';
                                        elseif ($log['module'] == 'Passwords') $icon = 'ph-key';

                                        $badgeClass = 'badge-blue';
                                        if ($log['action'] == 'Tạo mới') $badgeClass = 'badge-green';
                                        elseif ($log['action'] == 'Xoá') $badgeClass = 'badge-red';
                                        elseif ($log['action'] == 'Khôi phục') $badgeClass = 'badge-blue';
                                        elseif ($log['action'] == 'Cập nhật') $badgeClass = 'badge-yellow';
                                    ?>
                                <tr data-id="<?= $log['id'] ?>">
                                    <td><input type="checkbox" class="cb-custom"></td>
                                    <td><div class="log-module"><i class="ph <?= $icon ?>"></i> <?= htmlspecialchars($log['module']) ?></div></td>
                                    <td><span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($log['action']) ?></span></td>
                                    <td class="log-item-name"><?= htmlspecialchars($log['item_name']) ?></td>

                                    <td class="text-muted"><?= htmlspecialchars($log['user_name']) ?></td>
                                    <td class="text-muted"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                                    <td class="text-center">
                                        <div class="log-actions">
                                            <button class="btn-action" title="Xem" onclick="viewLogDetail(<?= $log['id'] ?>)"><i class="ph ph-eye color-blue"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="logs-pagination-row">
                    <span class="logs-count">Hiển thị <b><?= $offset + 1 ?></b> đến <b><?= min($offset + $limit, $totalLogs) ?></b> trong tổng số <b><?= $totalLogs ?></b> kết quả</span>
                    <div class="logs-pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?>&module=<?= $filters['module'] ?>&action=<?= $filters['action'] ?>&search=<?= urlencode($filters['search']) ?>" class="pg-btn"><i class="ph ph-caret-left"></i></a>
                        <?php endif; ?>
                        
                        <?php 
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $currentPage + 2);
                        for ($i = $start; $i <= $end; $i++): 
                        ?>
                            <a href="?page=<?= $i ?>&module=<?= $filters['module'] ?>&action=<?= $filters['action'] ?>&search=<?= urlencode($filters['search']) ?>" class="pg-btn <?= $i == $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?>&module=<?= $filters['module'] ?>&action=<?= $filters['action'] ?>&search=<?= urlencode($filters['search']) ?>" class="pg-btn"><i class="ph ph-caret-right"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
    </div>

    <!-- Toast Notification -->
    <div id="logToast" class="toast">
        <div class="toast-content">
            <div id="logToastSpinner" class="spinner"></div>
            <i id="logToastSuccessIcon" class="ph-fill ph-check-circle" style="display:none; color: #10b981; font-size: 24px;"></i>
            <i id="logToastErrorIcon" class="ph-fill ph-x-circle" style="display:none; color: #ef4444; font-size: 24px;"></i>
            <span id="logToastMsg">Đang xử lý...</span>
        </div>
    </div>

    <!-- Modal Chi tiết Log -->
    <div class="modal-overlay" id="logDetailModal" onclick="closeLogModalOverlay(event)">
        <div class="modal-box scrollable" style="max-width: 800px;">
            <div class="modal-header" style="background: #f0f9ff; border-bottom: 1px solid #e0f2fe;">
                <div class="modal-title-wrap">
                    <h3 class="modal-title" style="color: #0369a1;">Chi tiết Log</h3>
                </div>
                <button class="modal-close" onclick="closeLogModal()"><i class="ph ph-x"></i></button>
            </div>
            <div class="modal-body" id="logModalBody">
                <!-- Nội dung modal được render bằng JS -->
            </div>
            <div class="modal-footer" id="logModalFooter">
                <button class="modal-btn-cancel" onclick="closeLogModal()">Đóng</button>
            </div>
        </div>
    </div>

    <style>
    .log-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    .modal-box.scrollable {
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }
    .modal-box.scrollable .modal-body {
        flex: 1;
        overflow-y: auto;
        padding-right: 12px;
        margin-right: 4px;
    }
    .modal-box.scrollable .modal-body::-webkit-scrollbar {
        width: 6px;
    }
    .modal-box.scrollable .modal-body::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }
    .modal-box.scrollable .modal-body::-webkit-scrollbar-track {
        background-color: #f1f5f9;
        border-radius: 10px;
    }

    .log-detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .log-detail-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }
    .log-detail-value {
        font-size: 15px;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .log-data-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-top: 16px;
    }
    .log-data-title {
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 16px;
        display: block;
    }
    .log-data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }
    .log-data-field {
        display: flex;
        gap: 10px;
    }
    .log-data-icon {
        color: #94a3b8;
        font-size: 18px;
        margin-top: 2px;
    }
    .log-data-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .log-data-label {
        font-size: 12px;
        color: #64748b;
    }
    .log-data-val {
        font-size: 14px;
        color: #334155;
        font-weight: 500;
        word-break: break-all;
    }
    .badge-log {
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-action-create, .badge-green { background: #ecfdf5; color: #166534; border: 1px solid #a7f3d0; }
    .badge-action-update, .badge-yellow { background: #fefce8; color: #854d0e; border: 1px solid #fef08a; }
    .badge-action-delete, .badge-red { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .badge-blue { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
    .badge-action-renew { background: #f5f3ff; color: #5b21b6; border: 1px solid #ddd6fe; }

    .log-module {
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #475569;
    }
    .log-item-name {
        font-size: 13.5px;
        color: #334155;
        font-weight: 500;
    }



    /* Toast Notification Styles */
    .toast {
        position: fixed;
        bottom: 32px;
        right: 32px;
        background: #fff;
        border-radius: 12px;
        padding: 16px 24px;
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1);
        z-index: 2000;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border-left: 4px solid #2fab91;
    }
    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    .toast-content {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        color: #1e293b;
    }
    .spinner {
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #2fab91;
        border-radius: 50%;
        animation: spin-toast 1s linear infinite;
    }
    @keyframes spin-toast {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>

    <script>
    function showLogToast(message, type = 'loading') {
        const toast = document.getElementById('logToast');
        const msg = document.getElementById('logToastMsg');
        const spinner = document.getElementById('logToastSpinner');
        const successIcon = document.getElementById('logToastSuccessIcon');
        const errorIcon = document.getElementById('logToastErrorIcon');
        
        // Reset
        spinner.style.display = 'none';
        successIcon.style.display = 'none';
        errorIcon.style.display = 'none';
        toast.style.borderLeftColor = '#2fab91';

        msg.textContent = message;
        
        if (type === 'loading') {
            spinner.style.display = 'block';
        } else if (type === 'success') {
            successIcon.style.display = 'block';
            toast.style.borderLeftColor = '#10b981';
        } else if (type === 'error') {
            errorIcon.style.display = 'block';
            toast.style.borderLeftColor = '#ef4444';
        }
        
        toast.classList.add('show');
        
        if (type !== 'loading') {
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    }

    // Toggle custom dropddowns for filters
    function toggleLogDropdown(id) {
        const dd = document.getElementById(id);
        if (!dd) return;
        
        const isOpen = dd.classList.contains('open');
        
        // Close all dropdowns first
        document.querySelectorAll('.pj-dropdown').forEach(d => {
            if (d.id !== id) d.classList.remove('open');
        });
        
        // Toggle current dropdown
        dd.classList.toggle('open', !isOpen);
    }

    function setLogFilter(type, value, label, el) {
        if (type === 'module') {
            document.getElementById('logsModuleSelect').value = value;
            document.getElementById('labelModule').textContent = label;
        } else {
            document.getElementById('logsActionSelect').value = value;
            document.getElementById('labelAction').textContent = label;
        }
        
        // Highlight active item
        el.closest('.pj-dropdown').querySelectorAll('.pj-dropdown-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
        
        // Close dropdown and apply filter
        el.closest('.pj-dropdown').classList.remove('open');
        applyFilters();
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.pj-filter-wrapper')) {
            document.querySelectorAll('.pj-dropdown').forEach(d => d.classList.remove('open'));
        }
    });

function viewLogDetail(id) {
    try {
        const logList = Array.isArray(LOGS) ? LOGS : Object.values(LOGS || {});
        const log = logList.find(l => l.id == id);
        
        if (!log) {
            console.error('Log not found for ID:', id);
            alert("Không tìm thấy dữ liệu log.");
            return;
        }

        const modalBody = document.getElementById('logModalBody');
        const modalFooter = document.getElementById('logModalFooter');
        
        // Icon mapping
        let icon = 'ph-file-text';
        if (log.module == 'Project') icon = 'ph-folder';
        else if (log.module == 'Hosting') icon = 'ph-hard-drives';
        else if (log.module == 'CodeX') icon = 'ph-code';
        else if (log.module == 'Passwords') icon = 'ph-key';

        // Normalize action string to handle different utf-8 spaces and accents
        let actionStr = log.action ? log.action.toLowerCase() : '';
        
        // Badge mapping
        let badgeClass = 'badge-action-update';
        if (actionStr.includes('tạo')) badgeClass = 'badge-action-create';
        else if (actionStr.includes('xo') || actionStr.includes('xóa')) badgeClass = 'badge-action-delete';
        else if (actionStr.includes('gia hạn')) badgeClass = 'badge-action-renew';


        let dataTitle = 'Chi tiết dữ liệu';
        if (actionStr.includes('xo') || actionStr.includes('xóa')) dataTitle = 'Dữ liệu đã xóa';
        else if (actionStr.includes('tạo') || actionStr.includes('thêm')) dataTitle = 'Dữ liệu mới';
        
        let dataHtml = '';

        if (log.data) {
            try {
                let dataObj = JSON.parse(log.data);
                if (typeof dataObj === 'string') {
                    dataObj = JSON.parse(dataObj); // handle double stringification
                }
                
                // If it's an update and we have 'old' and 'new' objects
                if (actionStr.includes('cập nhật') && dataObj && dataObj.old && dataObj.new) {
                    dataTitle = 'Thay đổi';
                    dataHtml = `
                        <div class="log-data-title" style="margin-top: 20px;">${dataTitle}</div>
                        <div class="log-data-card">
                            ${renderUpdateChanges(dataObj.old, dataObj.new, log.module)}
                        </div>
                    `;
                } else {
                    // Fallback to regular format if it's an old update log or standard log
                    if (actionStr.includes('cập nhật') && dataTitle === 'Chi tiết dữ liệu') {
                        dataTitle = 'Dữ liệu trước khi cập nhật';
                    }
                    dataHtml = `
                        <div class="log-data-title" style="margin-top: 20px;">${dataTitle}</div>
                        <div class="log-data-card">
                            ${renderLogDataFields(dataObj, log.module)}
                        </div>
                    `;
                }
            } catch (e) {
                console.error("Parse JSON error:", e);
                dataHtml = `<div class="log-data-title" style="margin-top: 20px;">Dữ liệu thô</div><div class="log-data-card"><pre style="font-size:12px; overflow:auto;">${log.data}</pre></div>`;
            }
        }

        modalBody.innerHTML = `
            <div class="log-detail-grid">
                <div class="log-detail-item">
                    <span class="log-detail-label">Module</span>
                    <span class="log-detail-value"><i class="ph ${icon}"></i> ${log.module || ''}</span>
                </div>
                <div class="log-detail-item">
                    <span class="log-detail-label">Hành động</span>
                    <span class="log-detail-value"><span class="badge-log ${badgeClass}">${log.action || ''}</span></span>
                </div>
                <div class="log-detail-item">
                    <span class="log-detail-label">Item</span>
                    <span class="log-detail-value">${log.item_name || ''}</span>
                </div>
                <div class="log-detail-item">
                    <span class="log-detail-label">User</span>
                    <span class="log-detail-value">${log.user_name || ''}</span>
                </div>
                <div class="log-detail-item" style="grid-column: span 2;">
                    <span class="log-detail-label">Thời gian</span>
                    <span class="log-detail-value">${log.created_at ? new Date(log.created_at).toLocaleString('vi-VN') : ''}</span>
                </div>
            </div>
            ${dataHtml}
        `;

        // Footer buttons
        let footerHtml = `<button class="modal-btn-cancel" onclick="closeLogModal()">Đóng</button>`;
        if ((actionStr.includes('xo') || actionStr.includes('xóa') || actionStr.includes('cập nhật')) && log.data) {
            footerHtml = `
                <button class="modal-btn-submit" style="background: #10b981; border: none; min-width: 120px;" onclick="restoreLog(${log.id})">
                    <i class="ph ph-arrows-counter-clockwise"></i> Restore
                </button>
                <button class="modal-btn-cancel" onclick="closeLogModal()">Đóng</button>
            `;
        }
        modalFooter.innerHTML = footerHtml;

        document.getElementById('logDetailModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    } catch (err) {
        console.error("Critical error in viewLogDetail:", err);
        alert("Đã xảy ra lỗi khi mở modal. Vui lòng kiểm tra console.");
    }
}

function renderUpdateChanges(oldData, newData, module) {
    let html = '';
    const labelMap = {
        'name': 'Tên', 'title': 'Tiêu đề', 'domain': 'Domain', 'provider': 'Nhà cung cấp',
        'reg_date': 'Ngày đăng ký', 'exp_date': 'Ngày hết hạn', 'price': 'Giá',
        'status': 'Trạng thái', 'url': 'Website', 'username': 'Tên đăng nhập',
        'password': 'Mật khẩu', 'language': 'Ngôn ngữ', 'description': 'Mô tả', 'type': 'Loại',
        'admin_url': 'Link Admin', 'admin_user': 'Admin User', 'admin_pass': 'Admin Pass',
        'color': 'Màu nền', 'text_color': 'Màu chữ', 'desc': 'Mô tả'
    };

    const statusMap = {
        'planning': 'Lên kế hoạch',
        'doing': 'Đang thực hiện',
        'done': 'Hoàn thành',
        'active': 'Hoạt động',
        'expired': 'Hết hạn',
        'suspend': 'Tạm ngưng'
    };

    let hasChanges = false;
    for (let key in newData) {
        if (key === 'id' || key === 'created_at' || key === 'updated_at') continue;
        
        let oldVal = oldData[key] || '';
        let newVal = newData[key] || '';
        
        if (oldVal !== newVal) {
            hasChanges = true;
            let label = labelMap[key] || key;
            
            // Format status mapping
            if (key === 'status') {
                oldVal = statusMap[oldVal] || oldVal;
                newVal = statusMap[newVal] || newVal;
            }

            // Format passwords
            if (key === 'password' || key === 'pass' || key === 'admin_pass') {
                oldVal = "••••••••";
                newVal = "••••••••";
            }
            if (oldVal === '') oldVal = 'Trống';

            html += `
                <div class="diff-block">
                    <div class="diff-label">${label}</div>
                    <div class="diff-row">
                        <div class="diff-before">
                            <span class="diff-tag-before">Trước</span>
                            <span class="diff-text">${oldVal}</span>
                        </div>
                        <div class="diff-arrow"><i class="ph ph-arrow-right"></i></div>
                        <div class="diff-after">
                            <span class="diff-tag-after">Sau</span>
                            <span class="diff-text">${newVal}</span>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    if (!hasChanges) {
        return '<div style="padding: 15px; color: #64748b; font-size: 14px;">Không phát hiện thay đổi cụ thể.</div>';
    }
    
    return html;
}

function renderLogDataFields(data, module) {
    if (!data || typeof data !== 'object') return '';
    let fields = '';
    const iconMap = {
        'name': 'ph-identification-badge',
        'title': 'ph-text-t',
        'domain': 'ph-globe',
        'provider': 'ph-hard-drives',
        'reg_date': 'ph-calendar-blank',
        'exp_date': 'ph-calendar-blank',
        'price': 'ph-currency-circle-dollar',
        'status': 'ph-info',
        'url': 'ph-link',
        'username': 'ph-user',
        'password': 'ph-key',
        'language': 'ph-code',
        'description': 'ph-note',
        'amount': 'ph-currency-circle-dollar',
        'regDate': 'ph-calendar-blank',
        'expDate': 'ph-calendar-blank',
        'notes': 'ph-note',
        'usage_period': 'ph-hourglass',
        'usage': 'ph-hourglass'
    };



    const labelMap = {
        'name': 'Tên',
        'title': 'Tiêu đề',
        'domain': 'Domain',
        'provider': 'Nhà cung cấp',
        'reg_date': 'Ngày đăng ký',
        'exp_date': 'Ngày hết hạn',
        'price': 'Giá',
        'status': 'Trạng thái',
        'url': 'Đường dẫn',
        'username': 'Tài khoản',
        'password': 'Mật khẩu',
        'language': 'Ngôn ngữ',
        'description': 'Mô tả',
        'usage_period': 'Thời hạn sử dụng',
        'usage': 'Thời hạn',
        'amount': 'Số tiền gia hạn',
        'regDate': 'Ngày đăng ký',
        'expDate': 'Hạn sử dụng mới',
        'notes': 'Ghi chú'
    };



    for (let key in data) {
        if (key === 'id' || key === 'created_at' || key === 'updated_at') continue;
        
        let label = labelMap[key] || key;
        let icon = iconMap[key] || 'ph-dot';
        let val = data[key] || '---';

        if (key === 'price' || key === 'amount') {
            const num = parseInt(val);
            if (!isNaN(num)) val = num.toLocaleString('vi-VN') + ' VNĐ';
        }

        
        let isLink = false;
        if (key === 'url' || key === 'domain' || key === 'admin_url') {
            val = `<a href="${val}" target="_blank" style="color: #3b82f6; text-decoration: none;">${val} <i class="ph ph-link"></i></a>`;
            isLink = true;
        } else if (key === 'password' || key === 'pass' || key === 'admin_pass') {
            val = '<span style="letter-spacing: 2px; font-weight: bold; color: #94a3b8;">••••••••</span>';
        } else if (key === 'status') {
            const statusMap = {
                'planning': '<span class="status-badge badge-blue">Lên kế hoạch</span>',
                'doing': '<span class="status-badge badge-yellow">Đang thực hiện</span>',
                'done': '<span class="status-badge badge-green">Hoàn thành</span>',
                'active': '<span class="status-badge badge-green">Hoạt động</span>',
                'expired': '<span class="status-badge badge-red">Hết hạn</span>',
                'suspend': '<span class="status-badge badge-gray">Tạm ngưng</span>'
            };
            val = statusMap[val] || val;
        }

        fields += `
            <div class="log-data-field">
                <i class="ph ${icon} log-data-icon"></i>
                <div class="log-data-info">
                    <span class="log-data-label">${label}</span>
                    <span class="log-data-val ${isLink?'is-link':''}">${val}</span>
                </div>
            </div>
        `;
    }
    return `<div class="log-data-grid">${fields}</div>`;
}


function closeLogModal() {
    document.getElementById('logDetailModal').classList.remove('active');
    document.body.style.overflow = '';
}

function closeLogModalOverlay(e) {
    if (e.target === document.getElementById('logDetailModal')) closeLogModal();
}

    let filterTimer;
    function applyFilters(immediate = true) {
        clearTimeout(filterTimer);
        
        const apply = () => {
            const module = document.getElementById('logsModuleSelect').value;
            const action = document.getElementById('logsActionSelect').value;
            const search = document.getElementById('logsSearchInput').value;
            
            let url = new URL(window.location.href);
            url.searchParams.set('module', module);
            url.searchParams.set('action', action);
            url.searchParams.set('search', search);
            url.searchParams.set('page', 1);
            
            window.location.href = url.toString();
        };

        if (immediate) {
            apply();
        } else {
            filterTimer = setTimeout(apply, 500);
        }
    }

    document.getElementById('logsSearchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters(true);
        }
    });

async function restoreLog(id) {
    if (!confirm('Bạn có chắc chắn muốn khôi phục dữ liệu này?')) return;
    
    showLogToast('Đang khôi phục dữ liệu...', 'loading');
    try {
        const response = await fetch('/logs/restore', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        if (result.status === 'success' || result.success) {
            showLogToast('Khôi phục thành công!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showLogToast('Lỗi: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showLogToast('Đã xảy ra lỗi khi kết nối với máy chủ.', 'error');
    }
}
    </script>
    <?php include APP_DIR . '/Views/partials/footer.php'; ?>


</body>
</html>
