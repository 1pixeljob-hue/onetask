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
                            <div class="cx-sidebar-actions">
                                <button class="btn-primary" onclick="openCxModal()"><i class="ph ph-plus"></i> Thêm Mới</button>
                                <button class="btn-cx-category" onclick="openCodeCategoryModal()"><i class="ph ph-tag"></i> Danh Mục</button>
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

    <!-- Modal: Quản lý Danh mục Code -->
    <div id="codeCategoryModal" class="modal-overlay" onclick="if(event.target.id==='codeCategoryModal') closeCodeCategoryModal()">
        <div class="modal-box" style="max-width: 550px;">
            <div class="modal-header-gradient">
                <h3 class="modal-title-light"><i class="ph-fill ph-tag"></i> Quản lý Danh mục Code</h3>
                <button class="modal-close-light" onclick="closeCodeCategoryModal()"><i class="ph ph-x"></i></button>
            </div>
            
            <div class="modal-body">
                <!-- Chế độ Danh sách -->
                <div id="codeCatListView">
                    <button class="btn-add-cat-dashed" onclick="showAddCodeCategoryForm()">
                        <i class="ph ph-plus"></i> Thêm Danh Mục Mới
                    </button>
                    
                    <div class="label-with-action" style="margin-top: 24px;">
                        <span class="modal-label" style="font-size: 14px; font-weight: 700;">Danh Sách Danh Mục (<span id="codeCatCount">0</span>)</span>
                    </div>
                    
                    <div class="cat-list" id="codeCatListContainer">
                        <!-- Categories dynamic render -->
                    </div>
                </div>

                <!-- Chế độ Thêm/Sửa -->
                <div id="codeCatFormView" style="display: none;">
                    <div class="cat-preview-section">
                        <span class="cat-preview-label">XEM TRƯỚC</span>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span id="codeCatPreviewTag" class="cx-tag">Tên danh mục</span>
                            <span id="codeCatPreviewHex" style="font-family: monospace; font-size: 13px; color: #64748b;">#fef9c3</span>
                        </div>
                    </div>

                    <div class="modal-field full">
                        <label class="modal-label">Tên Danh Mục</label>
                        <input type="text" class="modal-input" id="codeCatNameInput" placeholder="VD: React, Vue, Python..." oninput="updateCodeCatPreview()">
                    </div>

                    <div class="modal-field full">
                        <label class="modal-label">Màu Sắc</label>
                        <div class="hex-input-wrapper">
                            <div class="color-dot" id="codeCatColorDot" style="background: #fef9c3;"></div>
                            <input type="text" class="hex-input" id="codeCatHexInput" value="#fef9c3" oninput="updateCodeCatPreviewFromHex()">
                        </div>
                    </div>

                    <div class="modal-label" style="margin-top: 16px;">Bộ màu gợi ý:</div>
                    <div class="color-presets" id="codeCatColorPresets">
                        <!-- Generated by JS -->
                    </div>

                    <div class="modal-footer" style="padding: 16px 0 0 0; margin-top: 24px; border-top: 1px solid #f1f5f9;">
                        <button type="button" class="modal-btn-cancel" onclick="showCodeCategoryList()">Hủy</button>
                        <button type="button" class="modal-btn-submit" id="codeCatSubmitBtn" onclick="saveCodeCategory()">Thêm Mới</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer" id="codeCatMainFooter">
                <button class="modal-btn-submit" style="width: 100%; height: 48px; background: #2b7495;" onclick="closeCodeCategoryModal()">Đóng</button>
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
                            <label class="modal-label"><i class="ph ph-notebook"></i> Tên Code <span class="req">*</span></label>
                            <input type="text" name="title" id="cxTitle" class="modal-input" placeholder="VD: React useEffect Hook" required>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label"><i class="ph ph-tag"></i> Loại Code <span class="req">*</span></label>
                            <div class="pj-modal-select" data-input-id="cxLangInput" data-callback="onCxLangSelect" id="cxLangSelect">
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
                                        <button type="button" class="cx-show-add-btn" id="cxShowAddBtn" onclick="showAddLangInput(event)">
                                            <i class="ph ph-plus-circle"></i> Thêm loại mới...
                                        </button>
                                        <div class="cx-add-lang-input-group" id="cxAddLangInputGroup" style="display: none;">
                                            <input type="text" id="newLangInput" placeholder="Tên loại mới..." onkeyup="handleNewLangKey(event)">
                                            <div class="cx-add-lang-actions">
                                                <button type="button" class="cx-add-btn-small" onclick="saveNewLang(event)">
                                                    <i class="ph ph-check"></i>
                                                </button>
                                                <button type="button" class="cx-cancel-btn-small" onclick="hideAddLangInput(event)">
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

<script>
// Data from PHP
let CODE_CATEGORIES = <?php echo json_encode($categories); ?>;
const ALL_SNIPPETS = <?php echo json_encode($snippets); ?>;

// Modal Snippet Variables

// --- Modal Snippet Functions ---
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
    renderCodeColorPresets();
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
        document.getElementById('codeCatHexInput').value = cat.color;
        document.getElementById('codeCatSubmitBtn').textContent = 'Cập Nhật';
    } else {
        document.getElementById('codeCatNameInput').value = '';
        document.getElementById('codeCatHexInput').value = '#fef9c3';
        document.getElementById('codeCatSubmitBtn').textContent = 'Thêm Mới';
    }
    updateCodeCatPreview();
}

