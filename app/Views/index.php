<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        // Data injected from PHP Models
        const PHP_DATA = {
            projects: <?php echo json_encode($projects); ?>,
            hostings: <?php echo json_encode($hostings); ?>
        };
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
                <a href="/" class="nav-item active">
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
                    <h1>Dashboard</h1>
                    <p>Tổng quan hệ thống quản lý công việc</p>
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
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <h2>Chào buổi tối, <span class="highlight">Quydev</span></h2>
                    <p>Đây là những gì đang diễn ra trong hệ thống hôm nay</p>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Tổng Hosting</span>
                            <i class="ph ph-cards color-gray"></i>
                        </div>
                        <div class="stat-value" id="statTotalHosting">0</div>
                        <div class="stat-desc" id="statActiveHosting">0 đang hoạt động</div>
                    </div>
                    <div class="stat-card warning-card">
                        <div class="stat-header">
                            <span class="stat-title">Sắp Hết Hạn</span>
                            <i class="ph ph-warning-circle color-orange"></i>
                        </div>
                        <div class="stat-value" id="statExpiring">0</div>
                        <div class="stat-desc">hosting cần gia hạn</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Đang Thực Hiện</span>
                            <i class="ph ph-folder color-blue"></i>
                        </div>
                        <div class="stat-value" id="statDoing">0</div>
                        <div class="stat-desc">đang triển khai</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Chờ Nghiệm Thu</span>
                            <i class="ph ph-check-circle color-purple"></i>
                        </div>
                        <div class="stat-value" id="statTesting">0</div>
                        <div class="stat-desc" id="statTestingValue">0 VNĐ</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Doanh Thu Hoàn Thành</span>
                            <i class="ph ph-trend-up color-green"></i>
                        </div>
                        <div class="stat-value" id="statRevenue">0</div>
                        <div class="stat-desc">VNĐ hiện tại</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Tổng Doanh Thu</span>
                            <i class="ph ph-chart-line-up color-blue"></i>
                        </div>
                        <div class="stat-value" id="statTotalRevenue">0</div>
                        <div class="stat-desc">toàn hệ thống</div>
                    </div>
                </div>


                <!-- Three Columns Section -->
                <div class="dashboard-columns">
                    <!-- Column 1 -->
                    <div class="board-column">
                        <h3 class="section-title">Tình Trạng Hosting</h3>
                        <div class="card-list" id="dashHostingList">
                            <!-- Rendered by JS -->
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="board-column">
                        <h3 class="section-title">Tiến Độ Dự Án</h3>
                        <div class="card-list" id="dashProjectList">
                            <!-- Rendered by JS -->
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="board-column">
                        <h3 class="section-title">Hoạt Động Gần Đây</h3>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="dot color-blue"></div>
                                <div class="activity-content">
                                    <p><b>quydev</b> đã cập nhật project <b>Thêm sản phẩm cho ...</b></p>
                                    <span>15/03/2026</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="dot color-blue"></div>
                                <div class="activity-content">
                                    <p><b>quydev</b> đã cập nhật project <b>Thiết kế website KLP</b></p>
                                    <span>16/03/2026</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="dot color-blue"></div>
                                <div class="activity-content">
                                    <p><b>quydev</b> đã cập nhật project <b>Onelaw Code section ...</b></p>
                                    <span>16/03/2026</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="dot color-blue"></div>
                                <div class="activity-content">
                                    <p><b>quydev</b> đã cập nhật project <b>Hỗ trợ chị Hạnh xử lý...</b></p>
                                    <span>11/03/2026</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="dot color-green"></div>
                                <div class="activity-content">
                                    <p><b>quydev</b> đã tạo hosting <b>VINALIGHT</b></p>
                                    <span>11/03/2026</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Section -->
                <div class="quick-actions-section">
                    <h3 class="section-title">Hành Động Nhanh</h3>
                    <div class="actions-grid">
                        <button class="action-btn">
                            <i class="ph ph-hard-drives"></i>
                            <span>Thêm Hosting</span>
                        </button>
                        <button class="action-btn">
                            <i class="ph ph-kanban"></i>
                            <span>Thêm Project</span>
                        </button>
                        <button class="action-btn">
                            <i class="ph ph-lock-key"></i>
                            <span>Lưu Password</span>
                        </button>
                        <button class="action-btn">
                            <i class="ph ph-code"></i>
                            <span>Tạo Code Snippet</span>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
