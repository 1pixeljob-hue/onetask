<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Project - 1Pixel Dashboard</title>
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
                <a href="/hostings" class="nav-item">
                    <i class="ph ph-hard-drives"></i>
                    <span>Hostings</span>
                </a>
                <a href="/projects" class="nav-item active">
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
                    <h1>Quản Lý Project</h1>
                    <p>Quản lý tất cả project của bạn</p>
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
                            <input type="text" class="pj-search-input" placeholder="Tìm kiếm theo tên, khách hàng, mô tả...">
                        </div>
                        <div class="pj-toolbar-right">
                            <div class="pj-filter-wrapper">
                                <button class="pj-filter-btn" id="statusFilterBtn" onclick="toggleFilterDropdown()">
                                    <i class="ph ph-funnel-simple"></i>
                                    <span id="filterLabel">Lọc bởi trạng thái</span>
                                    <i class="ph ph-caret-down"></i>
                                </button>
                                <div class="pj-dropdown" id="filterDropdown">
                                    <div class="pj-dropdown-item active" onclick="setFilter('', 'Lọc bởi trạng thái', this)">Tất cả</div>
                                    <div class="pj-dropdown-item" onclick="setFilter('doing', 'Đang Thực Hiện', this)">Đang Thực Hiện</div>
                                    <div class="pj-dropdown-item" onclick="setFilter('testing', 'Chờ Nghiệm Thu', this)">Chờ Nghiệm Thu</div>
                                    <div class="pj-dropdown-item" onclick="setFilter('done', 'Hoàn Thành', this)">Hoàn Thành</div>
                                </div>
                            </div>
                            <button class="pj-add-btn">
                                <i class="ph ph-plus"></i>
                                Thêm Mới
                            </button>
                        </div>
                    </div>

                    <div class="table-wrapper pj-table-wrap">
                        <table class="data-table projects-table">
                            <thead>
                                <tr>
                                    <th width="40"><input type="checkbox" class="cb-custom"></th>
                                    <th>TÊN PROJECT</th>
                                    <th>KHÁCH HÀNG</th>
                                    <th>GIÁ TRỊ</th>
                                    <th>NGÀY TẠO</th>
                                    <th>TRẠNG THÁI</th>
                                    <th class="text-center">THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Project 1 -->
                                <tr>
                                    <td><input type="checkbox" class="cb-custom"></td>
                                    <td>
                                        <div class="project-info-cell">
                                            <h4 class="pj-title">Thêm sản phẩm cho web Trái Cây Lâm Thành</h4>
                                            <p class="pj-desc">Đã thanh toán 50%. Link drive: https://docs.google.com/spreadsheets/d/1Xdmz-walfv-LJ00Evvu009HXsBfsfc_mtPObNANHUYM/edit?pli=1&gid=1785282534#gid=1785282534</p>
                                            <a href="https://lamthanhfruit.myshopify.com/a..." class="pj-link" target="_blank">
                                                <i class="ph ph-link"></i> https://lamthanhfruit.myshopify.com/a...
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <i class="ph ph-user-circle"></i> Khánh Linh
                                            <div class="customer-phone"><i class="ph ph-phone"></i></div>
                                        </div>
                                    </td>
                                    <td><span class="val-badge"><i class="ph ph-money"></i> 3.5M</span></td>
                                    <td class="text-muted">10/03/2026</td>
                                    <td><span class="status-badge-outline st-doing"><i class="ph ph-circle-notch"></i> Đang Thực Hiện</span></td>
                                    <td class="text-center">
                                        <button class="btn-icon-nm"><i class="ph ph-dots-three-vertical"></i></button>
                                    </td>
                                </tr>

                                <!-- Project 2 -->
                                <tr>
                                    <td><input type="checkbox" class="cb-custom"></td>
                                    <td>
                                        <div class="project-info-cell">
                                            <h4 class="pj-title">Onelaw Code section tài liệu kèm iframe view</h4>
                                            <a href="https://onelawvn.com/adminxxxx" class="pj-link" target="_blank">
                                                <i class="ph ph-link"></i> https://onelawvn.com/adminxxxx
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <i class="ph ph-user-circle"></i> Onelaw
                                            <div class="customer-phone"><i class="ph ph-phone"></i></div>
                                        </div>
                                    </td>
                                    <td><span class="val-badge"><i class="ph ph-money"></i> 1.5M</span></td>
                                    <td class="text-muted">05/03/2026</td>
                                    <td><span class="status-badge-outline st-testing"><i class="ph ph-clock"></i> Chờ Nghiệm Thu</span></td>
                                    <td class="text-center">
                                        <button class="btn-icon-nm"><i class="ph ph-dots-three-vertical"></i></button>
                                    </td>
                                </tr>

                                <!-- Project 3 -->
                                <tr>
                                    <td><input type="checkbox" class="cb-custom"></td>
                                    <td>
                                        <div class="project-info-cell">
                                            <h4 class="pj-title">Thiết kế web Nam Việt Food Land</h4>
                                            <a href="https://namviethoodland.com/nam-l..." class="pj-link" target="_blank">
                                                <i class="ph ph-link"></i> https://namviethoodland.com/nam-l...
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <i class="ph ph-user-circle"></i> Anh Nguyễn Sư
                                            <div class="customer-phone"><i class="ph ph-phone"></i></div>
                                        </div>
                                    </td>
                                    <td><span class="val-badge"><i class="ph ph-money"></i> 4.0M</span></td>
                                    <td class="text-muted">03/03/2026</td>
                                    <td><span class="status-badge-outline st-testing"><i class="ph ph-clock"></i> Chờ Nghiệm Thu</span></td>
                                    <td class="text-center">
                                        <button class="btn-icon-nm"><i class="ph ph-dots-three-vertical"></i></button>
                                    </td>
                                </tr>

                                <!-- Project 4 -->
                                <tr>
                                    <td><input type="checkbox" class="cb-custom"></td>
                                    <td>
                                        <div class="project-info-cell">
                                            <h4 class="pj-title">Hỗ trợ chị Hạnh xử lý web Phú Thành</h4>
                                            <p class="pj-desc">Xử lý chuyển link custom và vô domain chính phuthanh.net</p>
                                            <a href="https://phuthanh.net/phu-admin" class="pj-link" target="_blank">
                                                <i class="ph ph-link"></i> https://phuthanh.net/phu-admin
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <i class="ph ph-user-circle"></i> Phú Thành
                                            <div class="customer-phone"><i class="ph ph-phone"></i></div>
                                        </div>
                                    </td>
                                    <td><span class="val-badge"><i class="ph ph-money"></i> 1.0M</span></td>
                                    <td class="text-muted">05/03/2026</td>
                                    <td><span class="status-badge-outline st-done"><i class="ph ph-check-circle"></i> Hoàn Thành</span></td>
                                    <td class="text-center">
                                        <button class="btn-icon-nm"><i class="ph ph-dots-three-vertical"></i></button>
                                    </td>
                                </tr>

                                <!-- Project 5 -->
                                <tr>
                                    <td><input type="checkbox" class="cb-custom"></td>
                                    <td>
                                        <div class="project-info-cell">
                                            <h4 class="pj-title">Sayoung - Đăng ký website với Bộ Công Thương</h4>
                                            <p class="pj-desc">Thông báo website với Bộ Công Thương</p>
                                            <a href="https://dichvucong.moit.gov.vn/Log..." class="pj-link" target="_blank">
                                                <i class="ph ph-link"></i> https://dichvucong.moit.gov.vn/Log...
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <i class="ph ph-user-circle"></i> Sayoung
                                            <div class="customer-phone"><i class="ph ph-phone"></i> 0383797541</div>
                                        </div>
                                    </td>
                                    <td><span class="val-badge"><i class="ph ph-money"></i> 2.5M</span></td>
                                    <td class="text-muted">25/01/2026</td>
                                    <td><span class="status-badge-outline st-done"><i class="ph ph-check-circle"></i> Hoàn Thành</span></td>
                                    <td class="text-center">
                                        <button class="btn-icon-nm"><i class="ph ph-dots-three-vertical"></i></button>
                                    </td>
                                </tr>

                                <!-- Project 6 -->
                                <tr>
                                    <td><input type="checkbox" class="cb-custom"></td>
                                    <td>
                                        <div class="project-info-cell">
                                            <h4 class="pj-title">Thiết kế Landing cho Onelaw.vn</h4>
                                            <p class="pj-desc">Thiết kế Landing cho Onelaw page https://onelaw.vn/dich-vu-thanh-lap-cong-ty-von-nuoc-ngoai</p>
                                            <a href="https://onelaw.vn/adminxxxx" class="pj-link" target="_blank">
                                                <i class="ph ph-link"></i> https://onelaw.vn/adminxxxx
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <i class="ph ph-user-circle"></i> A Hùng
                                            <div class="customer-phone"><i class="ph ph-phone"></i> 0383797541</div>
                                        </div>
                                    </td>
                                    <td><span class="val-badge"><i class="ph ph-money"></i> 1.0M</span></td>
                                    <td class="text-muted">24/01/2026</td>
                                    <td><span class="status-badge-outline st-done"><i class="ph ph-check-circle"></i> Hoàn Thành</span></td>
                                    <td class="text-center">
                                        <button class="btn-icon-nm"><i class="ph ph-dots-three-vertical"></i></button>
                                    </td>
                                </tr>

                                <!-- Project 7 -->
                                <tr>
                                    <td><input type="checkbox" class="cb-custom"></td>
                                    <td>
                                        <div class="project-info-cell">
                                            <h4 class="pj-title">Thiết kế web Pearlcenter</h4>
                                            <p class="pj-desc">Thiết kế web theo yêu cầu</p>
                                            <a href="https://pearlcenter.vn/pro-login" class="pj-link" target="_blank">
                                                <i class="ph ph-link"></i> https://pearlcenter.vn/pro-login
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <i class="ph ph-user-circle"></i> A Hùng
                                            <div class="customer-phone"><i class="ph ph-phone"></i> 0383797541</div>
                                        </div>
                                    </td>
                                    <td><span class="val-badge"><i class="ph ph-money"></i> 5.0M</span></td>
                                    <td class="text-muted">22/01/2026</td>
                                    <td><span class="status-badge-outline st-done"><i class="ph ph-check-circle"></i> Hoàn Thành</span></td>
                                    <td class="text-center">
                                        <button class="btn-icon-nm"><i class="ph ph-dots-three-vertical"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div> <!-- end content-body -->

                <!-- Pagination -->
                <div class="logs-pagination-row" style="padding: 0 0 8px 0;">
                    <span class="logs-count">Hiển thị: 1 - 7 / 154 projects</span>
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
        </main>

    </div>

<script>
function toggleFilterDropdown() {
    const dd = document.getElementById('filterDropdown');
    dd.classList.toggle('open');
}
function setFilter(val, label, el) {
    document.getElementById('filterLabel').textContent = label;
    document.querySelectorAll('.pj-dropdown-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('filterDropdown').classList.remove('open');
    filterTable(val);
}
function filterTable(status) {
    document.querySelectorAll('.projects-table tbody tr').forEach(row => {
        if (!status) { row.style.display = ''; return; }
        const badge = row.querySelector('.status-badge-outline');
        if (badge && badge.classList.contains('st-' + status)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.pj-filter-wrapper')) {
        document.getElementById('filterDropdown').classList.remove('open');
    }
});
</script>
</body>
</html>
