<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1Pixel Dashboard</title>
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
                <a href="/" class="nav-item active">
                    <i class="ph ph-squares-four"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/hostings" class="nav-item">
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
                        <div class="stat-value">23</div>
                        <div class="stat-desc">22 đang hoạt động</div>
                    </div>
                    <div class="stat-card warning-card">
                        <div class="stat-header">
                            <span class="stat-title">Sắp Hết Hạn</span>
                            <i class="ph ph-warning-circle color-orange"></i>
                        </div>
                        <div class="stat-value">1</div>
                        <div class="stat-desc">hosting cần gia hạn</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Đang Thực Hiện</span>
                            <i class="ph ph-folder color-blue"></i>
                        </div>
                        <div class="stat-value">1</div>
                        <div class="stat-desc">đang triển khai</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Chờ Nghiệm Thu</span>
                            <i class="ph ph-check-circle color-purple"></i>
                        </div>
                        <div class="stat-value">2</div>
                        <div class="stat-desc">5.5M VNĐ</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-title">Doanh Thu</span>
                            <i class="ph ph-trend-up color-green"></i>
                        </div>
                        <div class="stat-value">112.0M</div>
                        <div class="stat-desc">VNĐ hoàn thành</div>
                    </div>
                </div>

                <!-- Three Columns Section -->
                <div class="dashboard-columns">
                    <!-- Column 1 -->
                    <div class="board-column">
                        <h3 class="section-title">Tình Trạng Hosting</h3>
                        <div class="card-list">
                            <div class="list-item">
                                <div class="item-icon color-orange"><i class="ph ph-warning-circle"></i></div>
                                <div class="item-content">
                                    <h4>Photoeditor 24h</h4>
                                    <p>Còn 17 ngày</p>
                                </div>
                                <div class="item-meta error-text">
                                    17/04/2026
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="board-column">
                        <h3 class="section-title">Tiến Độ Dự Án</h3>
                        <div class="card-list">
                            <div class="list-item">
                                <div class="item-icon color-purple"><i class="ph ph-check-circle"></i></div>
                                <div class="item-content">
                                    <h4>Onelaw Code section tài liệu kèm iframe view</h4>
                                    <p>Onelaw</p>
                                </div>
                                <div class="item-meta purple-text">
                                    Chờ nghiệm thu
                                </div>
                            </div>
                            <div class="list-item">
                                <div class="item-icon color-purple"><i class="ph ph-check-circle"></i></div>
                                <div class="item-content">
                                    <h4>Thiết kế web Nam Việt Food Land</h4>
                                    <p>Anh Nguyễn Sư</p>
                                </div>
                                <div class="item-meta purple-text">
                                    Chờ nghiệm thu
                                </div>
                            </div>
                            <div class="list-item">
                                <div class="item-icon color-blue"><i class="ph ph-folder"></i></div>
                                <div class="item-content">
                                    <h4>Thêm sản phẩm cho web Trái Cây Lâm Thành</h4>
                                    <p>Khánh Linh</p>
                                </div>
                                <div class="item-meta blue-text">
                                    Đang thực hiện
                                </div>
                            </div>
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
</html>
