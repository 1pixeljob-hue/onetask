<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>">
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
        <?php 
            $activePage = 'settings';
            include APP_DIR . '/Views/partials/sidebar.php'; 
        ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <?php 
                $pageTitle = 'Cài Đặt';
                $pageSubtitle = 'Cấu hình hệ thống và tài khoản';
                include APP_DIR . '/Views/partials/header.php'; 
            ?>

            <div class="content-body">
                
                <!-- Card 1: MySQL Database -->
                <div class="db-card">
                    <div class="db-header">
                        <div class="db-icon"><i class="ph ph-database"></i></div>
                        <div>
                            <h3>MySQL Database System</h3>
                            <p>Hệ thống lưu trữ dữ liệu tập trung</p>
                        </div>
                    </div>
                    
                    <div class="db-stats">
                        <div class="db-stat-item">
                            <h4><?= $stats['total_hostings'] ?? 0 ?></h4>
                            <p>Hostings</p>
                        </div>
                        <div class="db-stat-item">
                            <h4><?= $stats['total_projects'] ?? 0 ?></h4>
                            <p>Projects</p>
                        </div>
                        <div class="db-stat-item">
                            <h4><?= $stats['active_items'] ?? 0 ?></h4>
                            <p>Hoạt động</p>
                        </div>
                        <div class="db-stat-item">
                            <h4 class="text-yellow"><?= $stats['expiring_soon'] ?? 0 ?></h4>
                            <p>Sắp hết hạn</p>
                        </div>
                    </div>
                    
                    <div class="db-footer">
                        <div class="db-f-left">
                            <i class="ph ph-circles-three"></i> Trạng thái kết nối
                        </div>
                        <div class="db-f-right">
                            <span class="status-dot green"></span> Đã kết nối (MySQL)
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
    <?php include APP_DIR . '/Views/partials/footer.php'; ?>
</body>
</html>
