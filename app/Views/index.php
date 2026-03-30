<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data injected from PHP Models
        const PHP_DATA = {
            projects: <?php echo json_encode($projects); ?>,
            hostings: <?php echo json_encode($hostings); ?>,
            monthlyRevenue: <?php echo json_encode($monthlyRevenue); ?>,
            recentLogs: <?php echo json_encode($recentLogs ?? []); ?>
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
                    <h2 id="greetingText">Chào buổi tối, <span class="highlight">Quydev</span></h2>
                    <p>Đây là những gì đang diễn ra trong hệ thống hôm nay</p>
                </div>

                <script>
                    function updateGreeting() {
                        const hour = new Date().getHours();
                        let greeting = "Chào buổi tối";
                        if (hour >= 5 && hour < 12) greeting = "Chào buổi sáng";
                        else if (hour >= 12 && hour < 14) greeting = "Chào buổi trưa";
                        else if (hour >= 14 && hour < 18) greeting = "Chào buổi chiều";

                        const greetingElement = document.getElementById('greetingText');
                        if (greetingElement) {
                            greetingElement.innerHTML = `${greeting}, <span class="highlight">Quydev</span>`;
                        }
                    }
                    updateGreeting();
                </script>

                <!-- Layer 1: Stats Grid V2 -->
                <div class="stats-grid-v2">

                    <!-- Stat 1: Hosting -->
                    <div class="stat-card-v2">
                        <span class="badge-v2 badge-active">Hoạt động</span>
                        <div class="icon-box icon-blue">
                            <i class="ph ph-database"></i>
                        </div>
                        <div class="stat-label">Tổng số Hosting</div>
                        <div class="stat-main">
                            <span class="stat-value-big" id="statTotalHosting">0</span>
                            <span class="stat-sub" id="statActiveHosting">cụm đang chạy</span>
                        </div>
                    </div>

                    <!-- Stat 2: Expiring -->
                    <div class="stat-card-v2">
                        <span class="badge-v2 badge-warning">Gia hạn</span>
                        <div class="icon-box icon-orange">
                            <i class="ph ph-bell-ringing"></i>
                        </div>
                        <div class="stat-label">Sắp hết hạn</div>
                        <div class="stat-main">
                            <span class="stat-value-big" id="statExpiring">0</span>
                            <span class="stat-sub">Cần chú ý</span>
                        </div>
                    </div>

                    <!-- Stat 3: Projects -->
                    <div class="stat-card-v2">
                        <span class="badge-v2 badge-running">Đang chạy</span>
                        <div class="icon-box icon-green">
                            <i class="ph ph-projector-screen-chart"></i>
                        </div>
                        <div class="stat-label">Dự án đang thực hiện</div>
                        <div class="stat-main">
                            <span class="stat-value-big" id="statDoing">0</span>
                            <span class="stat-sub" id="statSprint">Sprint hiện tại</span>
                        </div>
                    </div>

                    <!-- Stat 4: Revenue -->
                    <div class="stat-card-v2" style="border-left: 4px solid var(--primary-color);">
                        <div class="icon-box icon-purple">
                            <i class="ph ph-currency-dollar"></i>
                        </div>
                        <div class="stat-label">Doanh thu hoàn tất</div>
                        <div class="stat-main">
                            <span class="stat-value-big" id="statRevenue">0</span>
                            <span class="stat-sub">+14%</span>
                        </div>
                    </div>
                </div>



                <!-- Layer 2: Middle Section (Chart + Progress) -->
                <div class="middle-section-grid">
                    <!-- Revenue Trend Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="chart-title">
                                <h3>Xu hướng doanh thu</h3>
                                <p>Theo dõi hiệu quả tài chính</p>
                            </div>
                            <div class="chart-total">
                                <span>TỔNG DOANH THU</span>
                                <h2 id="chartTotalValue" style="color: #2ab89c;">$0</h2>
                            </div>
                        </div>
                        <div class="chart-wrapper" style="height: 320px; position: relative;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Project Progress Sidebar -->
                    <div class="progress-card">
                        <div class="progress-header">
                            <h3>Tiến độ dự án</h3>
                        </div>
                        <div class="card-list" id="sideProjectList" style="max-height: 480px; overflow-y: auto; padding-right: 4px;">
                            <!-- Rendered by JS -->
                        </div>

                        <!-- Milestone Card Container -->
                        <div id="milestoneContainer">
                            <!-- No Progress Reminder -->
                            <div class="milestone-footer reminder-card" id="noProgressReminder"
                                style="display: none; background: #f8fafc; border: 1px dashed #e2e8f0; box-shadow: none;">
                                <div class="milestone-icon" style="background: #f1f5f9; color: #94a3b8;">
                                    <i class="ph ph-sparkle"></i>
                                </div>
                                <div class="milestone-text">
                                    <span style="color: #64748b;">Chưa có dự án nào!!</span>
                                    <p style="color: #475569; font-size: 13px; font-weight: 500;">Hãy thêm dự án mới để
                                        bắt đầu ngay!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Layer 3: Bottom Section (Status + Activity) -->
                <div class="dashboard-main-grid">
                    <!-- Hosting Status -->
                    <div class="hosting-status-card">
                        <div class="card-header-v2">
                            <h3>Tình trạng Hosting</h3>
                            <i class="ph ph-warning-circle" style="color: #ef4444; font-size: 20px; font-weight: 800;"></i>
                        </div>
                        <div class="hosting-status-list" id="dashHostingStatusList">
                            <!-- Rendered by JS -->
                        </div>
                        <div class="card-footer-v2">
                            <a href="/hostings" class="footer-btn-red">QUẢN LÝ GIA HẠN</a>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="activity-card-v2">
                        <div class="card-header-v2">
                            <h3>Hoạt động gần đây</h3>
                            <a href="/logs" class="card-link">XEM TẤT CẢ</a>
                        </div>
                        <div class="activity-list" id="dashActivityList">
                            <!-- Rendered by JS -->
                        </div>
                    </div>
                </div>

                <!-- Layer 4: Quick Actions Full Width -->
                <div class="quick-actions-card-full">
                    <div class="card-header-v2" style="margin-bottom: 24px;">
                        <h3>Thao tác nhanh</h3>
                        <div class="header-actions-right">
                           <a href="#" class="header-action-link"><i class="ph ph-export"></i> XUẤT NHẬT KÝ</a>
                           <a href="#" class="header-action-link"><i class="ph ph-share-network"></i> CHIA SẺ BÁO CÁO</a>
                        </div>
                    </div>
                    <div class="quick-actions-grid-v3">
                        <button class="action-item-v3" onclick="openHostingModal()">
                            <div class="action-icon-box" style="background: #eff6ff; color: #3b82f6;">
                                <i class="ph ph-plus"></i>
                            </div>
                            <span>Thêm Hosting</span>
                        </button>
                        <button class="action-item-v3" onclick="openAddProjectModal()">
                            <div class="action-icon-box" style="background: #ecfdf5; color: #10b981;">
                                <i class="ph ph-folder-plus"></i>
                            </div>
                            <span>Thêm Dự án</span>
                        </button>
                        <button class="action-item-v3" onclick="openPasswordModal()">
                            <div class="action-icon-box" style="background: #fdf2f8; color: #db2777;">
                                <i class="ph ph-key"></i>
                            </div>
                            <span>Lưu Mật khẩu</span>
                        </button>
                        <button class="action-item-v3" onclick="openAddSnippetModal()">
                            <div class="action-icon-box" style="background: #f5f3ff; color: #8b5cf6;">
                                <i class="ph ph-code"></i>
                            </div>
                            <span>Tạo Snippet</span>
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
<script>
    // Dashboard Premium Renderer
    (function () {
        const stats = getDashboardStats();

        // Layer 1: Stat cards V2
        if (document.getElementById('statTotalHosting')) {
            document.getElementById('statTotalHosting').textContent = stats.totalHostings;
            document.getElementById('statActiveHosting').textContent = `${stats.activeHostings} cụm đang chạy`;
            document.getElementById('statExpiring').textContent = stats.expiringSoon;
            // "Dự án đang thực hiện" counts both doing and testing
            document.getElementById('statDoing').textContent = stats.doingProjects.length + stats.testingProjects.length;
            document.getElementById('statRevenue').textContent = formatVNDShort(stats.totalRevenue).replace(' VNĐ', '');
            if (document.getElementById('chartTotalValue')) {
                document.getElementById('chartTotalValue').textContent = formatVNDShort(stats.totalPotentialRevenue);
            }
        }

        // Layer 2: Revenue Chart
        const ctx = document.getElementById('revenueChart');
        if (ctx) {
            const currentYear = new Date().getFullYear();
            const monthlyData = getMonthlyBreakdown(currentYear);
            const labels = monthlyData.map(m => 'Tháng ' + m.month);

            const projData = monthlyData.map(m => m.projectValue);
            const hostData = monthlyData.map(m => m.hostingValue);
            const totalData = monthlyData.map(m => m.total);

            // Formatter for Y axis / Totals
            const formatM = (val) => {
                if (val >= 1000000000) return '$' + (val / 1000000000).toFixed(1) + 'B';
                if (val >= 1000000) return '$' + (val / 1000000).toFixed(1) + 'M';
                if (val >= 1000) return '$' + (val / 1000).toFixed(0) + 'K';
                return '$' + val;
            };

            // Update Total Display in header (Grand Total for the year)
            const totalRev = totalData.reduce((a, b) => a + b, 0);
            if (document.getElementById('chartTotalValue')) {
                document.getElementById('chartTotalValue').textContent = formatM(totalRev);
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Projects',
                            data: projData,
                            borderColor: '#2ab89c',
                            borderWidth: 2,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#2ab89c',
                            fill: false
                        },
                        {
                            label: 'Hosting',
                            data: hostData,
                            borderColor: '#3b82f6',
                            borderWidth: 2,
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#3b82f6',
                            fill: false
                        },
                        {
                            label: 'Tổng',
                            data: totalData,
                            borderColor: '#10b981',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.4,
                            pointRadius: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#10b981',
                            fill: true,
                            backgroundColor: function (context) {
                                const chart = context.chart;
                                const { ctx, chartArea } = chart;
                                if (!chartArea) return null;
                                const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.01)');
                                gradient.addColorStop(1, 'rgba(16, 185, 129, 0.1)');
                                return gradient;
                            }
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: { size: 12, weight: '500' },
                                color: '#64748b'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function (context) {
                                    return ` ${context.dataset.label}: ${formatM(context.raw)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            display: true,
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 10, weight: '600' },
                                callback: function (value) {
                                    return formatM(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 10, weight: '600' }
                            }
                        }
                    }
                }
            });
        }

        // Layer 2: Project Progress Sidebar & Milestone Logic
        const sideProjectContainer = document.getElementById('sideProjectList');
        const noProgressReminder = document.getElementById('noProgressReminder');

        if (sideProjectContainer) {
            // Priority: doing (1), testing (2), planning (3), paused (4), done (5)
            const statusPriority = { 'doing': 1, 'testing': 2, 'planning': 3, 'paused': 4, 'done': 5 };
            const activeProjects = PROJECTS.filter(p => {
                const s = (p.status || '').toLowerCase().trim();
                return ['planning', 'doing', 'testing', 'paused'].includes(s); // Focus on non-completed
            }).sort((a, b) => {
                const sA = (a.status || '').toLowerCase().trim();
                const sB = (b.status || '').toLowerCase().trim();
                return (statusPriority[sA] || 99) - (statusPriority[sB] || 99);
            });

            const displayProjects = activeProjects.slice(0, 10);

            // Toggle Reminder
            if (activeProjects.length > 0) {
                if (noProgressReminder) noProgressReminder.style.display = 'none';
            } else {
                if (noProgressReminder) noProgressReminder.style.display = 'flex';
            }

            let html = '';
            if (activeProjects.length === 0) {
                html = '<div class="text-center py-4 text-muted" style="font-size: 13px;">Không có dự án đang thực hiện</div>';
            } else {
                displayProjects.forEach(p => {
                    const statusLower = (p.status || '').toLowerCase().trim();
                    let badgeClass = 'status-badge-v3-running';
                    let label = 'Đang thực hiện';
                    
                    if (statusLower === 'planning') { 
                        badgeClass = 'status-badge-v3-warning'; 
                        label = 'Lên kế hoạch'; 
                    } else if (statusLower === 'testing') { 
                        badgeClass = 'status-badge-v3-testing'; 
                        label = 'Chờ nghiệm thu'; 
                    } else if (statusLower === 'done') { 
                        badgeClass = 'status-badge-v3-active'; 
                        label = 'Hoàn thành'; 
                    } else if (statusLower === 'paused') {
                        badgeClass = 'status-badge-v3-paused';
                        label = 'Tạm dừng';
                    }

                    html += `
                    <div class="progress-item">
                        <span class="progress-name">${p.name}</span>
                        <span class="status-badge-v3 ${badgeClass}">${label}</span>
                    </div>
                `;
                });
            }
            sideProjectContainer.innerHTML = html;
        }

        // Layer 3.1: Hosting Status (NEW)
        function renderHostingStatus(stats) {
            const container = document.getElementById('dashHostingStatusList');
            if (!container) return;

            const list = [...(stats.expiredList || []), ...(stats.expiringSoonList || [])].slice(0, 5);
            
            if (list.length === 0) {
                container.innerHTML = '<div class="text-center py-4 text-muted" style="font-size: 13px;">Tất cả hosting đều ổn định</div>';
                return;
            }

            let html = '';
            list.forEach(item => {
                const isExpired = item.diffDays < 0;
                const badgeClass = isExpired ? 'badge-red' : 'badge-orange';
                const badgeLabel = isExpired ? 'HẾT HẠN' : 'SẮP HẾT HẠN';
                
                html += `
                <div class="status-item-v3">
                    <div class="status-info">
                        <span class="status-name">${item.name}</span>
                        <span class="status-date">${formatDateVN(item.expDate)}</span>
                    </div>
                    <span class="status-badge-mini ${badgeClass}">${badgeLabel}</span>
                </div>
                `;
            });
            container.innerHTML = html;
        }
        renderHostingStatus(stats);

        // Layer 3.2: Recent Activity (Dynamic)
        const activityContainer = document.getElementById('dashActivityList');
        if (activityContainer) {
            const logs = PHP_DATA.recentLogs || [];

            if (logs.length === 0) {
                activityContainer.innerHTML = '<div class="text-center py-5 text-muted" style="font-size: 13px;">Chưa có hoạt động nào được ghi lại</div>';
            } else {
                function timeAgo(dateString) {
                    const now = new Date();
                    const past = new Date(dateString);
                    const diffMs = now - past;
                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMins / 60);
                    const diffDays = Math.floor(diffHours / 24);

                    if (diffMins < 1) return 'VỪA XONG';
                    if (diffMins < 60) return `${diffMins} PHÚT TRƯỚC`;
                    if (diffHours < 24) return `${diffHours} GIỜ TRƯỚC`;
                    if (diffDays === 1) return 'HÔM QUA';
                    return past.toLocaleDateString('vi-VN');
                }

                let actHtml = '';
                logs.forEach(log => {
                    const userName = log.user_name || 'quy';
                    const userInitial = userName.substring(0, 1).toUpperCase();
                    
                    // Module mapping
                    const moduleMap = {
                        'Project': 'dự án',
                        'Hosting': 'hosting',
                        'Passwords': 'passwords',
                        'CodeX': 'codex'
                    };
                    const moduleName = moduleMap[log.module] || (log.module ? log.module.toLowerCase() : '');

                    // Get random color for avatar if not existing
                    const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                    const colorIndex = userName.length % colors.length;
                    const avatarColor = colors[colorIndex];

                    actHtml += `
                    <div class="activity-item-v3">
                        <div class="activity-avatar" style="background: ${avatarColor}">
                            ${userInitial}
                        </div>
                        <div class="activity-details">
                            <p>
                                <b style="color: #1e293b;">${userName}</b> đã ${log.action.toLowerCase()}${moduleName ? ' ' + moduleName : ''} 
                                <span class="activity-link">${log.item_name}</span>
                            </p>
                            <span class="activity-time">${timeAgo(log.created_at)}</span>
                        </div>
                    </div>
                    `;
                });
                activityContainer.innerHTML = actHtml;
            }
        }
    })();

</script>

</html>