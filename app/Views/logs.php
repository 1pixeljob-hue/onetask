<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                <a href="/codex" class="nav-item">
                    <i class="ph ph-code"></i>
                    <span>CodeX</span>
                </a>
                <a href="/logs" class="nav-item active">
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
                    <h1>Dashboard</h1>
                    <p>Tổng quan hệ thống</p>
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

                <!-- Page Title in Content -->
                <div class="logs-page-title-block">
                    <h2 class="logs-page-title">Logs</h2>
                    <p class="logs-page-subtitle">Theo dõi các hành động và restore dữ liệu đã xóa</p>
                </div>

                <!-- Toolbar -->
                <div class="logs-toolbar-row">
                    <div class="pj-search-wrap logs-search">
                        <i class="ph ph-magnifying-glass pj-search-icon"></i>
                        <input type="text" class="pj-search-input" id="logsSearchInput" placeholder="Tìm kiếm theo tên item hoặc user...">
                    </div>
                    <select class="logs-select" id="logsModuleSelect" onchange="filterLogs()">
                        <option value="">Tất cả Module</option>
                        <option value="Project">Project</option>
                        <option value="Hosting">Hosting</option>
                        <option value="CodeX">CodeX</option>
                    </select>
                    <select class="logs-select" id="logsActionSelect" onchange="filterLogs()">
                        <option value="">Tất cả Hành động</option>
                        <option value="Cập nhật">Cập nhật</option>
                        <option value="Tạo mới">Tạo mới</option>
                        <option value="Xoá">Xoá</option>
                    </select>
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
                            <!-- Row 1 -->
                            <tr data-module="Project" data-action="Cập nhật">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td><div class="log-module"><i class="ph ph-folder"></i> Project</div></td>
                                <td><span class="status-badge badge-blue">Cập nhật</span></td>
                                <td>Thêm sản phẩm cho web Trái Cây Lâm Thành</td>
                                <td class="text-muted">quydev</td>
                                <td class="text-muted">16/03/2026 14:39</td>
                                <td class="text-center"><div class="log-actions"><button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button><button class="btn-action" title="Xoá"><i class="ph ph-trash color-red"></i></button></div></td>
                            </tr>
                            <!-- Row 2 -->
                            <tr data-module="Project" data-action="Cập nhật">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td><div class="log-module"><i class="ph ph-folder"></i> Project</div></td>
                                <td><span class="status-badge badge-blue">Cập nhật</span></td>
                                <td>Thiết kế website KLP</td>
                                <td class="text-muted">quydev</td>
                                <td class="text-muted">16/03/2026 14:39</td>
                                <td class="text-center"><div class="log-actions"><button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button><button class="btn-action" title="Xoá"><i class="ph ph-trash color-red"></i></button></div></td>
                            </tr>
                            <!-- Row 3 -->
                            <tr data-module="Project" data-action="Cập nhật">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td><div class="log-module"><i class="ph ph-folder"></i> Project</div></td>
                                <td><span class="status-badge badge-blue">Cập nhật</span></td>
                                <td>Onelaw Code section tài liệu kèm iframe view</td>
                                <td class="text-muted">quydev</td>
                                <td class="text-muted">16/03/2026 14:39</td>
                                <td class="text-center"><div class="log-actions"><button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button><button class="btn-action" title="Xoá"><i class="ph ph-trash color-red"></i></button></div></td>
                            </tr>
                            <!-- Row 4 -->
                            <tr data-module="Project" data-action="Cập nhật">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td><div class="log-module"><i class="ph ph-folder"></i> Project</div></td>
                                <td><span class="status-badge badge-blue">Cập nhật</span></td>
                                <td>Hỗ trợ chị Hạnh xử lý web Phú Thành</td>
                                <td class="text-muted">quydev</td>
                                <td class="text-muted">11/03/2026 10:41</td>
                                <td class="text-center"><div class="log-actions"><button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button><button class="btn-action" title="Xoá"><i class="ph ph-trash color-red"></i></button></div></td>
                            </tr>
                            <!-- Row 5 -->
                            <tr data-module="Hosting" data-action="Tạo mới">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td><div class="log-module"><i class="ph ph-hard-drives"></i> Hosting</div></td>
                                <td><span class="status-badge badge-green">Tạo mới</span></td>
                                <td>VINALIGHT</td>
                                <td class="text-muted">quydev</td>
                                <td class="text-muted">11/03/2026 10:14</td>
                                <td class="text-center"><div class="log-actions"><button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button><button class="btn-action" title="Xoá"><i class="ph ph-trash color-red"></i></button></div></td>
                            </tr>
                            <!-- Row 6 -->
                            <tr data-module="Hosting" data-action="Tạo mới">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td><div class="log-module"><i class="ph ph-hard-drives"></i> Hosting</div></td>
                                <td><span class="status-badge badge-green">Tạo mới</span></td>
                                <td>VINALIGHT</td>
                                <td class="text-muted">quydev</td>
                                <td class="text-muted">11/03/2026 10:14</td>
                                <td class="text-center"><div class="log-actions"><button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button><button class="btn-action" title="Xoá"><i class="ph ph-trash color-red"></i></button></div></td>
                            </tr>
                            <!-- Row 7 -->
                            <tr data-module="Project" data-action="Cập nhật">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td><div class="log-module"><i class="ph ph-folder"></i> Project</div></td>
                                <td><span class="status-badge badge-blue">Cập nhật</span></td>
                                <td>VINALIGHT x Rooster</td>
                                <td class="text-muted">quydev</td>
                                <td class="text-muted">11/03/2026 10:13</td>
                                <td class="text-center"><div class="log-actions"><button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button><button class="btn-action" title="Xoá"><i class="ph ph-trash color-red"></i></button></div></td>
                            </tr>
                            <!-- Row 8 -->
                            <tr data-module="Project" data-action="Tạo mới">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td><div class="log-module"><i class="ph ph-folder"></i> Project</div></td>
                                <td><span class="status-badge badge-green">Tạo mới</span></td>
                                <td>VINALIGHT x Rooster</td>
                                <td class="text-muted">quydev</td>
                                <td class="text-muted">11/03/2026 10:13</td>
                                <td class="text-center"><div class="log-actions"><button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button><button class="btn-action" title="Xoá"><i class="ph ph-trash color-red"></i></button></div></td>
                            </tr>
                            <!-- Row 9 -->
                            <tr data-module="CodeX" data-action="Cập nhật">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td><div class="log-module"><i class="ph ph-code"></i> CodeX</div></td>
                                <td><span class="status-badge badge-blue">Cập nhật</span></td>
                                <td>Ẩn 1 menu trong admin WP</td>
                                <td class="text-muted">quydev</td>
                                <td class="text-muted">10/03/2026 20:13</td>
                                <td class="text-center"><div class="log-actions"><button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button><button class="btn-action" title="Xoá"><i class="ph ph-trash color-red"></i></button></div></td>
                            </tr>
                            <!-- Row 10 -->
                            <tr data-module="Project" data-action="Cập nhật">
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td><div class="log-module"><i class="ph ph-folder"></i> Project</div></td>
                                <td><span class="status-badge badge-blue">Cập nhật</span></td>
                                <td>Thêm sản phẩm cho web Trái Cây Lâm Thành</td>
                                <td class="text-muted">quydev</td>
                                <td class="text-muted">10/03/2026 18:03</td>
                                <td class="text-center"><div class="log-actions"><button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button><button class="btn-action" title="Xoá"><i class="ph ph-trash color-red"></i></button></div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="logs-pagination-row">
                    <span class="logs-count">Hiển thị: 1 - 10 / 154 logs</span>
                    <div class="logs-pagination">
                        <button class="pg-btn">Trước</button>
                        <button class="pg-btn active">1</button>
                        <button class="pg-btn">2</button>
                        <button class="pg-btn">3</button>
                        <button class="pg-btn">4</button>
                        <button class="pg-btn">5</button>
                        <button class="pg-btn">Sau</button>
                    </div>
                </div>

            </div>
        </main>
    </div>

<script>
function filterLogs() {
    const module = document.getElementById('logsModuleSelect').value;
    const action = document.getElementById('logsActionSelect').value;
    const search = document.getElementById('logsSearchInput').value.toLowerCase();
    document.querySelectorAll('#logsTable tbody tr').forEach(row => {
        const rowModule = row.dataset.module || '';
        const rowAction = row.dataset.action || '';
        const rowText = row.textContent.toLowerCase();
        const moduleMatch = !module || rowModule === module;
        const actionMatch = !action || rowAction === action;
        const searchMatch = !search || rowText.includes(search);
        row.style.display = (moduleMatch && actionMatch && searchMatch) ? '' : 'none';
    });
}
document.getElementById('logsSearchInput').addEventListener('input', filterLogs);
</script>
</body>
</html>
