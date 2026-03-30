<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeX - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fira+Code:wght@400;500&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="/js/shared-data.js"></script>
    <style>
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

        /* Danger Modal Styles */
        .danger-modal {
            border-top: 4px solid #ef4444 !important;
        }
        .btn-danger-ok {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-danger-ok:hover {
            background: #dc2626 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }
        .req {
            color: #ef4444;
            margin-left: 4px;
            font-weight: bold;
        }
        
        /* Modal Overlay for Loading state effect */
        .modal-overlay.loading {
            cursor: wait;
        }
        .modal-overlay.loading .modal-box {
            opacity: 0.7;
            pointer-events: none;
        }
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
                <a href="/passwords" class="nav-item">
                    <i class="ph ph-key"></i>
                    <span>Passwords</span>
                </a>
                <a href="/codex" class="nav-item active">
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
        <main class="main-content cx-main">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <h1>CodeX</h1>
                    <p>Quản lý các đoạn code hay dùng</p>
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

            <div class="content-body codex-body">
                <div class="codex-layout">

                    <!-- Left Panel: Filters -->
                    <div class="cx-panel cx-sidebar">
                        <div class="cx-sidebar-header">
                            <h3><i class="ph ph-funnel"></i> Bộ Lọc</h3>
                            <div class="cx-sidebar-actions">
                                <button class="btn-primary" onclick="openCxModal()"><i class="ph ph-plus"></i> Thêm
                                    Mới</button>
                                <button class="btn-cx-category" onclick="openCodeCategoryModal()"><i
                                        class="ph ph-tag"></i> Danh Mục</button>
                            </div>
                        </div>
                        <div class="cx-sidebar-nav" id="cxSidebarNav">
                            <div class="cx-nav-item active" onclick="filterByLang('all')">
                                <span>Tất cả ngôn ngữ</span>
                                <div class="cx-badge-right">
                                    <span class="cx-count" id="totalSnippetCount"><?php echo count($snippets); ?></span>
                                    <i class="ph ph-caret-right"></i>
                                </div>
                            </div>
                            <?php foreach ($categories as $cat):
                                $langCount = count(array_filter($snippets, function ($s) use ($cat) {
                                    return $s['language'] == $cat['name']; }));
                                ?>
                                <div class="cx-nav-item" data-lang-name="<?php echo $cat['name']; ?>"
                                    onclick="filterByLang('<?php echo $cat['name']; ?>')">
                                    <span><?php echo $cat['name']; ?></span>
                                    <span class="cx-count"><?php echo $langCount; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Middle Panel: Snippet List -->
                    <div class="cx-panel cx-list">
                        <div class="cx-list-header">
                            <div class="cx-search">
                                <i class="ph ph-magnifying-glass"></i>
                                <input type="text" id="cxSearchInput" placeholder="Tìm kiếm snippet..."
                                    oninput="searchSnippets()">
                            </div>
                            <p class="cx-subtitle" id="visibleCount"><?php echo count($snippets); ?> snippets</p>
                        </div>
                        <div class="cx-list-body" id="cxListBody">
                            <?php foreach ($snippets as $index => $snippet): ?>
                                <div class="cx-snippet-item <?php echo $index === 0 ? 'active' : ''; ?>"
                                    onclick="selectSnippet(<?php echo htmlspecialchars(json_encode($snippet)); ?>, this)"
                                    data-lang="<?php echo $snippet['language']; ?>"
                                    data-title="<?php echo strtolower($snippet['title']); ?>">
                                    <div class="cx-snippet-top">
                                        <h4 class="cx-snippet-title"><?php echo $snippet['title']; ?></h4>
                                        <span class="cx-tag tag-gray"><?php echo $snippet['language']; ?></span>
                                    </div>
                                    <p class="cx-snippet-desc"><?php echo $snippet['description']; ?></p>
                                    <div class="cx-snippet-meta">
                                        <i class="ph ph-hash"></i> <?php echo $snippet['line_count']; ?> dòng <span
                                            class="cx-dot">•</span> <?php echo $snippet['char_count']; ?> ký tự
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right Panel: Preview -->
                    <div class="cx-panel cx-preview" id="cxPreview">
                        <?php if (!empty($snippets)):
                            $first = $snippets[0]; ?>
                            <div class="cx-preview-header">
                                <div class="cx-preview-title-row">
                                    <div class="cx-preview-title">
                                        <h2 id="pTitle"><?php echo $first['title']; ?></h2>
                                        <span class="cx-tag tag-gray" id="pLang"><?php echo $first['language']; ?></span>
                                    </div>
                                    <div class="cx-preview-actions">
                                        <button class="btn-icon-nm"
                                            onclick="editSnippet(<?php echo htmlspecialchars(json_encode($first)); ?>)"><i
                                                class="ph ph-pencil-simple"></i></button>
                                        <button class="btn-icon-nm" onclick="deleteSnippet(<?php echo $first['id']; ?>)"><i
                                                class="ph ph-trash"></i></button>
                                    </div>
                                </div>
                                <p class="cx-preview-desc" id="pDesc"><?php echo $first['description']; ?></p>
                                <div class="cx-snippet-meta">
                                    <i class="ph ph-hash"></i> <span id="pLines"><?php echo $first['line_count']; ?></span>
                                    dòng <span class="cx-dot">•</span> <span
                                        id="pChars"><?php echo $first['char_count']; ?></span> ký tự
                                </div>
                            </div>

                            <div class="cx-preview-body">
                                <div class="cx-code-toolbar">
                                    <span class="cx-code-label"><i class="ph ph-code"></i> Code Preview</span>
                                    <button class="btn-dark" onclick="copySnippet()"><i class="ph ph-copy"></i> Copy
                                        Code</button>
                                </div>
                                <div class="cx-code-container">
                                    <pre><code id="pCode"><?php echo htmlspecialchars($first['code']); ?></code></pre>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="cx-empty">Chọn một snippet để xem nội dung</div>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Modal: Quản lý Danh mục Code -->
                <div id="codeCategoryModal" class="modal-overlay"
                    onclick="if(event.target.id==='codeCategoryModal') closeCodeCategoryModal()">
                    <div class="modal-box" style="max-width: 550px;">
                        <div class="modal-header-gradient">
                            <h3 class="modal-title-light"><i class="ph-fill ph-tag"></i> Quản lý Danh mục Code</h3>
                            <button class="modal-close-light" onclick="closeCodeCategoryModal()"><i
                                    class="ph ph-x"></i></button>
                        </div>

                        <div class="modal-body">
                            <!-- Chế độ Danh sách -->
                            <div id="codeCatListView">
                                <button class="btn-add-cat-dashed" onclick="showAddCodeCategoryForm()">
                                    <i class="ph ph-plus"></i> Thêm Danh Mục Mới
                                </button>

                                <div class="label-with-action" style="margin-top: 24px;">
                                    <span class="modal-label" style="font-size: 14px; font-weight: 700;">Danh Sách Danh
                                        Mục (<span id="codeCatCount">0</span>)</span>
                                </div>

                                <div class="cat-list" id="codeCatListContainer">
                                    <!-- Categories dynamic render -->
                                </div>
                            </div>

                            <!-- Chế độ Thêm/Sửa -->
                            <div id="codeCatFormView" style="display: none;">
                                <div class="modal-field full">
                                    <label class="modal-label">Tên Danh Mục</label>
                                    <input type="text" class="modal-input" id="codeCatNameInput"
                                        placeholder="VD: React, Vue, Python...">
                                </div>

                                <div class="modal-footer"
                                    style="padding: 16px 0 0 0; margin-top: 24px; border-top: 1px solid #f1f5f9;">
                                    <button type="button" class="modal-btn-cancel"
                                        onclick="showCodeCategoryList()">Hủy</button>
                                    <button type="button" class="modal-btn-submit" id="codeCatSubmitBtn"
                                        onclick="saveCodeCategory()">Thêm Mới</button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer" id="codeCatMainFooter">
                            <button class="modal-btn-submit" style="width: 100%; height: 48px; background: #2b7495;"
                                onclick="closeCodeCategoryModal()">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal: Thêm/Sửa Code Snippet -->
    <div id="cxModal" class="modal-overlay">
        <div class="modal-box cx-modal-box">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="cx-modal-icon">
                        <i class="ph ph-code-simple"></i>
                    </div>
                    <span>Thêm Code Mới</span>
                </div>
                <button class="modal-close" onclick="closeCxModal()">&times;</button>
            </div>
            <form id="cxForm">
                <input type="hidden" name="id" id="cxId">
                <input type="hidden" name="language" id="cxLangInput" value="JavaScript">
                <input type="hidden" name="line_count" id="cxLineCount" value="1">
                <input type="hidden" name="char_count" id="cxCharCount" value="0">

                <div class="modal-body">
                    <div class="modal-row">
                        <div class="modal-field">
                            <label class="modal-label"><i class="ph ph-notebook"></i> Tên Code <span
                                    class="req">*</span></label>
                            <input type="text" name="title" id="cxTitle" class="modal-input"
                                placeholder="VD: React useEffect Hook" required>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label"><i class="ph ph-tag"></i> Loại Code <span
                                    class="req">*</span></label>
                            <div class="pj-modal-select" data-input-id="cxLangInput" data-callback="onCxLangSelect"
                                id="cxLangSelect">
                                <div class="cx-badge-select-trigger pj-modal-select-trigger">
                                    <span class="cx-lang-badge" id="cxLangBadge">JavaScript</span>
                                    <i class="ph ph-caret-down"></i>
                                </div>
                                <div class="pj-dropdown">
                                    <div class="pj-dropdown-list" id="cxLangDropdownList">
                                        <?php foreach ($categories as $cat): ?>
                                            <div class="pj-dropdown-item <?php echo $cat['name'] == 'JavaScript' ? 'active' : ''; ?>"
                                                data-value="<?php echo $cat['name']; ?>">
                                                <span><?php echo $cat['name']; ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="pj-dropdown-divider"></div>
                                    <div class="cx-add-lang-wrapper" onclick="event.stopPropagation()">
                                        <button type="button" class="cx-show-add-btn" id="cxShowAddBtn"
                                            onclick="showAddLangInput(event)">
                                            <i class="ph ph-plus-circle"></i> Thêm loại mới...
                                        </button>
                                        <div class="cx-add-lang-input-group" id="cxAddLangInputGroup"
                                            style="display: none;">
                                            <input type="text" id="newLangInput" placeholder="Tên loại mới..."
                                                onkeyup="handleNewLangKey(event)">
                                            <div class="cx-add-lang-actions">
                                                <button type="button" class="cx-add-btn-small"
                                                    onclick="saveNewLang(event)">
                                                    <i class="ph ph-check"></i>
                                                </button>
                                                <button type="button" class="cx-cancel-btn-small"
                                                    onclick="hideAddLangInput(event)">
                                                    <i class="ph ph-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-text-align-left"></i> Mô Tả</label>
                        <input type="text" name="description" id="cxDesc" class="modal-input"
                            placeholder="VD: Hook để xử lý side effects trong React">
                    </div>
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-code"></i> Nội Dung Code <span
                                class="req">*</span></label>
                        <div class="cx-code-editor-wrapper">
                            <textarea name="code" id="cxCodeArea" class="cx-code-textarea"
                                placeholder="// Nhập code của bạn tại đây..." oninput="updateStats()"
                                required></textarea>
                            <div class="cx-code-status-bar">
                                <span id="statLines">1 dòng</span>
                                <span class="cx-dot">•</span>
                                <span id="statChars">0 ký tự</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cx-cancel" onclick="closeCxModal()">Hủy</button>
                    <button type="submit" class="btn-cx-submit">Thêm Mới</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Data from PHP
        let CODE_CATEGORIES = <?php echo json_encode($categories); ?>;
        const ALL_SNIPPETS = <?php echo json_encode($snippets); ?>;

        // Modal Snippet Variables
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


        function showCxToast(message, type = 'loading') {
            const toast = document.getElementById('cxToast');
            const msg = document.getElementById('cxToastMsg');
            const spinner = document.getElementById('cxToastSpinner');
            const successIcon = document.getElementById('cxToastSuccessIcon');
            const errorIcon = document.getElementById('cxToastErrorIcon');

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

        let pendingDeleteAction = null;

        function openCxConfirmDelete(title, message, confirmCallback) {
            document.getElementById('cxConfirmDeleteTitle').textContent = title;
            document.getElementById('cxConfirmDeleteMsg').innerHTML = message;
            document.getElementById('cxConfirmDeleteModal').classList.add('active');
            
            const btn = document.getElementById('cxConfirmDeleteBtn');
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            newBtn.onclick = () => {
                closeCxConfirmDelete();
                confirmCallback();
            };
        }

        function closeCxConfirmDelete() {
            document.getElementById('cxConfirmDeleteModal').classList.remove('active');
        }

        // --- Modal Snippet Functions ---
        function openCxModal() {
            document.getElementById('cxId').value = '';
            document.getElementById('cxForm').reset();
            document.querySelector('#cxModal .modal-title span').textContent = 'Thêm Code Mới';
            document.querySelector('#cxModal .btn-cx-submit').textContent = 'Thêm Mới';
            document.getElementById('cxLangBadge').textContent = 'Chọn danh mục';
            document.getElementById('cxLangInput').value = '';

            // Sync dropdown with latest categories
            renderCodeCategories();

            document.getElementById('cxModal').classList.add('active');
            updateStats();
        }

        function closeCxModal() {
            document.getElementById('cxModal').classList.remove('active');
        }

        function updateStats() {
            const code = document.getElementById('cxCodeArea').value;
            const lines = code ? code.split('\n').length : 0;
            const chars = code.length;

            document.getElementById('statLines').textContent = lines + ' dòng';
            document.getElementById('statChars').textContent = chars + ' ký tự';
            document.getElementById('cxLineCount').value = lines;
            document.getElementById('cxCharCount').value = chars;
        }

        // --- Category Management Functions ---
        let currentCatEditId = null;
        const CODE_COLOR_PRESETS = [
            '#2fab91', '#2563eb', '#ef4444', '#f59e0b', '#f97316', '#059669',
            '#38bdf8', '#6366f1', '#8b5cf6', '#d946ef', '#ec4899', '#475569'
        ];

        function openCodeCategoryModal() {
            document.getElementById('codeCategoryModal').classList.add('active');
            document.body.style.overflow = 'hidden';
            showCodeCategoryList();
        }

        function closeCodeCategoryModal() {
            document.getElementById('codeCategoryModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function showCodeCategoryList() {
            document.getElementById('codeCatListView').style.display = 'block';
            document.getElementById('codeCatFormView').style.display = 'none';
            document.getElementById('codeCatMainFooter').style.display = 'block';
            renderCodeCategories();
        }

        function showAddCodeCategoryForm(id = null) {
            currentCatEditId = id;
            document.getElementById('codeCatListView').style.display = 'none';
            document.getElementById('codeCatFormView').style.display = 'block';
            document.getElementById('codeCatMainFooter').style.display = 'none';

            if (id) {
                const cat = CODE_CATEGORIES.find(c => c.id == id);
                document.getElementById('codeCatNameInput').value = cat.name;
                document.getElementById('codeCatSubmitBtn').textContent = 'Cập Nhật';
            } else {
                document.getElementById('codeCatNameInput').value = '';
                document.getElementById('codeCatSubmitBtn').textContent = 'Thêm Mới';
            }
        }

        function renderCodeCategories() {
            const container = document.getElementById('codeCatListContainer');
            document.getElementById('codeCatCount').textContent = CODE_CATEGORIES.length;

            container.innerHTML = CODE_CATEGORIES.map(c => `
        <div class="cat-item">
            <div class="cat-info">
                <i class="ph ph-tag" style="color: #64748b; font-size: 18px;"></i>
                <div class="cat-name">${c.name}</div>
            </div>
            <div class="cat-actions" style="display: flex; gap: 8px;">
                <button class="btn-icon-sm" onclick="showAddCodeCategoryForm(${c.id})"><i class="ph ph-pencil-simple"></i></button>
                <button class="btn-icon-sm" style="color: #ef4444;" onclick="deleteCodeCategory(${c.id})"><i class="ph ph-trash"></i></button>
            </div>
        </div>
    `).join('');

            // Update Sidebar Nav and Dropdowns
            const list = document.getElementById('cxLangDropdownList');
            if (list) {
                const currentVal = document.getElementById('cxLangInput').value;
                list.innerHTML = CODE_CATEGORIES.map(c => `
            <div class="pj-dropdown-item ${c.name === currentVal ? 'active' : ''}" data-value="${c.name}">
                <span>${c.name}</span>
            </div>
        `).join('');
            }

            updateSidebarNav();
        }

        function updateSidebarNav() {
            const sidebarNav = document.getElementById('cxSidebarNav');
            const totalCount = ALL_SNIPPETS.length;

            let html = `
        <div class="cx-nav-item active" onclick="filterByLang('all')">
            <span>Tất cả ngôn ngữ</span>
            <div class="cx-badge-right">
                <span class="cx-count" id="totalSnippetCount">${totalCount}</span>
                <i class="ph ph-caret-right"></i>
            </div>
        </div>
    `;

            CODE_CATEGORIES.forEach(cat => {
                const count = ALL_SNIPPETS.filter(s => s.language === cat.name).length;
                html += `
            <div class="cx-nav-item" data-lang-name="${cat.name}" onclick="filterByLang('${cat.name}')">
                <span>${cat.name}</span>
                <span class="cx-count">${count}</span>
            </div>
        `;
            });

            if (sidebarNav) sidebarNav.innerHTML = html;
        }

        async function saveCodeCategory() {
            const nameInput = document.getElementById('codeCatNameInput');
            const data = {
                id: currentCatEditId,
                name: nameInput.value.trim()
            };

            clearErrors();
            if (!data.name) {
                showCxToast('Vui lòng nhập tên danh mục', 'error');
                markError('codeCatNameInput');
                return;
            }

            // Kiểm tra trùng lặp local
            const isDuplicate = CODE_CATEGORIES.some(c => 
                c.name.toLowerCase() === data.name.toLowerCase() && c.id != data.id
            );
            if (isDuplicate) {
                showCxToast('Tên danh mục này đã tồn tại', 'error');
                return;
            }

            const modal = document.getElementById('codeCategoryModal');
            modal.classList.add('loading');
            showCxToast(currentCatEditId ? 'Đang cập nhật danh mục...' : 'Đang thêm danh mục...', 'loading');

            try {
                const response = await fetch('/codex/categories/save', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                modal.classList.remove('loading');
                if (result.status === 'success' || result.success) {
                    const savedData = result.data || result; // Fallback if flattened
                    if (currentCatEditId) {
                        const idx = CODE_CATEGORIES.findIndex(c => c.id == currentCatEditId);
                        if (idx !== -1) CODE_CATEGORIES[idx] = savedData;
                    } else {
                        CODE_CATEGORIES.push(savedData);
                    }
                    showCxToast(currentCatEditId ? 'Đã cập nhật danh mục!' : 'Đã thêm danh mục thành công!', 'success');
                    showCodeCategoryList();
                } else {
                    showCxToast(result.message || 'Lỗi khi lưu danh mục', 'error');
                }
            } catch (e) { 
                modal.classList.remove('loading');
                console.error(e); 
                showCxToast('Lỗi kết nối máy chủ', 'error');
            }
        }

        async function deleteCodeCategory(id) {
            const cat = CODE_CATEGORIES.find(c => c.id == id);
            if (!cat) return;

            openCxConfirmDelete(
                'Xác nhận xóa danh mục',
                `Bạn có chắc chắn muốn xóa danh mục "<strong>${cat.name}</strong>"? Toàn bộ snippet thuộc danh mục này sẽ bị ảnh hưởng.`,
                async () => {
                    showCxToast('Đang xóa danh mục...', 'loading');
                    try {
                        const response = await fetch('/codex/categories/delete', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id })
                        });
                        const result = await response.json();
                        if (result.status === 'success' || result.success) {
                            CODE_CATEGORIES = CODE_CATEGORIES.filter(c => c.id != id);
                            showCxToast('Đã xóa danh mục thành công!', 'success');
                            renderCodeCategories();
                        } else {
                            showCxToast(result.message || 'Không thể xóa danh mục này', 'error');
                        }
                    } catch (e) { 
                        console.error(e); 
                        showCxToast('Lỗi kết nối máy chủ', 'error');
                    }
                }
            );
        }


        // --- Snippet Logic ---
        document.getElementById('cxForm').onsubmit = async function (e) {
            e.preventDefault();
            // Validation
            const title = document.getElementById('cxTitle').value.trim();
            const lang = document.getElementById('cxLangInput').value;
            const code = document.getElementById('cxCodeArea').value.trim();

            clearErrors();
            if (!title) { showCxToast('Vui lòng nhập tên code!', 'error'); markError('cxTitle'); return; }
            if (!lang || lang === 'Chọn danh mục') { showCxToast('Vui lòng chọn loại code!', 'error'); markError('cxLangSelect', true); return; }
            if (!code) { showCxToast('Vui lòng nhập nội dung code!', 'error'); markError('cxCodeArea'); return; }

            // Hiển thị loading toast & overlay
            const modal = document.getElementById('cxModal');
            modal.classList.add('loading');
            showCxToast(document.getElementById('cxId').value ? 'Đang cập nhật snippet...' : 'Đang thêm snippet...', 'loading');

            const formData = new FormData(this);
            fetch('/codex/save', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                modal.classList.remove('loading');
                if (data.status === 'success' || data.success) {
                    showCxToast('Thành công!', 'success');
                    setTimeout(() => location.reload(), 500);
                } else {
                    showCxToast(data.message || 'Lỗi khi lưu snippet', 'error');
                }
            })
            .catch(err => {
                modal.classList.remove('loading');
                console.error(err);
                showCxToast('Lỗi kết nối máy chủ', 'error');
            });
        };

        function selectSnippet(snippet, element) {
            document.querySelectorAll('.cx-snippet-item').forEach(i => i.classList.remove('active'));
            element.classList.add('active');

            document.getElementById('pTitle').textContent = snippet.title;
            document.getElementById('pLang').textContent = snippet.language;
            document.getElementById('pDesc').textContent = snippet.description;
            document.getElementById('pLines').textContent = snippet.line_count;
            document.getElementById('pChars').textContent = snippet.char_count;
            document.getElementById('pCode').textContent = snippet.code;

            const actionContainer = document.querySelector('.cx-preview-actions');
            actionContainer.innerHTML = `
        <button class="btn-icon-nm" onclick='editSnippet(${JSON.stringify(snippet)})'><i class="ph ph-pencil-simple"></i></button>
        <button class="btn-icon-nm" onclick="deleteSnippet(${snippet.id})"><i class="ph ph-trash"></i></button>
    `;
        }

        function editSnippet(snippet) {
            document.getElementById('cxId').value = snippet.id;
            document.getElementById('cxTitle').value = snippet.title;
            document.getElementById('cxDesc').value = snippet.description;
            document.getElementById('cxCodeArea').value = snippet.code;
            document.getElementById('cxLangInput').value = snippet.language;
            document.getElementById('cxLangBadge').textContent = snippet.language;

            document.getElementById('cxLangBadge').style.backgroundColor = '#f1f5f9';
            document.getElementById('cxLangBadge').style.color = '#64748b';

            document.querySelector('#cxModal .modal-title span').textContent = 'Chỉnh sửa Snippet';
            document.querySelector('#cxModal .btn-cx-submit').textContent = 'Cập nhật';
            document.getElementById('cxModal').classList.add('active');
            updateStats();
        }

        function deleteSnippet(id) {
            const snippet = ALL_SNIPPETS.find(s => s.id == id);
            const title = snippet ? snippet.title : 'mục này';

            openCxConfirmDelete(
                'Xác nhận xóa snippet',
                `Bạn có chắc chắn muốn xóa snippet "<strong>${title}</strong>"? Toàn bộ dữ liệu của mục này sẽ bị xóa khỏi hệ thống.`,
                async () => {
                    showCxToast('Đang xóa snippet...', 'loading');
                    const formData = new FormData();
                    formData.append('id', id);

                    fetch('/codex/delete', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success' || data.success) {
                            showCxToast('Đã xóa thành công!', 'success');
                            setTimeout(() => location.reload(), 500);
                        } else {
                            showCxToast(data.message || 'Lỗi khi xóa snippet', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showCxToast('Lỗi kết nối máy chủ', 'error');
                    });
                }
            );
        }

        function filterByLang(lang) {
            const items = document.querySelectorAll('.cx-snippet-item');
            let visible = 0;
            items.forEach(item => {
                if (lang === 'all' || item.dataset.lang === lang) {
                    item.style.display = 'block';
                    visible++;
                } else {
                    item.style.display = 'none';
                }
            });
            document.getElementById('visibleCount').textContent = visible + ' snippets';

            document.querySelectorAll('.cx-nav-item').forEach(nav => {
                const langName = nav.getAttribute('data-lang-name');
                if (lang === 'all') {
                    if (!langName) nav.classList.add('active');
                    else nav.classList.remove('active');
                } else {
                    if (langName === lang) nav.classList.add('active');
                    else nav.classList.remove('active');
                }
            });
        }

        function searchSnippets() {
            const query = document.getElementById('cxSearchInput').value.toLowerCase();
            const items = document.querySelectorAll('.cx-snippet-item');
            let visible = 0;
            items.forEach(item => {
                const title = item.dataset.title;
                if (title.includes(query)) {
                    item.style.display = 'block';
                    visible++;
                } else {
                    item.style.display = 'none';
                }
            });
            document.getElementById('visibleCount').textContent = visible + ' snippets';
        }

        function copySnippet() {
            const code = document.getElementById('pCode').textContent;
            navigator.clipboard.writeText(code).then(() => {
                showCxToast('Đã copy code vào clipboard!', 'success');
            });
        }

        // --- Snippet Logic ---

        function showAddLangInput(e) {
            e.stopPropagation();
            document.getElementById('cxShowAddBtn').style.display = 'none';
            document.getElementById('cxAddLangInputGroup').style.display = 'flex';
            document.getElementById('newLangInput').focus();
        }

        function hideAddLangInput(e) {
            if (e) e.stopPropagation();
            document.getElementById('cxShowAddBtn').style.display = 'flex';
            document.getElementById('cxAddLangInputGroup').style.display = 'none';
            document.getElementById('newLangInput').value = '';
        }

        function handleNewLangKey(e) {
            if (e.key === 'Enter') saveNewLang(e);
            if (e.key === 'Escape') hideAddLangInput(e);
        }

        async function saveNewLang(e) {
            e.stopPropagation();
            const name = document.getElementById('newLangInput').value.trim();
            if (!name) {
                showCxToast('Vui lòng nhập tên loại code', 'error');
                return;
            }

            // Kiểm tra trùng lặp local
            const isDuplicate = CODE_CATEGORIES.some(c => c.name.toLowerCase() === name.toLowerCase());
            if (isDuplicate) {
                showCxToast('Tên loại code này đã tồn tại', 'error');
                return;
            }

            showCxToast('Đang thêm loại mới...', 'loading');

            try {
                const response = await fetch('/codex/categories/save', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name: name })
                });
                const data = await response.json();

                if (data.status === 'success' || data.success) {
                    showCxToast('Đã thêm loại code!', 'success');
                    const savedData = data.data || data; // Handle nested 'data' from MainController
                    if (!data.exists) {
                        CODE_CATEGORIES.push(savedData);
                        renderCodeCategories();
                    }
                    document.getElementById('cxLangInput').value = savedData.name;
                    document.getElementById('cxLangBadge').textContent = savedData.name;
                    const badge = document.getElementById('cxLangBadge');
                    badge.style.backgroundColor = '#f1f5f9';
                    badge.style.color = '#64748b';

                    hideAddLangInput();
                    document.getElementById('cxLangSelect').classList.remove('open');
                } else {
                    showCxToast(data.message || 'Lỗi khi thêm loại code', 'error');
                }
            } catch (err) {
                console.error('Error:', err);
                showCxToast('Lỗi kết nối máy chủ', 'error');
            }
        }
        // Callback khi chọn loại code từ dropdown
        function onCxLangSelect(value, label, option) {
            const badge = document.getElementById('cxLangBadge');
            if (!badge || !value) return;

            // Tìm danh mục từ dữ liệu CODE_CATEGORIES
            const cat = CODE_CATEGORIES.find(c => c.name === value);
            if (cat) {
                // Cập nhật giá trị input language ẩn
                document.getElementById('cxLangInput').value = cat.name;
            }

            // Reset badge style (neutral)
            badge.style.backgroundColor = '#f1f5f9';
            badge.style.color = '#64748b';
        }

        // Khởi tạo trang: đồng bộ hóa danh sách từ PHP khi DOM sẵn sàng
        document.addEventListener('DOMContentLoaded', () => {
            renderCodeCategories();
        });
    </script>
    <div id="cxToast" class="toast">
        <div class="toast-content">
            <div id="cxToastSpinner" class="spinner"></div>
            <i id="cxToastSuccessIcon" class="ph-fill ph-check-circle" style="display:none; color: #10b981; font-size: 24px;"></i>
            <i id="cxToastErrorIcon" class="ph-fill ph-x-circle" style="display:none; color: #ef4444; font-size: 24px;"></i>
            <span id="cxToastMsg">Đang xử lý...</span>
        </div>
    </div>
    <!-- Modal Xác nhận Xóa -->
    <div class="modal-overlay" id="cxConfirmDeleteModal" onclick="if(event.target.id==='cxConfirmDeleteModal') closeCxConfirmDelete()">
        <div class="modal-box danger-modal" style="max-width: 420px; padding: 24px; border-radius: 16px; position: relative;">
            <button class="modal-close" style="position: absolute; right: 20px; top: 20px;" onclick="closeCxConfirmDelete()"><i class="ph ph-x"></i></button>
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                <div style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px; font-size: 24px; display: flex; align-items: center; justify-content: center;">
                    <i class="ph-fill ph-warning"></i>
                </div>
                <div>
                    <h3 id="cxConfirmDeleteTitle" style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">Xác nhận xóa</h3>
                    <p style="margin: 4px 0 0 0; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">HÀNH ĐỘNG KHÔNG THỂ HOÀN TÁC</p>
                </div>
            </div>
            <p id="cxConfirmDeleteMsg" style="margin: 0 0 28px 0; color: #475569; font-size: 14px; line-height: 1.6;">
                Bạn có chắc chắn muốn xóa mục này? Toàn bộ dữ liệu sẽ bị xóa khỏi hệ thống.
            </p>
            <div style="display: flex; gap: 12px;">
                <button class="modal-btn-cancel" style="flex: 1; justify-content: center; height: 44px; border-radius: 10px; font-weight: 600;" onclick="closeCxConfirmDelete()">Hủy Bỏ</button>
                <button class="btn-danger-ok" id="cxConfirmDeleteBtn">Xác Nhận Xóa</button>
            </div>
        </div>
    </div>
</body>

</html>