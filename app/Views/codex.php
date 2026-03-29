<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeX - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
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
                            <button class="btn-primary btn-block" onclick="openCxModal()"><i class="ph ph-plus"></i> Thêm Snippet</button>
                        </div>
                        <div class="cx-sidebar-nav" id="cxSidebarNav">
                            <div class="cx-nav-item active" onclick="filterByLang('all')">
                                <span>Tất cả ngôn ngữ</span>
                                <div class="cx-badge-right">
                                    <span class="cx-count" id="totalSnippetCount"><?php echo count($snippets); ?></span>
                                    <i class="ph ph-caret-right"></i>
                                </div>
                            </div>
                            <?php foreach($categories as $cat): 
                                $langCount = count(array_filter($snippets, function($s) use ($cat) { return $s['language'] == $cat['name']; }));
                            ?>
                            <div class="cx-nav-item" data-lang-name="<?php echo $cat['name']; ?>" onclick="filterByLang('<?php echo $cat['name']; ?>')">
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
                                <input type="text" id="cxSearchInput" placeholder="Tìm kiếm snippet..." oninput="searchSnippets()">
                            </div>
                            <p class="cx-subtitle" id="visibleCount"><?php echo count($snippets); ?> snippets</p>
                        </div>
                        <div class="cx-list-body" id="cxListBody">
                            <?php foreach($snippets as $index => $snippet): ?>
                            <div class="cx-snippet-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                                 onclick="selectSnippet(<?php echo htmlspecialchars(json_encode($snippet)); ?>, this)"
                                 data-lang="<?php echo $snippet['language']; ?>"
                                 data-title="<?php echo strtolower($snippet['title']); ?>">
                                <div class="cx-snippet-top">
                                    <h4 class="cx-snippet-title"><?php echo $snippet['title']; ?></h4>
                                    <span class="cx-tag <?php 
                                        echo match($snippet['language']) {
                                            'PHP' => 'tag-blue',
                                            'CSS' => 'tag-purple',
                                            'JS', 'JavaScript' => 'tag-yellow',
                                            default => 'tag-gray'
                                        };
                                    ?>"><?php echo $snippet['language']; ?></span>
                                </div>
                                <p class="cx-snippet-desc"><?php echo $snippet['description']; ?></p>
                                <div class="cx-snippet-meta">
                                    <i class="ph ph-hash"></i> <?php echo $snippet['line_count']; ?> dòng <span class="cx-dot">•</span> <?php echo $snippet['char_count']; ?> ký tự
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right Panel: Preview -->
                    <div class="cx-panel cx-preview" id="cxPreview">
                        <?php if(!empty($snippets)): $first = $snippets[0]; ?>
                        <div class="cx-preview-header">
                            <div class="cx-preview-title-row">
                                <div class="cx-preview-title">
                                    <h2 id="pTitle"><?php echo $first['title']; ?></h2>
                                    <span class="cx-tag tag-gray" id="pLang"><?php echo $first['language']; ?></span>
                                </div>
                                <div class="cx-preview-actions">
                                    <button class="btn-icon-nm" onclick="editSnippet(<?php echo htmlspecialchars(json_encode($first)); ?>)"><i class="ph ph-pencil-simple"></i></button>
                                    <button class="btn-icon-nm" onclick="deleteSnippet(<?php echo $first['id']; ?>)"><i class="ph ph-trash"></i></button>
                                </div>
                            </div>
                            <p class="cx-preview-desc" id="pDesc"><?php echo $first['description']; ?></p>
                            <div class="cx-snippet-meta">
                                <i class="ph ph-hash"></i> <span id="pLines"><?php echo $first['line_count']; ?></span> dòng <span class="cx-dot">•</span> <span id="pChars"><?php echo $first['char_count']; ?></span> ký tự
                            </div>
                        </div>

                        <div class="cx-preview-body">
                            <div class="cx-code-toolbar">
                                <span class="cx-code-label"><i class="ph ph-code"></i> Code Preview</span>
                                <button class="btn-dark" onclick="copySnippet()"><i class="ph ph-copy"></i> Copy Code</button>
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
                            <label class="modal-label"><i class="ph ph-notebook"></i> Tên Code <span class="req">*</span></label>
                            <input type="text" name="title" id="cxTitle" class="modal-input" placeholder="VD: React useEffect Hook" required>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label"><i class="ph ph-tag"></i> Loại Code <span class="req">*</span></label>
                            <div class="pj-modal-select" data-input-id="cxLangInput" id="cxLangSelect">
                                <div class="cx-badge-select-trigger pj-modal-select-trigger">
                                    <span class="cx-lang-badge" id="cxLangBadge">JavaScript</span>
                                    <i class="ph ph-caret-down"></i>
                                </div>
                                <div class="pj-dropdown">
                                    <div class="pj-dropdown-list" id="cxLangDropdownList">
                                        <?php foreach($categories as $cat): ?>
                                        <div class="pj-dropdown-item <?php echo $cat['name'] == 'JavaScript' ? 'active' : ''; ?>" data-value="<?php echo $cat['name']; ?>">
                                            <span><?php echo $cat['name']; ?></span>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="pj-dropdown-divider"></div>
                                    <div class="cx-add-lang-wrapper" onclick="event.stopPropagation()">
                                        <button type="button" class="cx-show-add-btn" id="cxShowAddBtn" onclick="openCodeCategoryModal(); document.getElementById('cxLangSelect').classList.remove('active');">
                                            <i class="ph ph-gear"></i> Quản lý danh mục...
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-text-align-left"></i> Mô Tả</label>
                        <input type="text" name="description" id="cxDesc" class="modal-input" placeholder="VD: Hook để xử lý side effects trong React">
                    </div>
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-code"></i> Nội Dung Code <span class="req">*</span></label>
                        <div class="cx-code-editor-wrapper">
                            <textarea name="code" id="cxCodeArea" class="cx-code-textarea" placeholder="// Nhập code của bạn tại đây..." oninput="updateStats()" required></textarea>
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

    <!-- Modal Quản Lý Danh Mục (CodeX) -->
    <div class="modal-overlay" id="codeCategoryModal" onclick="if(event.target.id==='codeCategoryModal') closeCodeCategoryModal()">
        <div class="modal-box" style="max-width: 550px;">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <div class="modal-icon-brand" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="ph-fill ph-tag"></i>
                    </div>
                    <h3 class="modal-title">Quản Lý Danh Mục Code</h3>
                </div>
                <button class="modal-close" onclick="closeCodeCategoryModal()"><i class="ph ph-x"></i></button>
            </div>
            
            <div class="modal-body">
                <!-- Chế độ Danh sách -->
                <div id="codeCatListView">
                    <button class="btn-add-cat-dashed" onclick="showAddCodeCategoryForm()">
                        <i class="ph ph-plus"></i> Thêm Loại Code Mới
                    </button>
                    
                    <div class="label-with-action" style="margin-top: 24px;">
                        <span class="modal-label" style="font-size: 14px; font-weight: 700;">Danh Sách Loại Code (<span id="codeCatCount">0</span>)</span>
                    </div>
                    
                    <div class="cat-list" id="codeCatListContainer">
                        <!-- Categories dynamic render -->
                    </div>
                </div>

                <!-- Chế độ Thêm/Sửa -->
                <div id="codeCatFormView" style="display: none;">
                    <div class="cat-preview-section">
                        <span class="cat-preview-label">Xem Trước</span>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span id="codeCatPreviewTag" class="cat-tag-preview" style="padding: 6px 12px; border-radius: 6px; font-weight: 600;">Tên loại code</span>
                        </div>
                    </div>

                    <div class="modal-field full">
                        <label class="modal-label">Tên Loại Code</label>
                        <input type="text" class="modal-input" id="codeCatNameInput" placeholder="VD: React, Python, Flutter..." oninput="updateCodeCatPreview()">
                    </div>

                    <div class="modal-field full">
                        <label class="modal-label">Màu Nền (Background)</label>
                        <div class="hex-input-wrapper">
                            <div class="color-dot" id="codeCatBgColorDot" style="background: #fef9c3;"></div>
                            <input type="text" class="hex-input" id="codeCatBgHexInput" value="#fef9c3" oninput="updateCodeCatPreview()">
                        </div>
                    </div>

                    <div class="modal-field full">
                        <label class="modal-label">Màu Chữ (Text)</label>
                        <div class="hex-input-wrapper">
                            <div class="color-dot" id="codeCatTextColorDot" style="background: #854d0e;"></div>
                            <input type="text" class="hex-input" id="codeCatTextHexInput" value="#854d0e" oninput="updateCodeCatPreview()">
                        </div>
                    </div>

                    <div class="modal-label" style="margin-top: 16px;">Gợi ý màu sắc:</div>
                    <div class="color-presets" id="codeCatColorPresets">
                        <!-- Presets generated via JS -->
                    </div>

                    <div class="modal-footer" style="padding: 16px 0 0 0; margin-top: 24px; border-top: 1px solid #f1f5f9;">
                        <button type="button" class="modal-btn-cancel" onclick="showCodeCategoryList()">Hủy</button>
                        <button type="button" class="modal-btn-submit" id="codeCatSubmitBtn" onclick="saveCodeCategory()">Thêm Mới</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer" id="codeCatMainFooter">
                <button class="modal-btn-submit" style="width: 100%; height: 48px;" onclick="closeCodeCategoryModal()">Đóng</button>
            </div>
        </div>
    </div>

