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

function saveNewLang(e) {
    e.stopPropagation();
    const name = document.getElementById('newLangInput').value.trim();
    if (!name) return;

    fetch('/codex/categories/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name: name })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Add to dropdown list if it doesn't exist
            const list = document.getElementById('cxLangDropdownList');
            const exists = Array.from(list.children).some(item => item.dataset.value === data.name);
            
            if (!exists) {
                const newItem = document.createElement('div');
                newItem.className = 'pj-dropdown-item';
                newItem.dataset.value = data.name;
                newItem.innerHTML = `<span>${data.name}</span>`;
                list.appendChild(newItem);

                // Add to sidebar nav if it doesn't exist
                const sidebarNav = document.getElementById('cxSidebarNav');
                const navExists = Array.from(sidebarNav.children).some(item => item.dataset.langName === data.name);
                if (!navExists) {
                    const newNavItem = document.createElement('div');
                    newNavItem.className = 'cx-nav-item';
                    newNavItem.dataset.langName = data.name;
                    newNavItem.onclick = () => filterByLang(data.name);
                    newNavItem.innerHTML = `<span>${data.name}</span> <span class="cx-count">0</span>`;
                    sidebarNav.appendChild(newNavItem);
                }
            }

            // Select it
            document.getElementById('cxLangInput').value = data.name;
            document.getElementById('cxLangBadge').textContent = data.name;
            
            // UI active state
            Array.from(list.children).forEach(item => {
                if (item.dataset.value === data.name) item.classList.add('active');
                else item.classList.remove('active');
            });

            hideAddLangInput();
        } else {
            alert(data.message || 'Lỗi khi thêm loại code');
        }
    })
    .catch(err => console.error('Error:', err));
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
