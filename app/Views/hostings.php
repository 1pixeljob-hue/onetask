<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostings - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
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
                <a href="/hostings" class="nav-item active">
                    <i class="ph ph-hard-drives"></i>
                    <span>Hostings</span>
                </a>
                <a href="/projects" class="nav-item">
                    <i class="ph ph-kanban"></i>
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
                    <h1>Danh Sách Hosting</h1>
                    <p>Quản lý tất cả hosting của bạn</p>
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
                        <input type="text" class="pj-search-input" placeholder="Tìm kiếm theo tên, domain, nhà cung cấp...">
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
                        <button class="pj-add-btn">
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
                                <th width="40"><input type="checkbox" class="cb-custom"></th>
                                <th>TÊN HOSTING</th>
                                <th>DOMAIN</th>
                                <th>NHÀ CUNG CẤP</th>
                                <th>NGÀY HẾT HẠN</th>
                                <th>TRẠNG THÁI</th>
                                <th width="80" class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Row 1 -->
                            <tr>
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Photoeditor 24h</div>
                                    <div class="cell-sub">Sử dụng 1 năm</div>
                                </td>
                                <td>
                                    <div class="domain-info">
                                        <i class="ph ph-globe color-gray"></i>
                                        <span>https://photoeditor24h.com/</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-hard-drives color-gray"></i>
                                        <span>iNet</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info error-text">
                                        <i class="ph ph-calendar-blank"></i> 12/04/2026
                                    </div>
                                    <div class="date-sub error-text">
                                        <i class="ph ph-clock"></i> Còn 17 ngày
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge warning">
                                        <i class="ph ph-warning-circle"></i>
                                        Sắp hết hạn
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action"><i class="ph ph-dots-three"></i></button>
                                </td>
                            </tr>
                            
                            <!-- Row 2 -->
                            <tr>
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Hosting Sơn TREX</div>
                                    <div class="cell-sub">Sử dụng 1 năm</div>
                                </td>
                                <td>
                                    <div class="domain-info">
                                        <i class="ph ph-globe color-gray"></i>
                                        <span>https://sontrex.vn/</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-hard-drives color-gray"></i>
                                        <span>iNet</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info text-main">
                                        <i class="ph ph-calendar-blank"></i> 07/05/2026
                                    </div>
                                    <div class="date-sub success-text">
                                        <i class="ph ph-clock"></i> Còn 42 ngày
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge success">
                                        <i class="ph ph-check-circle"></i>
                                        Hoạt động
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action"><i class="ph ph-dots-three"></i></button>
                                </td>
                            </tr>

                            <!-- Row 3 -->
                            <tr>
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Sayoung</div>
                                    <div class="cell-sub">Sử dụng 5 năm</div>
                                </td>
                                <td>
                                    <div class="domain-info">
                                        <i class="ph ph-globe color-gray"></i>
                                        <span>https://sayoung.vn/</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-hard-drives color-gray"></i>
                                        <span>iNet</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info text-main">
                                        <i class="ph ph-calendar-blank"></i> 20/05/2026
                                    </div>
                                    <div class="date-sub success-text">
                                        <i class="ph ph-clock"></i> Còn 55 ngày
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge success">
                                        <i class="ph ph-check-circle"></i>
                                        Hoạt động
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action"><i class="ph ph-dots-three"></i></button>
                                </td>
                            </tr>

                            <!-- Row 4 -->
                            <tr>
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">BĐS Yên Thủy</div>
                                    <div class="cell-sub">Sử dụng 1 năm</div>
                                </td>
                                <td>
                                    <div class="domain-info">
                                        <i class="ph ph-globe color-gray"></i>
                                        <span>https://vietnamrussia.com.vn/</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-hard-drives color-gray"></i>
                                        <span>iNet</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info text-main">
                                        <i class="ph ph-calendar-blank"></i> 14/06/2026
                                    </div>
                                    <div class="date-sub success-text">
                                        <i class="ph ph-clock"></i> Còn 80 ngày
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge success">
                                        <i class="ph ph-check-circle"></i>
                                        Hoạt động
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action"><i class="ph ph-dots-three"></i></button>
                                </td>
                            </tr>

                            <!-- Row 5 -->
                            <tr>
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Giấy Sao Mai</div>
                                    <div class="cell-sub">Sử dụng 1 năm</div>
                                </td>
                                <td>
                                    <div class="domain-info">
                                        <i class="ph ph-globe color-gray"></i>
                                        <span>http://thesaomaigroup.com/</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-hard-drives color-gray"></i>
                                        <span>iNet</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info text-main">
                                        <i class="ph ph-calendar-blank"></i> 11/07/2026
                                    </div>
                                    <div class="date-sub success-text">
                                        <i class="ph ph-clock"></i> Còn 107 ngày
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge success">
                                        <i class="ph ph-check-circle"></i>
                                        Hoạt động
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action"><i class="ph ph-dots-three"></i></button>
                                </td>
                            </tr>
                            
                            <!-- Row 6 -->
                            <tr>
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Sơn KAZUKI</div>
                                    <div class="cell-sub">Sử dụng 1 năm</div>
                                </td>
                                <td>
                                    <div class="domain-info">
                                        <i class="ph ph-globe color-gray"></i>
                                        <span>https://sonkazuki.com/</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-hard-drives color-gray"></i>
                                        <span>iNet</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info text-main">
                                        <i class="ph ph-calendar-blank"></i> 18/07/2026
                                    </div>
                                    <div class="date-sub success-text">
                                        <i class="ph ph-clock"></i> Còn 114 ngày
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge success">
                                        <i class="ph ph-check-circle"></i>
                                        Hoạt động
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action"><i class="ph ph-dots-three"></i></button>
                                </td>
                            </tr>

                            <!-- Row 7 -->
                            <tr>
                                <td><input type="checkbox" class="cb-custom"></td>
                                <td>
                                    <div class="cell-main">Pomaxx</div>
                                    <div class="cell-sub">Sử dụng 2 năm</div>
                                </td>
                                <td>
                                    <div class="domain-info">
                                        <i class="ph ph-globe color-gray"></i>
                                        <span>https://pomaxx.vn/</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="provider-info">
                                        <i class="ph ph-hard-drives color-gray"></i>
                                        <span>iNet</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="date-info text-main">
                                        <i class="ph ph-calendar-blank"></i> 24/07/2026
                                    </div>
                                    <div class="date-sub success-text">
                                        <i class="ph ph-clock"></i> Còn 120 ngày
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge success">
                                        <i class="ph ph-check-circle"></i>
                                        Hoạt động
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action"><i class="ph ph-dots-three"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> <!-- end content-body -->
        </main>
    </div>

<script>
function toggleHostingFilter() {
    document.getElementById('hostingFilterDropdown').classList.toggle('open');
}
function setHostingFilter(val, label, el) {
    document.getElementById('hostingFilterLabel').textContent = label;
    document.querySelectorAll('#hostingFilterDropdown .pj-dropdown-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('hostingFilterDropdown').classList.remove('open');
    document.querySelectorAll('.data-table tbody tr').forEach(row => {
        if (!val) { row.style.display = ''; return; }
        const badge = row.querySelector('.status-badge');
        row.style.display = (badge && badge.classList.contains(val)) ? '' : 'none';
    });
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.pj-filter-wrapper')) {
        const dd = document.getElementById('hostingFilterDropdown');
        if (dd) dd.classList.remove('open');
    }
});
</script>
</body>
</html>