<script>
    // Data injected from PHP Models
    let SNIPPETS = <?php echo json_encode($snippets ?? []); ?>;
    let CODE_CATEGORIES = <?php echo json_encode($categories ?? []); ?>;
    let currentCodeCatEditId = null;
function openCxModal() {
    document.getElementById('cxId').value = '';
    document.getElementById('cxForm').reset();
    document.querySelector('#cxModal .modal-title span').textContent = 'Thêm Code Mới';
    document.querySelector('#cxModal .btn-cx-submit').textContent = 'Thêm Mới';
    document.getElementById('cxLangBadge').textContent = 'JavaScript';
    document.getElementById('cxLangInput').value = 'JavaScript';
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

// Custom select handle for badge
document.addEventListener('change', function(e) {
    if (e.target.id === 'cxLangInput') {
        document.getElementById('cxLangBadge').textContent = e.target.value;
    }
});

document.getElementById('cxForm').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('/codex/save', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert(data.message);
        }
    });
};

function selectSnippet(snippet, element) {
    // UI update for list
    document.querySelectorAll('.cx-snippet-item').forEach(i => i.classList.remove('active'));
    element.classList.add('active');
    
    // UI update for preview
    document.getElementById('pTitle').textContent = snippet.title;
    document.getElementById('pLang').textContent = snippet.language;
    document.getElementById('pDesc').textContent = snippet.description;
    document.getElementById('pLines').textContent = snippet.line_count;
    document.getElementById('pChars').textContent = snippet.char_count;
    document.getElementById('pCode').textContent = snippet.code;
    
    // Update preview buttons to use current snippet data
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
    
    document.querySelector('#cxModal .modal-title span').textContent = 'Chỉnh sửa Snippet';
    document.querySelector('#cxModal .btn-cx-submit').textContent = 'Cập nhật';
    document.getElementById('cxModal').classList.add('active');
    updateStats();
}

