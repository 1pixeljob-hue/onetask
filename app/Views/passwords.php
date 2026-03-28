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
        const PASSWORDS = <?php echo json_encode($passwords ?? []); ?>;
    </script>
    <style>
        /* Modal & Toast Styles for Passwords */
        .modal-icon-brand {
            width: 48px;
            height: 48px;
            background: rgba(47, 171, 145, 0.1);
            color: #2fab91;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .modal-title-wrap {
            display: flex;
            align-items: center;
            gap: 16px;
        }
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
    </style>
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
                <!-- Toolbar -->
                <div class="pj-toolbar">
                    <div class="pj-search-wrap">
                        <i class="ph ph-magnifying-glass pj-search-icon"></i>
                        <input type="text" class="pj-search-input" placeholder="Tìm kiếm theo tiêu đề, tên đăng nhập, website...">
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
                                <div class="pj-dropdown-item" onclick="setPwdFilter('outline-blue', 'Email', this)">Email</div>
                                <div class="pj-dropdown-item" onclick="setPwdFilter('outline-red', 'Tài khoản', this)">Tài khoản</div>
                                <div class="pj-dropdown-item" onclick="setPwdFilter('outline-purple', 'Khác', this)">Khác</div>
                            </div>
                        </div>
                        <button class="pj-filter-btn" style="border-color: #8b5cf6; color: #8b5cf6;">
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
                        <div class="custom-select-wrapper">
                            <select class="modal-input" id="mPwdCategory" required>
                                <option value="Email" data-icon="ph-envelope">Email</option>
                                <option value="Tài khoản" data-icon="ph-user-circle">Tài khoản</option>
                                <option value="Khác" data-icon="ph-dots-three-circle">Khác</option>
                            </select>
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

    <!-- Notification Toast -->
    <div id="pwdToast" class="toast">
        <div class="toast-content">
            <div id="toastSpinner" class="spinner"></div>
            <i id="toastSuccessIcon" class="ph-fill ph-check-circle" style="display:none; color: #10b981; font-size: 24px;"></i>
            <span id="toastMsg">Đang xử lý...</span>
        </div>
    </div>

</body>
<script>
function togglePwdFilter() {
    document.getElementById('pwdFilterDropdown').classList.toggle('open');
}
function setPwdFilter(tagClass, label, el) {
    document.getElementById('pwdFilterLabel').textContent = label;
    document.querySelectorAll('#pwdFilterDropdown .pj-dropdown-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('pwdFilterLabel').parentElement.classList.remove('open');
    document.querySelectorAll('.pwd-card').forEach(card => {
        if (!tagClass) { card.style.display = ''; return; }
        const tag = card.querySelector('.badge-tag.' + tagClass);
        card.style.display = tag ? '' : 'none';
    });
}

let currentActionMode = 'add';
let currentEditId = null;

// Modal Password Functions
function openAddPwdModal() {
    currentActionMode = 'add';
    currentEditId = null;
    document.querySelector('.modal-title').textContent = 'Thêm Mật Khẩu Mới';
    document.querySelector('.modal-btn-submit').textContent = 'Thêm Mới';
    document.getElementById('addPasswordForm').reset();
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
    document.getElementById('mPwdCategory').value = pwd.category || 'Khác';
    document.getElementById('mPwdUser').value = pwd.username || '';
    document.getElementById('mPwdPass').value = pwd.password || '';
    document.getElementById('mPwdNotes').value = pwd.notes || '';
    
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
    
    showPwdToast(loadingMsg, successMsg);

    try {
        const response = await fetch('/passwords/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            alert('Lỗi: ' + (result.message || 'Không thể lưu mật khẩu'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Lỗi kết nối máy chủ');
    }
}

function showPwdToast(loadingMsg, successMsg) {
    const toast = document.getElementById('pwdToast');
    const msg = document.getElementById('toastMsg');
    const spinner = document.getElementById('toastSpinner');
    const successIcon = document.getElementById('toastSuccessIcon');
    
    spinner.style.display = 'block';
    successIcon.style.display = 'none';
    msg.textContent = loadingMsg;
    toast.classList.add('show');
    
    setTimeout(() => {
        spinner.style.display = 'none';
        successIcon.style.display = 'block';
        msg.textContent = successMsg;
        setTimeout(() => toast.classList.remove('show'), 2000);
    }, 1000);
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('pwdSearchInput').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase().trim();
        renderPasswords(term);
    });
    renderPasswords();
});

function renderPasswords(searchTerm = '', page = 1) {
    const grid = document.getElementById('pwdGrid');
    const emptyState = document.getElementById('pwdEmptyState');
    const itemsPerPage = 8;
    
    grid.innerHTML = '';
    grid.appendChild(emptyState);

    const filtered = PASSWORDS.filter(p => {
        const matchesSearch = !searchTerm || 
            p.title.toLowerCase().includes(searchTerm) || 
            (p.username && p.username.toLowerCase().includes(searchTerm)) ||
            (p.url && p.url.toLowerCase().includes(searchTerm));
        return matchesSearch;
    });

    if (filtered.length === 0) {
        emptyState.style.display = 'block';
        updatePagination(0, 0, 1);
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
    const borderClass = p.category === 'Tài khoản' ? 'border-top-red' : 'border-top-teal';
    card.className = `pwd-card ${borderClass}`;
    
    // Category Badge
    let badgeClass = 'outline-purple';
    let iconClass = 'ph-dots-three-circle';
    if (p.category === 'Email') { badgeClass = 'outline-blue'; iconClass = 'ph-envelope'; }
    if (p.category === 'Tài khoản') { badgeClass = 'outline-red'; iconClass = 'ph-user-circle'; }

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
                <span class="badge-tag ${badgeClass}"><i class="ph ${iconClass}"></i> ${p.category}</span>
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

async function promptDeletePwd(id, title) {
    if (confirm(`Bạn có chắc muốn xóa mật khẩu "${title}" không?`)) {
        try {
            const response = await fetch('/passwords/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            const result = await response.json();
            if (result.success) {
                window.location.reload();
            } else {
                alert('Lỗi: ' + (result.message || 'Không thể xóa mật khẩu'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Lỗi kết nối máy chủ');
        }
    }
}
</script>
</html>
