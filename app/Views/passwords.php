<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        // Data injected from PHP Models
        const PASSWORDS = <?php echo json_encode($passwords ?? []); ?> || [];
        const CATEGORIES = <?php echo json_encode($categories ?? []); ?> || [];
    </script>
    <script src="/js/shared-data.js"></script>
    <style>
        /* Modal & Toast Styles for Passwords */

        .label-with-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .label-link {
            font-size: 13px;
            font-weight: 600;
            color: #2fab91;
            text-decoration: none;
            transition: all 0.2s;
        }
        .label-link:hover {
            color: #248c76;
            text-decoration: underline;
        }
        .pwd-input-wrapper {
            position: relative;
        }
        .btn-toggle-pwd {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }
        .btn-toggle-pwd:hover {
            color: #1e293b;
        }
        
        /* Custom Select Styling */
        .custom-select-wrapper {
            position: relative;
        }
        
        /* Toast Notification */
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
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Password Grid Empty State */
        .pwd-empty-state {
            grid-column: 1 / -1;
            padding: 64px;
            text-align: center;
            background: #fff;
            border-radius: 16px;
            border: 1px dashed #cbd5e1;
        }
        .pwd-empty-state i {
            display: block;
            font-size: 48px;
            color: #94a3b8;
            margin-bottom: 16px;
        }
        .pwd-empty-state p {
            color: #64748b;
            font-size: 15px;
        }

        /* Category Management Styles */
        .cat-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 4px;
        }
        .cat-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.2s;
        }
        .cat-item:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .cat-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .cat-color-preview {
            width: 24px;
            height: 24px;
            border-radius: 6px;
        }
        .cat-name {
            font-weight: 600;
            color: #1e293b;
        }
        .cat-tag-preview {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .cat-actions {
            display: flex;
            gap: 8px;
        }
        .btn-add-cat-dashed {
            width: 100%;
            padding: 16px;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            background: none;
            color: #64748b;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-add-cat-dashed:hover {
            border-color: #2fab91;
            color: #2fab91;
            background: rgba(47, 171, 145, 0.05);
        }
        
        /* Color Picker Styles */
        .color-presets {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin: 12px 0;
        }
        .color-tile {
            aspect-ratio: 1;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .color-tile i {
            display: none;
            font-size: 14px;
        }
        .color-tile.active {
            border-color: #1e293b;
            transform: scale(1.1);
        }
        .color-tile.active i {
            display: block;
        }
        .hex-input-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f8fafc;
            padding: 4px 12px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .hex-input-wrapper .color-dot {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }
        .hex-input {
            border: none;
            background: none;
            font-family: monospace;
            font-weight: 600;
            color: #475569;
            flex: 1;
            padding: 8px 0;
            outline: none;
        }
        .cat-preview-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            border: 1px solid #e2e8f0;
        }
        .cat-preview-label {
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
            display: block;
        }
        /* Delete Modal specific */
    .modal-box.danger-modal { border-top: 5px solid #ef4444; }
    .btn-danger-ok { background: #ef4444 !important; color: white !important; font-weight: 600; flex: 1; height: 44px; border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
    .btn-danger-ok:hover { background: #dc2626 !important; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }

    /* Animation for modals */
    .modal-overlay { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; opacity: 0; }
    .modal-overlay.active { opacity: 1; pointer-events: auto; }
    .modal-overlay.active .modal-box { transform: scale(1); opacity: 1; }
    .modal-box { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); transform: scale(0.9); opacity: 0; }
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
                <a href="/projects" class="nav-item">
                    <i class="ph ph-folders"></i>
                    <span>Projects</span>
                </a>
                <a href="/reports" class="nav-item">
                    <i class="ph ph-chart-bar"></i>
                    <span>B&#225;o C&#225;o</span>
                </a>
                <a href="/passwords" class="nav-item active">
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
                    <h1>Password Manager</h1>
                    <p>Quản lý mật khẩu an toàn</p>
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
                <!-- Browser Honeypot for anti-autofill -->
                <div style="display: none;">
                    <input type="text" name="username">
                    <input type="password" name="password">
                </div>
                <!-- Toolbar -->
                <div class="pj-toolbar">
                    <div class="pj-search-wrap">
                        <i class="ph ph-magnifying-glass pj-search-icon"></i>
                        <input type="text" class="pj-search-input" id="pwd_search_v2" name="q_search_field" placeholder="Tìm kiếm theo tiêu đề, tên đăng nhập, website..." autocomplete="chrome-off">
                    </div>
                    <div class="pj-toolbar-right">
                        <div class="pj-filter-wrapper">
                            <button class="pj-filter-btn" onclick="togglePwdFilter()">
                                <i class="ph ph-funnel-simple"></i>
                                <span id="pwdFilterLabel">Lọc bởi loại</span>
                                <i class="ph ph-caret-down"></i>
                            </button>
                            <div class="pj-dropdown" id="pwdFilterDropdown">
                                <div class="pj-dropdown-item active" onclick="setPwdFilter('', 'Lọc bởi loại', this)">Tất cả</div>
                                <?php foreach ($categories as $cat): ?>
                                    <div class="pj-dropdown-item" onclick="setPwdFilter('<?php echo htmlspecialchars($cat['name']); ?>', '<?php echo htmlspecialchars($cat['name']); ?>', this)">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <button class="pj-filter-btn" style="border-color: #8b5cf6; color: #8b5cf6;" onclick="openCategoryModal()">
                            <i class="ph ph-tag"></i>
                            Danh Mục
                        </button>
                        <button class="pj-add-btn" onclick="openAddPwdModal()">
                            <i class="ph ph-plus"></i>
                            Thêm Mới
                        </button>
                    </div>
                </div>

                <!-- Password Grid -->
                <div class="pwd-grid" id="pwdGrid">
                    <!-- Data populated by JavaScript -->
                    <div class="pwd-empty-state" id="pwdEmptyState" style="display: none;">
                        <i class="ph ph-mask-sad"></i>
                        <p>Chưa có mật khẩu nào được lưu.</p>
                    </div>
                </div>
                

                <!-- Pagination -->
                <div class="logs-pagination-row" id="pwdPaginationRow" style="display: none;">
                    <span class="logs-count" id="pwdPaginationCount">Hiển thị: 0 - 0 / 0 mục</span>
                    <div class="logs-pagination" id="pwdPaginationButtons">
                    </div>
                </div>

            </div>
        </main>

    </div>

    <!-- Modal Thêm Mật Khẩu Mới -->
    <div class="modal-overlay" id="addPasswordModal" onclick="closeAddPasswordModalOverlay(event)">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <div class="modal-icon-brand">
                        <i class="ph-fill ph-key"></i>
                    </div>
                    <h3 class="modal-title">Thêm Mật Khẩu Mới</h3>
                </div>
                <button class="modal-close" onclick="closeAddPwdModal()"><i class="ph ph-x"></i></button>
            </div>
            
            <form id="addPasswordForm" onsubmit="submitAddPwdForm(event)">
                <div class="modal-body">
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-note-pencil"></i> Tiêu Đề <span class="req">*</span></label>
                        <input type="text" class="modal-input" id="mPwdTitle" placeholder="VD: Gmail - Công Ty" required>
                    </div>
                    
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-globe"></i> Website</label>
                        <input type="text" class="modal-input" id="mPwdUrl" placeholder="VD: https://gmail.com hoặc gmail.com">
                    </div>
                    
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-tag"></i> Danh Mục <span class="req">*</span></label>
                        <div class="pj-modal-select" id="mPwdCategorySelect" data-input-id="mPwdCategory">
                            <div class="pj-modal-select-trigger">
                                <i class="ph ph-tag"></i>
                                <span>Khác</span>
                                <i class="ph ph-caret-down trigger-chevron"></i>
                            </div>
                            <div class="pj-modal-select-menu pj-dropdown" style="width: 100%; right: auto; left: 0;">
                                <?php if (empty($categories)): ?>
                                    <div class="pj-dropdown-item active" data-value="Khác">
                                        Khác
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($categories as $index => $cat): ?>
                                        <div class="pj-dropdown-item <?php echo $index === 0 ? 'active' : ''; ?>" data-value="<?php echo htmlspecialchars($cat['name']); ?>">
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <!-- Actual hidden input for form data -->
                            <input type="hidden" id="mPwdCategory" value="<?php echo !empty($categories) ? htmlspecialchars($categories[0]['name']) : 'Khác'; ?>" required>
                        </div>
                    </div>
                    
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-user"></i> Tài Khoản <span class="req">*</span></label>
                        <input type="text" class="modal-input" id="mPwdUser" placeholder="VD: user@example.com" required>
                    </div>
                    
                    <div class="modal-field full">
                        <div class="label-with-action">
                            <label class="modal-label"><i class="ph ph-lock"></i> Mật Khẩu <span class="req">*</span></label>
                            <a href="javascript:void(0)" class="label-link" onclick="generateStrongPwd()">Tạo mật khẩu mạnh</a>
                        </div>
                        <div class="pwd-input-wrapper">
                            <input type="password" class="modal-input" id="mPwdPass" placeholder="••••••••" required>
                            <button type="button" class="btn-toggle-pwd" onclick="toggleAddPwdVisibility()">
                                <i class="ph ph-eye" id="mPwdEyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-article"></i> Ghi Chú</label>
                        <textarea class="modal-textarea" id="mPwdNotes" placeholder="Ghi chú bổ sung (tùy chọn)..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="modal-btn-cancel" onclick="closeAddPwdModal()">Hủy</button>
                    <button type="submit" class="modal-btn-submit">Thêm Mới</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Quản Lý Danh Mục -->
    <div class="modal-overlay" id="categoryModal" onclick="if(event.target.id==='categoryModal') closeCategoryModal()">
        <div class="modal-box" style="max-width: 550px;">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <div class="modal-icon-brand" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="ph-fill ph-tag"></i>
                    </div>
                    <h3 class="modal-title">Quản Lý Danh Mục</h3>
                </div>
                <button class="modal-close" onclick="closeCategoryModal()"><i class="ph ph-x"></i></button>
            </div>
            
            <div class="modal-body">
                <!-- Chế độ Danh sách -->
                <div id="catListView">
                    <button class="btn-add-cat-dashed" onclick="showAddCategoryForm()">
                        <i class="ph ph-plus"></i> Thêm Danh Mục Mới
                    </button>
                    
                    <div class="label-with-action" style="margin-top: 24px;">
                        <span class="modal-label" style="font-size: 14px; font-weight: 700;">Danh Sách Danh Mục (<span id="catCount">0</span>)</span>
                    </div>
                    
                    <div class="cat-list" id="catListContainer">
                        <!-- Categories dynamic render -->
                    </div>
                </div>

                <!-- Chế độ Thêm/Sửa -->
                <div id="catFormView" style="display: none;">
                    <div class="cat-preview-section">
                        <span class="cat-preview-label">Xem Trước</span>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span id="catPreviewTag" class="cat-tag-preview">Tên danh mục</span>
                            <span id="catPreviewHex" style="font-family: monospace; font-size: 13px; color: #64748b;">#2fab91</span>
                        </div>
                    </div>

                    <div class="modal-field full">
                        <label class="modal-label">Tên Danh Mục</label>
                        <input type="text" class="modal-input" id="catNameInput" placeholder="VD: Email, Banking, Gaming..." oninput="updateCatPreview()">
                    </div>

                    <div class="modal-field full">
                        <label class="modal-label">Màu Sắc</label>
                        <div class="hex-input-wrapper">
                            <div class="color-dot" id="catColorDot" style="background: #2fab91;"></div>
                            <input type="text" class="hex-input" id="catHexInput" value="#2fab91" oninput="updateCatPreviewFromHex()">
                        </div>
                    </div>

                    <div class="modal-label" style="margin-top: 16px;">Bộ màu gợi ý:</div>
                    <div class="color-presets" id="colorPresets">
                        <!-- Generated by JS -->
                    </div>

                    <div class="modal-footer" style="padding: 16px 0 0 0; margin-top: 24px; border-top: 1px solid #f1f5f9;">
                        <button type="button" class="modal-btn-cancel" onclick="showCategoryList()">Hủy</button>
                        <button type="button" class="modal-btn-submit" id="catSubmitBtn" onclick="saveCategory()">Thêm Mới</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer" id="catMainFooter">
                <button class="modal-btn-submit" style="width: 100%; height: 48px;" onclick="closeCategoryModal()">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Modal Xác nhận Xóa Mật Khẩu -->
    <div class="modal-overlay" id="confirmDeletePwdModal" onclick="closeConfirmDeletePwdModalOverlay(event)">
        <div class="modal-box danger-modal" style="max-width: 420px; padding: 24px; border-radius: 16px; position: relative;">
            <button class="modal-close" style="position: absolute; right: 20px; top: 20px;" onclick="closeConfirmDeletePwdModal()"><i class="ph ph-x"></i></button>
            
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                <div style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px; font-size: 24px; display: flex; align-items: center; justify-content: center;">
                    <i class="ph-fill ph-warning"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--text-main);">Xác nhận xóa mật khẩu</h3>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 12px; font-weight: 500;">HÀNH ĐỘNG KHÔNG THỂ HOÀN TÁC</p>
                </div>
            </div>
            
            <p style="margin: 0 0 28px 0; color: #475569; font-size: 14px; line-height: 1.6;">
                Bạn có chắc chắn muốn xóa mật khẩu "<strong id="cdmPwdTitle" style="color: #0f172a;"></strong>"? Toàn bộ dữ liệu của mục này sẽ bị xóa khỏi hệ thống.
            </p>
            
            <div style="display: flex; gap: 12px;">
                <button class="modal-btn-cancel" style="flex: 1; justify-content: center; height: 44px; border-radius: 10px; font-weight: 600;" onclick="closeConfirmDeletePwdModal()">Hủy Bỏ</button>
                <button class="btn-danger-ok" onclick="confirmDeletePwdAction()">Xác Nhận Xóa</button>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="pwdToast" class="toast">
        <div class="toast-content">
            <div id="toastSpinner" class="spinner"></div>
            <i id="toastSuccessIcon" class="ph-fill ph-check-circle" style="display:none; color: #10b981; font-size: 24px;"></i>
            <i id="toastErrorIcon" class="ph-fill ph-x-circle" style="display:none; color: #ef4444; font-size: 24px;"></i>
            <span id="toastMsg">Đang xử lý...</span>
        </div>
    </div>

</body>
<script>
function togglePwdFilter() {
    document.getElementById('pwdFilterDropdown').classList.toggle('open');
}
function setPwdFilter(catName, label, el) {
    currentCategoryFilter = catName;
    document.getElementById('pwdFilterLabel').textContent = label;
    document.querySelectorAll('#pwdFilterDropdown .pj-dropdown-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('pwdFilterDropdown').classList.remove('open');
    renderPasswords(currentSearchTerm);
}

let currentActionMode = 'add';
let currentEditId = null;
let pwdToDeleteId = null;
let currentSearchTerm = '';
let currentCategoryFilter = '';

// Modal Password Functions
function openAddPwdModal() {
    currentActionMode = 'add';
    currentEditId = null;
    document.querySelector('.modal-title').textContent = 'Thêm Mật Khẩu Mới';
    document.querySelector('.modal-btn-submit').textContent = 'Thêm Mới';
    document.getElementById('addPasswordForm').reset();
    
    // Reset Custom Select UI
    const customSelect = document.getElementById('mPwdCategorySelect');
    const firstOption = customSelect.querySelector('.pj-dropdown-item');
    if (firstOption) {
        const trigger = customSelect.querySelector('.pj-modal-select-trigger');
        trigger.querySelector('span').textContent = firstOption.textContent.trim();
        const icon = firstOption.querySelector('i');
        if (icon) trigger.querySelector('i:first-child').style.color = icon.style.color;
        customSelect.querySelectorAll('.pj-dropdown-item').forEach(i => i.classList.remove('active'));
        firstOption.classList.add('active');
        document.getElementById('mPwdCategory').value = firstOption.dataset.value;
    }

    document.getElementById('addPasswordModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function openEditPwdModal(id) {
    const pwd = PASSWORDS.find(p => p.id == id);
    if (!pwd) return;

    currentActionMode = 'edit';
    currentEditId = id;
    document.querySelector('.modal-title').textContent = 'Chỉnh Sửa Mật Khẩu';
    document.querySelector('.modal-btn-submit').textContent = 'Cập Nhật';
    
    document.getElementById('mPwdTitle').value = pwd.title;
    document.getElementById('mPwdUrl').value = pwd.url || '';
    document.getElementById('mPwdPass').value = pwd.password || '';
    document.getElementById('mPwdNotes').value = pwd.notes || '';

    // Sync Custom Select UI for Category
    const customSelect = document.getElementById('mPwdCategorySelect');
    const category = pwd.category || 'Khác';
    const option = customSelect.querySelector(`.pj-dropdown-item[data-value="${category}"]`);
    if (option) {
        const trigger = customSelect.querySelector('.pj-modal-select-trigger');
        trigger.querySelector('span').textContent = option.textContent.trim();
        const icon = option.querySelector('i');
        if (icon) trigger.querySelector('i:first-child').style.color = icon.style.color;
        
        customSelect.querySelectorAll('.pj-dropdown-item').forEach(i => i.classList.remove('active'));
        option.classList.add('active');
    }
    document.getElementById('mPwdCategory').value = category;
    
    document.getElementById('addPasswordModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeAddPwdModal() {
    document.getElementById('addPasswordModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('addPasswordForm').reset();
}

function closeAddPasswordModalOverlay(e) {
    if (e.target.id === 'addPasswordModal') closeAddPwdModal();
}

function toggleAddPwdVisibility() {
    const input = document.getElementById('mPwdPass');
    const icon = document.getElementById('mPwdEyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('ph-eye', 'ph-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('ph-eye-slash', 'ph-eye');
    }
}

function generateStrongPwd() {
    const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
    let pwd = "";
    for (let i = 0; i < 16; i++) {
        pwd += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('mPwdPass').value = pwd;
    if (document.getElementById('mPwdPass').type === 'password') toggleAddPwdVisibility();
}

async function submitAddPwdForm(e) {
    e.preventDefault();
    const data = {
        id: currentEditId,
        title: document.getElementById('mPwdTitle').value,
        url: document.getElementById('mPwdUrl').value,
        category: document.getElementById('mPwdCategory').value,
        username: document.getElementById('mPwdUser').value,
        password: document.getElementById('mPwdPass').value,
        notes: document.getElementById('mPwdNotes').value
    };

    const loadingMsg = currentActionMode === 'add' ? 'Đang thêm mật khẩu...' : 'Đang cập nhật...';
    const successMsg = currentActionMode === 'add' ? 'Đã thêm mật khẩu thành công!' : 'Đã cập nhật thành công!';
    
    showPwdToast(loadingMsg, 'loading');

    try {
        const response = await fetch('/passwords/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            // Update local data and UI immediately
            if (currentActionMode === 'add') {
                data.id = result.id || Date.now();
                PASSWORDS.unshift(data);
            } else {
                const index = PASSWORDS.findIndex(p => p.id == currentEditId);
                if (index !== -1) PASSWORDS[index] = { ...data, id: currentEditId };
            }
            
            showPwdToast(successMsg, 'success');
            setTimeout(() => {
                closeAddPwdModal();
                renderPasswords();
            }, 500);
        } else {
            showPwdToast(result.message || 'Không thể lưu mật khẩu', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showPwdToast('Lỗi kết nối máy chủ', 'error');
    }
}

// Category Management JS Logic
let currentCatEditId = null;
const COLOR_PRESETS = [
    '#2fab91', '#2563eb', '#ef4444', '#f59e0b', '#f97316', '#059669',
    '#38bdf8', '#6366f1', '#8b5cf6', '#d946ef', '#ec4899', '#475569'
];

function openCategoryModal() {
    document.getElementById('categoryModal').classList.add('active');
    document.body.style.overflow = 'hidden';
    showCategoryList();
    renderColorPresets();
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function showCategoryList() {
    document.getElementById('catListView').style.display = 'block';
    document.getElementById('catFormView').style.display = 'none';
    document.getElementById('catMainFooter').style.display = 'block';
    renderCategories();
}

function showAddCategoryForm(id = null) {
    currentCatEditId = id;
    document.getElementById('catListView').style.display = 'none';
    document.getElementById('catFormView').style.display = 'block';
    document.getElementById('catMainFooter').style.display = 'none';
    
    if (id) {
        const cat = CATEGORIES.find(c => c.id == id);
        document.getElementById('catNameInput').value = cat.name;
        document.getElementById('catHexInput').value = cat.color;
        document.getElementById('catSubmitBtn').textContent = 'Cập Nhật';
    } else {
        document.getElementById('catNameInput').value = '';
        document.getElementById('catHexInput').value = '#2fab91';
        document.getElementById('catSubmitBtn').textContent = 'Thêm Mới';
    }
    updateCatPreview();
}

function renderCategories() {
    const container = document.getElementById('catListContainer');
    document.getElementById('catCount').textContent = CATEGORIES.length;
    
    container.innerHTML = CATEGORIES.map(c => `
        <div class="cat-item">
            <div class="cat-info">
                <div class="cat-color-preview" style="background: ${c.color}"></div>
                <div class="cat-name">${c.name}</div>
                <div class="cat-tag-preview" style="background: ${c.color}20; color: ${c.color}; border-color: ${c.color}">${c.name}</div>
            </div>
            <div class="cat-actions">
                <button class="btn-icon-sm" onclick="showAddCategoryForm(${c.id})"><i class="ph ph-pencil-simple"></i></button>
                <button class="btn-icon-sm" style="color: #ef4444;" onclick="deleteCategory(${c.id})"><i class="ph ph-trash"></i></button>
            </div>
        </div>
    `).join('');
    
    // Update Password Modal Select options
    const catSelect = document.getElementById('mPwdCategory');
    const currentVal = catSelect.value;
    catSelect.innerHTML = CATEGORIES.map(c => `<option value="${c.name}">${c.name}</option>`).join('');
    if (CATEGORIES.length === 0) {
        catSelect.innerHTML = '<option value="Khác">Khác</option>';
    }
}

function renderColorPresets() {
    const container = document.getElementById('colorPresets');
    container.innerHTML = COLOR_PRESETS.map(c => `
        <div class="color-tile" style="background: ${c}" onclick="selectPresetColor('${c}', this)">
            <i class="ph ph-check"></i>
        </div>
    `).join('');
    selectPresetColor(document.getElementById('catHexInput').value);
}

function selectPresetColor(color, el = null) {
    document.getElementById('catHexInput').value = color;
    document.querySelectorAll('.color-tile').forEach(t => t.classList.remove('active'));
    if (el) el.classList.add('active');
    else {
        const active = [...document.querySelectorAll('.color-tile')].find(t => t.style.backgroundColor.includes(color.toLowerCase()));
        if (active) active.classList.add('active');
    }
    updateCatPreview();
}

function updateCatPreview() {
    const name = document.getElementById('catNameInput').value || 'Tên danh mục';
    const color = document.getElementById('catHexInput').value;
    const tag = document.getElementById('catPreviewTag');
    
    tag.textContent = name;
    tag.style.background = color + '20';
    tag.style.color = color;
    tag.style.borderColor = color;
    document.getElementById('catPreviewHex').textContent = color;
    document.getElementById('catColorDot').style.background = color;
}

function updateCatPreviewFromHex() {
    const hex = document.getElementById('catHexInput').value;
    if (/^#[0-9A-F]{6}$/i.test(hex)) {
        updateCatPreview();
    }
}

async function saveCategory() {
    const data = {
        id: currentCatEditId,
        name: document.getElementById('catNameInput').value,
        color: document.getElementById('catHexInput').value
    };

    if (!data.name) return alert('Vui lòng nhập tên danh mục');

    showPwdToast(currentCatEditId ? 'Đang cập nhật danh mục...' : 'Đang thêm danh mục...', 'loading');

    try {
        const response = await fetch('/passwords/categories/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            if (currentCatEditId) {
                const idx = CATEGORIES.findIndex(c => c.id == currentCatEditId);
                CATEGORIES[idx] = { ...data, id: currentCatEditId };
            } else {
                CATEGORIES.push({ ...data, id: result.id || Date.now() });
            }
            showPwdToast(currentCatEditId ? 'Đã cập nhật danh mục!' : 'Đã thêm danh mục thành công!', 'success');
            showCategoryList();
        } else {
            showPwdToast(result.message || 'Không thể lưu danh mục', 'error');
        }
    } catch (e) { 
        console.error(e); 
        showPwdToast('Lỗi kết nối máy chủ', 'error');
    }
}

async function deleteCategory(id) {
    if (!confirm('Bạn có chắc muốn xóa danh mục này?')) return;
    showPwdToast('Đang xóa danh mục...', 'loading');
    try {
        const response = await fetch('/passwords/categories/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const result = await response.json();
        if (result.success) {
            const idx = CATEGORIES.findIndex(c => c.id == id);
            if (idx !== -1) CATEGORIES.splice(idx, 1);
            showPwdToast('Đã xóa danh mục thành công!', 'success');
            renderCategories();
        } else {
            showPwdToast(result.message || 'Không thể xóa danh mục', 'error');
        }
    } catch (e) { 
        console.error(e); 
        showPwdToast('Lỗi kết nối máy chủ', 'error');
    }
}

function showPwdToast(message, type = 'loading') {
    const toast = document.getElementById('pwdToast');
    const msg = document.getElementById('toastMsg');
    const spinner = document.getElementById('toastSpinner');
    const successIcon = document.getElementById('toastSuccessIcon');
    const errorIcon = document.getElementById('toastErrorIcon');
    
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
        setTimeout(() => toast.classList.remove('show'), 3000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('pwd_search_v2').addEventListener('input', (e) => {
        currentSearchTerm = e.target.value.toLowerCase().trim();
        renderPasswords(currentSearchTerm);
    });
    renderPasswords();
    renderCategories(); // Ensure category dropdowns are populated on load
});

function renderPasswords(searchTerm = '', page = 1) {
    const grid = document.getElementById('pwdGrid');
    const emptyState = document.getElementById('pwdEmptyState');
    const itemsPerPage = 8;
    
    grid.innerHTML = '';
    grid.appendChild(emptyState);

    const filtered = PASSWORDS.filter(p => {
        // Search Filter
        const matchesSearch = !searchTerm || 
            p.title.toLowerCase().includes(searchTerm) || 
            (p.username && p.username.toLowerCase().includes(searchTerm)) ||
            (p.url && p.url.toLowerCase().includes(searchTerm));
        
        // Category Filter
        const matchesCategory = !currentCategoryFilter || p.category === currentCategoryFilter;

        return matchesSearch && matchesCategory;
    });

    if (filtered.length === 0) {
        emptyState.style.display = 'block';
        updatePagination(0, 0, 1, itemsPerPage);
    } else {
        emptyState.style.display = 'none';
        
        const totalItems = filtered.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const startIdx = (page - 1) * itemsPerPage;
        const endIdx = Math.min(startIdx + itemsPerPage, totalItems);
        
        filtered.slice(startIdx, endIdx).forEach(p => {
            const card = createPasswordCard(p);
            grid.appendChild(card);
        });
        
        updatePagination(totalItems, totalPages, page, itemsPerPage);
    }
}

function updatePagination(totalItems, totalPages, currentPage, itemsPerPage) {
    const row = document.getElementById('pwdPaginationRow');
    const countText = document.getElementById('pwdPaginationCount');
    const buttonsContainer = document.getElementById('pwdPaginationButtons');

    if (totalItems <= itemsPerPage) {
        row.style.display = 'none';
        return;
    }

    row.style.display = 'flex';
    const start = (currentPage - 1) * itemsPerPage + 1;
    const end = Math.min(currentPage * itemsPerPage, totalItems);
    countText.textContent = `Hiển thị: ${start} - ${end} / ${totalItems} mục`;

    let html = `<button class="pg-btn ${currentPage === 1 ? 'disabled' : ''}" onclick="renderPasswords('', ${currentPage - 1})">Trước</button>`;
    for (let i = 1; i <= totalPages; i++) {
        html += `<button class="pg-btn ${i === currentPage ? 'active' : ''}" onclick="renderPasswords('', ${i})">${i}</button>`;
    }
    html += `<button class="pg-btn ${currentPage === totalPages ? 'disabled' : ''}" onclick="renderPasswords('', ${currentPage + 1})">Sau</button>`;
    buttonsContainer.innerHTML = html;
}

function createPasswordCard(p) {
    const card = document.createElement('div');
    
    // Find category for custom color
    const cat = CATEGORIES.find(c => c.name === p.category);
    const catColor = cat ? cat.color : '#64748b';
    
    card.className = `pwd-card`;
    card.style.borderTop = `4px solid ${catColor}`;
    
    // Category Badge
    const badgeStyle = `background: ${catColor}15; color: ${catColor}; border-color: ${catColor}`;
    const iconClass = p.category === 'Email' ? 'ph-envelope' : (p.category === 'Tài khoản' ? 'ph-user-circle' : 'ph-dots-three-circle');

    const urlDisplay = p.url ? `
        <div class="pwd-link">
            <a href="${p.url.startsWith('http') ? p.url : 'http://' + p.url}" target="_blank">
                <i class="ph ph-link"></i> ${p.url.replace(/^https?:\/\//, '')}
            </a>
        </div>` : '';

    const notesDisplay = p.notes ? `
        <div class="pwd-field">
            <label>GHI CHÚ</label>
            <div class="pwd-input-group note-group">
                <input type="text" value="${p.notes}" readonly class="pwd-input input-yellow">
            </div>
        </div>` : '';

    card.innerHTML = `
        <div class="pwd-header">
            <h3>${p.title}</h3>
            <div class="pwd-tags">
                <span class="badge-tag" style="${badgeStyle}"><i class="ph ${iconClass}"></i> ${p.category}</span>
            </div>
            ${urlDisplay}
        </div>
        <div class="pwd-body">
            <div class="pwd-field">
                <label><i class="ph ph-user"></i> USERNAME</label>
                <div class="pwd-input-group">
                    <input type="text" value="${p.username}" readonly class="pwd-input">
                    <div class="pwd-input-actions">
                        <button class="btn-icon-sm" onclick="copyText(this, '${p.username}')"><i class="ph ph-copy"></i></button>
                    </div>
                </div>
            </div>
            <div class="pwd-field">
                <label><i class="ph ph-lock"></i> PASSWORD</label>
                <div class="pwd-input-group">
                    <input type="password" value="${p.password}" readonly class="pwd-input">
                    <div class="pwd-input-actions">
                        <button class="btn-icon-sm" onclick="toggleCardPwd(this)"><i class="ph ph-eye"></i></button>
                        <button class="btn-icon-sm" onclick="copyText(this, '${p.password}')"><i class="ph ph-copy"></i></button>
                    </div>
                </div>
            </div>
            ${notesDisplay}
        </div>
        <div class="pwd-footer">
            <button class="btn-pwd btn-pwd-edit" onclick="openEditPwdModal(${p.id})"><i class="ph ph-pencil-simple"></i> Sửa</button>
            <button class="btn-pwd btn-pwd-delete" onclick="promptDeletePwd(${p.id}, '${p.title}')"><i class="ph ph-trash"></i> Xóa</button>
        </div>
    `;
    return card;
}

function copyText(btn, text) {
    navigator.clipboard.writeText(text).then(() => {
        const icon = btn.querySelector('i');
        const oldClass = icon.className;
        icon.className = 'ph ph-check';
        icon.style.color = '#10b981';
        setTimeout(() => {
            icon.className = oldClass;
            icon.style.color = '';
        }, 2000);
    });
}

function toggleCardPwd(btn) {
    const group = btn.closest('.pwd-input-group');
    const input = group.querySelector('input');
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ph ph-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'ph ph-eye';
    }
}

function closeConfirmDeletePwdModal() {
    document.getElementById('confirmDeletePwdModal').classList.remove('active');
    document.body.style.overflow = '';
}

function closeConfirmDeletePwdModalOverlay(e) {
    if (e.target.id === 'confirmDeletePwdModal') closeConfirmDeletePwdModal();
}

async function promptDeletePwd(id, title) {
    pwdToDeleteId = id;
    document.getElementById('cdmPwdTitle').textContent = title;
    document.getElementById('confirmDeletePwdModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

async function confirmDeletePwdAction() {
    if (!pwdToDeleteId) return;
    
    closeConfirmDeletePwdModal();
    showPwdToast('Đang xóa mật khẩu...', 'loading');
    
    try {
        const response = await fetch('/passwords/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: pwdToDeleteId })
        });
        const result = await response.json();
        if (result.success) {
            // Update local state without reload to avoid UI glitches
            const index = PASSWORDS.findIndex(p => p.id == pwdToDeleteId);
            if (index !== -1) {
                PASSWORDS.splice(index, 1);
            }
            showPwdToast('Đã xóa mật khẩu thành công!', 'success');
            renderPasswords();
            pwdToDeleteId = null;
        } else {
            showPwdToast(result.message || 'Không thể xóa mật khẩu', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showPwdToast('Lỗi kết nối máy chủ', 'error');
    }
}
</script>
</html>