function deleteSnippet(id) {
    if (confirm('Bạn có chắc chắn muốn xoá snippet này?')) {
        const formData = new FormData();
        formData.append('id', id);
        
        fetch('/codex/delete', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
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
    
    // Update active nav
    document.querySelectorAll('.cx-nav-item').forEach(nav => {
        const langName = nav.getAttribute('data-lang-name');
        if (lang === 'all') {
            if (nav.classList.contains('active') && !langName) return; // Already "All"
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

// Category Management Functions
document.addEventListener('click', function(e) {
    const isSelect = e.target.closest('.pj-modal-select');
    const allSelects = document.querySelectorAll('.pj-modal-select');
    
    if (isSelect) {
        const trigger = e.target.closest('.pj-modal-select-trigger');
        if (trigger) {
            const currentActive = isSelect.classList.contains('active');
            allSelects.forEach(s => s.classList.remove('active'));
            if (!currentActive) isSelect.classList.add('active');
        }
        
        const item = e.target.closest('.pj-dropdown-item');
        if (item) {
            const value = item.dataset.value;
            const select = item.closest('.pj-modal-select');
            const inputId = select.dataset.inputId;
            const input = document.getElementById(inputId);
            const badge = select.querySelector('.cx-lang-badge');
            
            if (input) input.value = value;
            if (badge) badge.textContent = value;
            
            // Toggle active class on items
            select.querySelectorAll('.pj-dropdown-item').forEach(i => i.classList.remove('active'));
            item.classList.add('active');
            
            select.classList.remove('active');
        }
    } else {
        allSelects.forEach(s => s.classList.remove('active'));
    }
});

// Category Management Functions (CodeX)
function openCodeCategoryModal() {
    showCodeCategoryList();
    document.getElementById('codeCategoryModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeCodeCategoryModal() {
    document.getElementById('codeCategoryModal').classList.remove('active');
    document.body.style.overflow = '';
}

function showCodeCategoryList() {
    document.getElementById('codeCatListView').style.display = 'block';
    document.getElementById('codeCatFormView').style.display = 'none';
    document.getElementById('codeCatMainFooter').style.display = 'block';
    renderCodeCategories();
}

function showAddCodeCategoryForm(id = null) {
    currentCodeCatEditId = id;
    document.getElementById('codeCatListView').style.display = 'none';
    document.getElementById('codeCatFormView').style.display = 'block';
    document.getElementById('codeCatMainFooter').style.display = 'none';
    
    if (id) {
        const cat = CODE_CATEGORIES.find(c => c.id == id);
        document.getElementById('codeCatNameInput').value = cat.name;
        document.getElementById('codeCatBgHexInput').value = cat.color;
        document.getElementById('codeCatTextHexInput').value = cat.text_color;
        document.getElementById('codeCatSubmitBtn').textContent = 'Cập Nhật';
    } else {
        document.getElementById('codeCatNameInput').value = '';
        document.getElementById('codeCatBgHexInput').value = '#fef9c3';
        document.getElementById('codeCatTextHexInput').value = '#854d0e';
        document.getElementById('codeCatSubmitBtn').textContent = 'Thêm Mới';
    }
    
    renderCodeColorPresets();
    updateCodeCatPreview();
}

function updateCodeCatPreview() {
    const name = document.getElementById('codeCatNameInput').value || 'Tên loại code';
    const bg = document.getElementById('codeCatBgHexInput').value;
    const text = document.getElementById('codeCatTextHexInput').value;
    
    const preview = document.getElementById('codeCatPreviewTag');
    preview.textContent = name;
    preview.style.background = bg;
    preview.style.color = text;
    
    document.getElementById('codeCatBgColorDot').style.background = bg;
    document.getElementById('codeCatTextColorDot').style.background = text;
}

function renderCodeColorPresets() {
    const presets = [
        {bg: '#fef9c3', text: '#854d0e'}, // Yellow
        {bg: '#eff6ff', text: '#1e40af'}, // Blue
        {bg: '#f5f3ff', text: '#5b21b6'}, // Purple
        {bg: '#fff7ed', text: '#9a3412'}, // Orange
        {bg: '#ecfdf5', text: '#065f46'}, // Green
        {bg: '#fdf2f8', text: '#9d174d'}, // Pink
        {bg: '#f1f5f9', text: '#475569'}, // Gray
        {bg: '#fff1f2', text: '#9f1239'}, // Red
    ];
    
    const container = document.getElementById('codeCatColorPresets');
    container.innerHTML = presets.map(p => `
        <div class="color-preset-item" style="background: ${p.bg}; color: ${p.text}; border: 1px solid ${p.text}20;" 
             onclick="applyCodeColorPreset('${p.bg}', '${p.text}')">Aa</div>
    `).join('');
}

function applyCodeColorPreset(bg, text) {
    document.getElementById('codeCatBgHexInput').value = bg;
    document.getElementById('codeCatTextHexInput').value = text;
    updateCodeCatPreview();
}

async function saveCodeCategory() {
    const data = {
        id: currentCodeCatEditId,
        name: document.getElementById('codeCatNameInput').value,
        color: document.getElementById('codeCatBgHexInput').value,
        text_color: document.getElementById('codeCatTextHexInput').value
    };

    if (!data.name) return alert('Vui lòng nhập tên loại code');

    try {
        const response = await fetch('/codex/categories/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            // Hard reload for now to ensure all UI components sync correctly
            location.reload();
        } else {
            alert(result.message || 'Không thể lưu danh mục');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function deleteCodeCategory(id) {
    if (!confirm('Bạn có chắc muốn xóa loại code này? Các snippet thuộc loại này sẽ không bị xóa.')) return;
    
    try {
        const response = await fetch('/codex/categories/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        const result = await response.json();
        if (result.success) {
            location.reload();
        } else {
            alert('Không thể xóa danh mục');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function renderCodeCategories() {
    const container = document.getElementById('codeCatListContainer');
    document.getElementById('codeCatCount').textContent = CODE_CATEGORIES.length;
    
    container.innerHTML = CODE_CATEGORIES.map(c => `
        <div class="cat-item">
            <div class="cat-info">
                <div class="cat-tag-preview" style="background: ${c.color}; color: ${c.text_color}; border: 1px solid ${c.text_color}20">${c.name}</div>
            </div>
            <div class="cat-actions">
                <button class="btn-icon-sm" onclick="showAddCodeCategoryForm(${c.id})"><i class="ph ph-pencil-simple"></i></button>
                <button class="btn-icon-sm" style="color: #ef4444;" onclick="deleteCodeCategory(${c.id})"><i class="ph ph-trash"></i></button>
            </div>
        </div>
    `).join('');

    // Update Snippet Modal Select options (UI ONLY)
    const selectMenu = document.getElementById('cxLangDropdownList');
    if (selectMenu) {
        selectMenu.innerHTML = CODE_CATEGORIES.map(c => `
            <div class="pj-dropdown-item" data-value="${c.name}">
                <span>${c.name}</span>
            </div>
        `).join('');
    }
}

function copySnippet() {
    const code = document.getElementById('pCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
        alert('Đã copy vào clipboard!');
    });
}
</script>
</body>
</html>