function renderCodeCategories() {
    const container = document.getElementById('codeCatListContainer');
    document.getElementById('codeCatCount').textContent = CODE_CATEGORIES.length;
    
    container.innerHTML = CODE_CATEGORIES.map(c => `
        <div class="cat-item">
            <div class="cat-info">
                <div class="cat-color-preview" style="background: ${c.color}"></div>
                <div class="cat-name">${c.name}</div>
                <div class="cx-tag" style="background: ${c.color}20; color: ${c.color}; border-color: ${c.color}">${c.name}</div>
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

function renderCodeColorPresets() {
    const container = document.getElementById('codeCatColorPresets');
    container.innerHTML = CODE_COLOR_PRESETS.map(c => `
        <div class="color-tile" style="background: ${c}" onclick="selectCodePresetColor('${c}', this)">
            <i class="ph ph-check"></i>
        </div>
    `).join('');
    selectCodePresetColor(document.getElementById('codeCatHexInput').value);
}

function selectCodePresetColor(color, el = null) {
    document.getElementById('codeCatHexInput').value = color;
    document.querySelectorAll('#codeCatColorPresets .color-tile').forEach(t => t.classList.remove('active'));
    if (el) el.classList.add('active');
    else {
        const tiles = [...document.querySelectorAll('#codeCatColorPresets .color-tile')];
        const active = tiles.find(t => {
            const tileBg = t.style.backgroundColor;
            return tileBg.includes(color.toLowerCase()) || rgbToHex(tileBg) === color.toLowerCase();
        });
        if (active) active.classList.add('active');
    }
    updateCodeCatPreview();
}

function rgbToHex(rgb) {
    if (!rgb) return "";
    const match = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    if (!match) return rgb;
    function hex(x) { return ("0" + parseInt(x).toString(16)).slice(-2); }
    return "#" + hex(match[1]) + hex(match[2]) + hex(match[3]);
}

function updateCodeCatPreview() {
    const name = document.getElementById('codeCatNameInput').value || 'Tên danh mục';
    const color = document.getElementById('codeCatHexInput').value;
    const tag = document.getElementById('codeCatPreviewTag');
    
    if (tag) {
        tag.textContent = name;
        tag.style.background = color + '20';
        tag.style.color = color;
        tag.style.borderColor = color;
    }
    document.getElementById('codeCatPreviewHex').textContent = color;
    document.getElementById('codeCatColorDot').style.background = color;
}

function updateCodeCatPreviewFromHex() {
    const hex = document.getElementById('codeCatHexInput').value;
    if (/^#[0-9A-F]{6}$/i.test(hex)) {
        updateCodeCatPreview();
    }
}

async function saveCodeCategory() {
    const data = {
        id: currentCatEditId,
        name: document.getElementById('codeCatNameInput').value,
        color: document.getElementById('codeCatHexInput').value
    };

    if (!data.name) return alert('Vui lòng nhập tên danh mục');

    try {
        const response = await fetch('/codex/categories/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            if (currentCatEditId) {
                const idx = CODE_CATEGORIES.findIndex(c => c.id == currentCatEditId);
                CODE_CATEGORIES[idx] = result;
            } else {
                CODE_CATEGORIES.push(result);
            }
            showCodeCategoryList();
        } else {
            alert(result.message || 'Lỗi khi lưu danh mục');
        }
    } catch (e) { console.error(e); }
}

async function deleteCodeCategory(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa danh mục này?')) return;
    try {
        const response = await fetch('/codex/categories/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const result = await response.json();
        if (result.success) {
            CODE_CATEGORIES = CODE_CATEGORIES.filter(c => c.id != id);
            renderCodeCategories();
        } else {
            alert('Không thể xóa danh mục này');
        }
    } catch (e) { console.error(e); }
}


// --- Snippet Logic ---
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
    
    const cat = CODE_CATEGORIES.find(c => c.name === snippet.language);
    if (cat) {
        document.getElementById('cxLangBadge').style.backgroundColor = cat.color;
        document.getElementById('cxLangBadge').style.color = cat.text_color;
    }
    
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
        alert('Đã copy vào clipboard!');
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
    if (!name) return;

    try {
        const response = await fetch('/codex/categories/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: name })
        });
        const data = await response.json();
        
        if (data.success) {
            if (!data.exists) {
                CODE_CATEGORIES.push(data);
                renderCodeCategories();
            }
            document.getElementById('cxLangInput').value = data.name;
            document.getElementById('cxLangBadge').textContent = data.name;
            const badge = document.getElementById('cxLangBadge');
            badge.style.backgroundColor = data.color;
            badge.style.color = data.text_color;

            hideAddLangInput();
            document.getElementById('cxLangSelect').classList.remove('open');
        } else {
            alert(data.message || 'Lỗi khi thêm loại code');
        }
    } catch (err) {
        console.error('Error:', err);
    }
}
// Callback khi chọn loại code từ dropdown
function onCxLangSelect(value, label, option) {
    const badge = document.getElementById('cxLangBadge');
    if (!badge || !value) return;
    
    // Tìm danh mục từ dữ liệu CODE_CATEGORIES
    const cat = CODE_CATEGORIES.find(c => c.name === value);
    if (cat) {
        // Cập nhật phong cách badge theo danh mục
        badge.style.backgroundColor = cat.color + '20'; 
        badge.style.color = cat.color;
        badge.style.borderColor = cat.color;
    } else {
        // Màu mặc định nếu không tìm thấy
        badge.style.backgroundColor = '#f1f5f9';
        badge.style.color = '#64748b';
        badge.style.borderColor = '#e2e8f0';
    }
}
</script>
</body>
</html>
