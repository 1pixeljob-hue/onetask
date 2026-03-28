<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - 1Pixel Dashboard</title>
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
                <a href="/logs" class="nav-item">
                    <i class="ph ph-file-text"></i>
                    <span>Logs</span>
                </a>
                <a href="/settings" class="nav-item active">
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
                    <h1>Cài Đặt</h1>
                    <p>Cấu hình hệ thống</p>
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
                
                <h2 class="settings-page-title">Cài Đặt</h2>

                <!-- Card 1: Supabase Database -->
                <div class="db-card">
                    <div class="db-header">
                        <div class="db-icon"><i class="ph ph-database"></i></div>
                        <div>
                            <h3>Supabase Cloud Database</h3>
                            <p>Hệ thống lưu trữ dữ liệu tập trung</p>
                        </div>
                    </div>
                    
                    <div class="db-stats">
                        <div class="db-stat-item">
                            <h4>23</h4>
                            <p>Hostings</p>
                        </div>
                        <div class="db-stat-item">
                            <h4>27</h4>
                            <p>Projects</p>
                        </div>
                        <div class="db-stat-item">
                            <h4>22</h4>
                            <p>Hoạt động</p>
                        </div>
                        <div class="db-stat-item">
                            <h4 class="text-yellow">1</h4>
                            <p>Sắp hết hạn</p>
                        </div>
                    </div>
                    
                    <div class="db-footer">
                        <div class="db-f-left">
                            <i class="ph ph-circles-three"></i> Trạng thái kết nối
                        </div>
                        <div class="db-f-right">
                            <span class="status-dot green"></span> Đã kết nối
                        </div>
                    </div>
                </div>

                <!-- Card 2: Notifications -->
                <div class="setting-card">
                    <div class="sc-header">
                        <div class="sc-icon"><i class="ph ph-bell-ringing"></i></div>
                        <h3>Cài Đặt Thông Báo</h3>
                    </div>
                    <div class="sc-body">
                        <div class="form-group">
                            <label>Cảnh báo trước khi hết hạn (ngày)</label>
                            <input type="number" class="form-control st-input" value="30">
                            <span class="form-help">Hệ thống sẽ hiển thị cảnh báo khi hosting còn 30 ngày nữa là hết hạn.</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Google Calendar -->
                <div class="setting-card">
                    <div class="sc-header">
                        <div class="sc-icon"><i class="ph ph-calendar-check"></i></div>
                        <div>
                            <h3>Google Calendar Integration</h3>
                            <p class="sc-subtitle">Tự động đồng bộ ngày hết hạn hosting lên Google Calendar</p>
                        </div>
                    </div>
                    
                    <div class="sc-body">
                        <!-- Connect Box -->
                        <div class="integration-box">
                            <div class="ib-title">
                                <i class="ph ph-calendar-plus text-blue"></i>
                                <strong>Kết nối Google Calendar</strong>
                            </div>
                            <p class="ib-desc">Sau khi kết nối, hệ thống sẽ tự động tạo 3 sự kiện nhắc nhở cho mỗi hosting:</p>
                            <ul class="ib-list">
                                <li><span class="dot d-blue"></span> 7 ngày trước hết hạn</li>
                                <li><span class="dot d-orange"></span> 1 ngày trước hết hạn</li>
                                <li><span class="dot d-red"></span> Ngày hết hạn</li>
                            </ul>
                            <button class="btn-connect-gg"><i class="ph ph-google-logo"></i> Kết nối Google Calendar</button>
                        </div>

                        <!-- Note Box -->
                        <div class="note-box mt-20">
                            <div class="nb-title">
                                <i class="ph ph-lightbulb text-yellow-drk"></i>
                                <strong>Lưu ý:</strong>
                            </div>
                            <ul class="nb-list">
                                <li>Mỗi hosting sẽ tạo 3 events với màu sắc khác nhau</li>
                                <li>Email & popup reminder sẽ được gửi tự động</li>
                                <li>Thêm/Sửa/Xóa hosting sẽ tự động cập nhật Calendar</li>
                                <li>Xem events tại <a href="https://calendar.google.com" target="_blank" class="text-blue">calendar.google.com</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