<script>
// Dashboard Stats Renderer
(function() {
    const stats = getDashboardStats();
    
    // Stat cards
    document.getElementById('statTotalHosting').textContent = stats.totalHostings;
    document.getElementById('statActiveHosting').textContent = stats.activeHostings + ' đang hoạt động';
    document.getElementById('statExpiring').textContent = stats.expiringSoon;
    document.getElementById('statDoing').textContent = stats.doingProjects.length;
    document.getElementById('statTesting').textContent = stats.testingProjects.length;
    document.getElementById('statTestingValue').textContent = formatVNDShort(stats.testingValue);
    document.getElementById('statRevenue').textContent = formatVNDShort(stats.totalRevenue).replace(' VNĐ', '');
    document.getElementById('statTotalRevenue').textContent = formatVNDShort(stats.totalPotentialRevenue).replace(' VNĐ', '');

    
    // Hosting Status List (Column 1)
    const hostListContainer = document.getElementById('dashHostingList');
    let hostHtml = '';
    
    // Combine and sort by urgency (Expired first, then Expiring Soon)
    const urgentHostings = [...stats.expiredList, ...stats.expiringSoonList];
    
    urgentHostings.forEach(h => {
        const isExpired = h.diffDays < 0;
        const iconColor = isExpired ? 'color-red' : 'color-orange';
        const metaClass = isExpired ? 'error-text' : 'orange-text';
        const subtext = isExpired ? 'Đã hết hạn' : `Còn ${h.diffDays} ngày`;
        
        hostHtml += `
            <div class="list-item">
                <div class="item-icon ${iconColor}"><i class="ph ph-warning-circle"></i></div>
                <div class="item-content">
                    <h4>${h.name}</h4>
                    <p>${subtext}</p>
                </div>
                <div class="item-meta ${metaClass}">${formatDateVN(h.expDate)}</div>
            </div>
        `;
    });
    
    if (urgentHostings.length === 0) {
        hostHtml = '<div class="list-item"><div class="item-content"><p class="text-muted">Không có hosting nào chuẩn bị hết hạn</p></div></div>';
    }
    hostListContainer.innerHTML = hostHtml;

    // Project Progress List (Column 2)
    const listContainer = document.getElementById('dashProjectList');
    const activeProjects = PROJECTS.filter(p => p.status === 'doing' || p.status === 'testing');
    
    activeProjects.forEach(p => {
        const isTest = p.status === 'testing';
        const iconColor = isTest ? 'color-purple' : 'color-blue';
        const iconClass = isTest ? 'ph-check-circle' : 'ph-folder';
        const metaColor = isTest ? 'purple-text' : 'blue-text';
        const metaLabel = isTest ? 'Chờ nghiệm thu' : 'Đang thực hiện';
        
        listContainer.innerHTML += `
            <div class="list-item">
                <div class="item-icon ${iconColor}"><i class="ph ${iconClass}"></i></div>
                <div class="item-content">
                    <h4>${p.name}</h4>
                    <p>${p.customer}</p>
                </div>
                <div class="item-meta ${metaColor}">${metaLabel}</div>
            </div>
        `;
    });
    
    if (activeProjects.length === 0) {
        listContainer.innerHTML = '<div class="list-item"><div class="item-content"><p class="text-muted">Không có dự án nào đang thực hiện</p></div></div>';
    }
})();
</script>

</html>
