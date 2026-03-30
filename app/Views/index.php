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
            recentLogs: <?php echo json_encode($recentLogs ?? []); ?>,
            password_categories: <?php echo json_encode($password_categories ?? []); ?>,
            snippet_categories: <?php echo json_encode($snippet_categories ?? []); ?>
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

    <!-- DASHBOARD QUICK ACTION MODALS -->
    <!-- Modal Thêm Hosting Mới (Dashboard) -->
    <div class="modal-overlay" id="hostingModal" onclick="closeHostingModal(event)">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <div class="modal-icon-brand"><i id="modalBrandIcon" class="ph-fill ph-hard-drives"></i></div>
                    <h3 class="modal-title" id="modalTitle">Thêm Hosting Mới</h3>
                </div>
                <button class="modal-close" onclick="closeHostingModalBtn()"><i class="ph ph-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="modal-field full">
                    <label class="modal-label">Tên Hosting <span class="req">*</span></label>
                    <input type="text" class="modal-input" id="mHostingName" placeholder="VD: Website Chính">
                </div>
                <div class="modal-row">
                    <div class="modal-field">
                        <label class="modal-label">Tên Miền <span class="req">*</span></label>
                        <input type="text" class="modal-input" id="mDomain" placeholder="VD: example.com">
                    </div>
                    <div class="modal-field">
                        <label class="modal-label">Nhà Cung Cấp <span class="req">*</span></label>
                        <input type="text" class="modal-input" id="mProvider" placeholder="iNet" value="iNet">
                    </div>
                </div>
                <div class="modal-row">
                    <div class="modal-field">
                        <label class="modal-label">Ngày Đăng Ký <span class="req">*</span></label>
                        <input type="date" class="modal-input" id="mRegDate">
                    </div>
                    <div class="modal-field">
                        <label class="modal-label">Ngày Hết Hạn <span class="req">*</span></label>
                        <input type="date" class="modal-input" id="mExpDate">
                    </div>
                </div>
                <div class="modal-field full">
                    <label class="modal-label">Giá (VNĐ) <span class="req">*</span></label>
                    <input type="number" class="modal-input" id="hostingPrice" value="1100000" oninput="formatPrice()">
                    <div class="modal-price-hint" id="priceHint">1.100.000 VNĐ</div>
                </div>
                <div class="modal-field full">
                    <label class="modal-label">Ghi Chú</label>
                    <textarea class="modal-textarea" id="mHostingNotes" placeholder="Thêm ghi chú về hosting này..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn-cancel" onclick="closeHostingModalBtn()">Hủy</button>
                <button class="modal-btn-submit" id="modalSubmitBtn" onclick="submitHostingForm()"><i class="ph ph-plus"></i> Thêm Mới</button>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Project Mới (Dashboard) -->
    <div class="modal-overlay" id="addProjectModal" onclick="closeAddProjectModalOverlay(event)">
        <div class="modal-box scrollable">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <div class="modal-icon-brand"><i class="ph-fill ph-folders"></i></div>
                    <h3 class="modal-title">Thêm Project Mới</h3>
                </div>
                <button class="modal-close" onclick="closeAddProjectModal()"><i class="ph ph-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="modal-section-header"><span class="modal-section-title">Thông Tin Project</span></div>
                <div class="modal-field full">
                    <label class="modal-label">Tên Project <span class="req">*</span></label>
                    <input type="text" class="modal-input" id="mProjectName" placeholder="VD: Website Thương Mại Điện Tử">
                </div>
                <div class="modal-field full">
                    <label class="modal-label">Trạng Thái <span class="req">*</span></label>
                    <div class="pj-modal-select" id="mProjectStatusSelect" data-input-id="mProjectStatus">
                        <div class="pj-modal-select-trigger" onclick="togglePjModalSelect(this)">
                            <span>Lên Kế Hoạch</span>
                            <i class="ph ph-caret-down trigger-chevron"></i>
                        </div>
                        <div class="pj-modal-select-menu pj-dropdown" style="width: 100%; right: auto; left: 0;">
                            <div class="pj-dropdown-item active" data-value="planning"><span>Lên Kế Hoạch</span></div>
                            <div class="pj-dropdown-item" data-value="doing"><span>Đang Thực Hiện</span></div>
                            <div class="pj-dropdown-item" data-value="testing"><span>Chờ Nghiệm Thu</span></div>
                            <div class="pj-dropdown-item" data-value="done"><span>Hoàn Thành</span></div>
                            <div class="pj-dropdown-item" data-value="paused"><span>Tạm Dừng</span></div>
                        </div>
                        <input type="hidden" id="mProjectStatus" value="planning">
                    </div>
                </div>
                <div class="modal-field full">
                    <label class="modal-label">Mô Tả</label>
                    <textarea class="modal-textarea" id="mProjectDesc" placeholder="Mô tả chi tiết về project..."></textarea>
                </div>
                <div class="modal-field full">
                    <label class="modal-label">Ngày Tạo <span class="req">*</span></label>
                    <input type="date" class="modal-input" id="mProjectDate">
                </div>
                <div class="modal-section-header with-border"><span class="modal-section-title">Thông Tin Khách Hàng</span></div>
                <div class="modal-row">
                    <div class="modal-field">
                        <label class="modal-label">Tên Khách Hàng <span class="req">*</span></label>
                        <input type="text" class="modal-input" id="mCustomerName" placeholder="VD: Nguyễn Văn A">
                    </div>
                    <div class="modal-field">
                        <label class="modal-label">Số Điện Thoại</label>
                        <input type="text" class="modal-input" id="mCustomerPhone" placeholder="VD: 0912345678">
                    </div>
                </div>
                <div class="modal-section-header with-border"><span class="modal-section-title">Thông Tin Quản Trị</span></div>
                <div class="modal-field full">
                    <label class="modal-label">Đường Dẫn Admin</label>
                    <input type="text" class="modal-input" id="mAdminLink" placeholder="VD: https://example.com/admin">
                </div>
                <div class="modal-row">
                    <div class="modal-field">
                        <label class="modal-label">Tài Khoản Admin</label>
                        <input type="text" class="modal-input" id="mAdminUser" placeholder="VD: admin">
                    </div>
                    <div class="modal-field">
                        <label class="modal-label">Mật Khẩu Admin</label>
                        <div class="modal-input-group">
                            <input type="password" class="modal-input" id="adminPassword" placeholder="********">
                            <button type="button" class="modal-input-icon-btn" onclick="togglePasswordVisibility('adminPassword', this)">
                                <i class="ph ph-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-section-header with-border"><span class="modal-section-title">Thông Tin Tài Chính</span></div>
                <div class="modal-field full">
                    <label class="modal-label">Giá Trị Dự Án (VNĐ)</label>
                    <div class="modal-input-group">
                        <i class="ph ph-currency-circle-dollar modal-input-prefix"></i>
                        <input type="number" class="modal-input with-prefix" id="projectValue" value="0" oninput="updateProjectValueDisplay(this)">
                    </div>
                    <div id="projectValueDisplay" class="modal-price-hint">0 VNĐ</div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn-cancel" onclick="closeAddProjectModal()">Hủy</button>
                <button class="modal-btn-submit" onclick="submitProjectForm()">Thêm Mới</button>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Mật Khẩu Mới (Dashboard) -->
    <div class="modal-overlay" id="addPasswordModal" onclick="closeAddPasswordModalOverlay(event)">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title-wrap">
                    <div class="modal-icon-brand" style="background: #ecfdf5; color: #10b981;">
                        <i class="ph-fill ph-key"></i>
                    </div>
                    <h3 class="modal-title">Thêm Mật Khẩu Mới</h3>
                </div>
                <button class="modal-close" onclick="closeAddPwdModal()"><i class="ph ph-x"></i></button>
            </div>
            <form id="addPasswordForm" onsubmit="submitAddPwdForm(event)">
                <div class="modal-body">
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-pencil-line"></i> Tiêu Đề <span class="req">*</span></label>
                        <input type="text" class="modal-input" id="mPwdTitle" placeholder="VD: Gmail - Công Ty" required>
                    </div>
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-globe"></i> Website</label>
                        <input type="text" class="modal-input" id="mPwdUrl" placeholder="VD: https://gmail.com hoặc gmail.com">
                    </div>
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-tag"></i> Danh Mục <span class="req">*</span></label>
                        <div class="pj-modal-select" id="mPwdCategorySelect" data-input-id="mPwdCategory">
                            <div class="pj-modal-select-trigger" onclick="togglePjModalSelect(this)">
                                <span>Email</span>
                                <i class="ph ph-caret-down trigger-chevron"></i>
                            </div>
                            <div class="pj-modal-select-menu pj-dropdown" style="width: 100%; right: auto; left: 0;">
                                <?php foreach ($password_categories as $cat): ?>
                                    <div class="pj-dropdown-item" data-value="<?php echo htmlspecialchars($cat['name']); ?>"><?php echo htmlspecialchars($cat['name']); ?></div>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" id="mPwdCategory" value="Email" required>
                        </div>
                    </div>
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-user"></i> Tài Khoản <span class="req">*</span></label>
                        <input type="text" class="modal-input" id="mPwdUser" placeholder="VD: user@example.com" required>
                    </div>
                    <div class="modal-field full">
                        <div class="label-with-action">
                            <label class="modal-label"><i class="ph ph-lock"></i> Mật Khẩu <span class="req">*</span></label>
                            <a href="javascript:void(0)" class="label-link" onclick="generateStrongPwd()">Tạo mật khẩu mạnh</a>
                        </div>
                        <div class="modal-input-group">
                            <input type="password" class="modal-input" id="mPwdPass" placeholder="••••••••" required>
                            <button type="button" class="modal-input-icon-btn" onclick="toggleAddPwdVisibility()">
                                <i class="ph ph-eye" id="mPwdEyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-field full">
                        <label class="modal-label"><i class="ph ph-note"></i> Ghi Chú</label>
                        <textarea class="modal-textarea" id="mPwdNotes" placeholder="Ghi chú bổ sung (tùy chọn)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-cancel" onclick="closeAddPwdModal()">Hủy</button>
                    <button type="submit" class="modal-btn-submit">Thêm Mới</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Thêm Code Snippet (Dashboard) -->
    <div id="cxModal" class="modal-overlay">
        <div class="modal-box cx-modal-box">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="cx-modal-icon"><i class="ph ph-code-simple"></i></div>
                    <span>Tạo Snippet Mới</span>
                </div>
                <button class="modal-close" onclick="closeCxModal()">&times;</button>
            </div>
            <form id="cxForm" onsubmit="submitCxForm(event)">
                <div class="modal-body">
                    <div class="modal-row">
                        <div class="modal-field">
                            <label class="modal-label">Tên Code <span class="req">*</span></label>
                            <input type="text" name="title" id="cxTitle" class="modal-input" placeholder="VD: React useEffect" required>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label">Loại Code <span class="req">*</span></label>
                            <div class="pj-modal-select" data-input-id="cxLangInput" id="cxLangSelect">
                                <div class="cx-badge-select-trigger pj-modal-select-trigger" onclick="togglePjModalSelect(this)">
                                    <span class="cx-lang-badge" id="cxLangBadge">JavaScript</span>
                                    <i class="ph ph-caret-down"></i>
                                </div>
                                <div class="pj-dropdown">
                                    <div class="pj-dropdown-list">
                                        <?php foreach ($snippet_categories as $cat): ?>
                                            <div class="pj-dropdown-item" data-value="<?php echo htmlspecialchars($cat['name']); ?>"><span><?php echo htmlspecialchars($cat['name']); ?></span></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="language" id="cxLangInput" value="JavaScript">
                        </div>
                    </div>
                    <div class="modal-field full">
                        <label class="modal-label">Mô Tả</label>
                        <input type="text" name="description" id="cxDesc" class="modal-input" placeholder="VD: Hook React">
                    </div>
                    <div class="modal-field full">
                        <label class="modal-label">Nội Dung Code <span class="req">*</span></label>
                        <div class="cx-code-editor-wrapper">
                            <textarea name="code" id="cxCodeArea" class="cx-code-textarea" placeholder="// Nhập code..." required oninput="updateCxStats()"></textarea>
                            <div class="cx-code-status-bar">
                                <span id="statLines">1 dòng</span> <span class="cx-dot">•</span> <span id="statChars">0 ký tự</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cx-cancel" onclick="closeCxModal()">Hủy</button>
                    <button type="submit" class="btn-cx-submit">Tạo Snippet</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Toast (Dashboard) -->
    <div class="delete-toast" id="deleteToast">
        <div class="toast-spinner" id="dtSpinner"></div>
        <div id="dtSuccessIcon" style="display:none; color: #10b981; font-size: 22px; align-items: center; justify-content: center;"><i class="ph-fill ph-check-circle"></i></div>
        <div id="dtErrorIcon" style="display:none; color: #ef4444; font-size: 22px; align-items: center; justify-content: center;"><i class="ph-fill ph-x-circle"></i></div>
        <span id="dtMessage" style="color: #1e293b; font-weight: 600;">Đang xử lý...</span>
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

    // --- DASHBOARD QUICK ACTION MODAL LOGIC ---
    function clearErrors() {
        document.querySelectorAll('.modal-input-error').forEach(el => el.classList.remove('modal-input-error'));
    }

    function markError(id, isCustom = false) {
        const el = document.getElementById(id);
        if (!el) return;
        if (isCustom) {
            const container = el.closest('.pj-modal-select');
            if (container) {
                container.classList.add('modal-input-error');
                const trigger = container.querySelector('.pj-modal-select-trigger');
                if (trigger) trigger.focus();
            }
        } else {
            el.classList.add('modal-input-error');
            if (id === 'cxCodeArea') {
                el.closest('.cx-code-editor-wrapper').classList.add('modal-input-error');
            }
            el.focus();
        }
    }

    function showToast(msg, icon = 'dtSpinner') {
        const t = document.getElementById('deleteToast');
        const m = document.getElementById('dtMessage');
        const s = document.getElementById('dtSpinner');
        const succ = document.getElementById('dtSuccessIcon');
        const err = document.getElementById('dtErrorIcon');
        if (!t) return;
        m.textContent = msg;
        t.classList.add('show');
        
        s.style.display = 'none';
        succ.style.display = 'none';
        err.style.display = 'none';

        if (icon === 'success') {
            succ.style.display = 'flex';
        } else if (icon === 'error') {
            err.style.display = 'flex';
        } else {
            s.style.display = 'block';
        }
    }
    function hideToast() {
        const t = document.getElementById('deleteToast');
        if (t) t.classList.remove('show');
    }

    // Modal Dropdown Helper
    function togglePjModalSelect(trigger) {
        clearErrors(); // Also clear errors when interacting
        const select = trigger.closest('.pj-modal-select');
        const menu = select.querySelector('.pj-dropdown');
        const isOpen = menu.classList.contains('active');
        document.querySelectorAll('.pj-dropdown.active').forEach(m => m.classList.remove('active'));
        if (!isOpen) menu.classList.add('active');
    }
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.pj-modal-select')) {
            document.querySelectorAll('.pj-dropdown.active').forEach(m => m.classList.remove('active'));
        }
    });

    // Hosting Logic
    function openHostingModal() { 
        clearErrors();
        document.getElementById('hostingModal').classList.add('active'); 
        document.getElementById('mRegDate').value = new Date().toISOString().split('T')[0];
    }
    function closeHostingModal(e) { if (e.target.id === 'hostingModal') closeHostingModalBtn(); }
    function closeHostingModalBtn() { 
        document.getElementById('hostingModal').classList.remove('active'); 
        clearErrors();
    }
    function formatPrice() {
        const p = document.getElementById('hostingPrice').value;
        const h = document.getElementById('priceHint');
        if (!p) { h.textContent = '0 VNĐ'; return; }
        h.textContent = new Intl.NumberFormat('vi-VN').format(p) + ' VNĐ';
    }
    async function submitHostingForm() {
        clearErrors();
        const data = {
            name: document.getElementById('mHostingName').value,
            domain: document.getElementById('mDomain').value,
            provider: document.getElementById('mProvider').value,
            regDate: document.getElementById('mRegDate').value,
            expDate: document.getElementById('mExpDate').value,
            price: document.getElementById('hostingPrice').value,
            notes: document.getElementById('mHostingNotes').value
        };

        if (!data.name) { showToast('Vui lòng nhập tên hosting!', 'error'); markError('mHostingName'); return; }
        if (!data.domain) { showToast('Vui lòng nhập tên miền!', 'error'); markError('mDomain'); return; }
        if (!data.regDate) { showToast('Vui lòng chọn ngày đăng ký!', 'error'); markError('mRegDate'); return; }
        if (!data.expDate) { showToast('Vui lòng chọn ngày hết hạn!', 'error'); markError('mExpDate'); return; }
        if (!data.price) { showToast('Vui lòng nhập giá hosting!', 'error'); markError('hostingPrice'); return; }

        showToast('Đang thêm hosting...');
        try {
            const resp = await fetch('/hostings/save', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const res = await resp.json();
            if (res.success) {
                showToast('Thêm hosting thành công!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(res.message || 'Lỗi khi lưu.', 'error');
            }
        } catch (e) {
            showToast('Lỗi kết nối!', 'error');
        }
    }

    // Projects Logic
    function openAddProjectModal() { 
        clearErrors();
        document.getElementById('addProjectModal').classList.add('active'); 
        document.getElementById('mProjectDate').value = new Date().toISOString().split('T')[0];
    }
    function closeAddProjectModal() { 
        document.getElementById('addProjectModal').classList.remove('active'); 
        clearErrors();
    }
    function closeAddProjectModalOverlay(e) { if (e.target.id === 'addProjectModal') closeAddProjectModal(); }
    function updateProjectValueDisplay(input) {
        document.getElementById('projectValueDisplay').textContent = new Intl.NumberFormat('vi-VN').format(input.value || 0) + ' VNĐ';
    }
    async function submitProjectForm() {
        clearErrors();
        const data = {
            name: document.getElementById('mProjectName').value,
            status: document.getElementById('mProjectStatus').value,
            desc: document.getElementById('mProjectDesc').value,
            date: document.getElementById('mProjectDate').value,
            customer: document.getElementById('mCustomerName').value,
            phone: document.getElementById('mCustomerPhone').value,
            adminUrl: document.getElementById('mAdminLink').value,
            adminUser: document.getElementById('mAdminUser').value,
            adminPass: document.getElementById('adminPassword').value,
            value: document.getElementById('projectValue').value
        };

        if (!data.name) { showToast('Vui lòng nhập tên dự án!', 'error'); markError('mProjectName'); return; }
        if (!data.date) { showToast('Vui lòng chọn ngày tạo!', 'error'); markError('mProjectDate'); return; }
        if (!data.customer) { showToast('Vui lòng nhập tên khách hàng!', 'error'); markError('mCustomerName'); return; }

        showToast('Đang thêm dự án...');
        try {
            const resp = await fetch('/projects/save', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const res = await resp.json();
            if (res.success) {
                showToast('Thêm dự án thành công!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(res.message || 'Lỗi khi lưu.', 'error');
            }
        } catch (e) {
            showToast('Lỗi kết nối!', 'error');
        }
    }

    // Passwords Logic
    function openPasswordModal() { 
        clearErrors();
        document.getElementById('addPasswordModal').classList.add('active'); 
    }
    function closeAddPwdModal() { 
        document.getElementById('addPasswordModal').classList.remove('active'); 
        clearErrors();
    }
    function closeAddPasswordModalOverlay(e) { if (e.target.id === 'addPasswordModal') closeAddPwdModal(); }
    function toggleAddPwdVisibility() {
        const p = document.getElementById('mPwdPass');
        const i = document.getElementById('mPwdEyeIcon');
        if (p.type === 'password') { p.type = 'text'; i.className = 'ph ph-eye-slash'; }
        else { p.type = 'password'; i.className = 'ph ph-eye'; }
    }
    function generateStrongPwd() {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
        let pwd = "";
        for (let i = 0; i < 16; i++) pwd += chars.charAt(Math.floor(Math.random() * chars.length));
        document.getElementById('mPwdPass').value = pwd;
        document.getElementById('mPwdPass').type = 'text';
        document.getElementById('mPwdEyeIcon').className = 'ph ph-eye-slash';
    }
    async function submitAddPwdForm(e) {
        e.preventDefault();
        clearErrors();
        const data = {
            title: document.getElementById('mPwdTitle').value,
            url: document.getElementById('mPwdUrl').value,
            category: document.getElementById('mPwdCategory').value,
            username: document.getElementById('mPwdUser').value,
            password: document.getElementById('mPwdPass').value,
            notes: document.getElementById('mPwdNotes').value
        };

        if (!data.title) { showToast('Vui lòng nhập tiêu đề!', 'error'); markError('mPwdTitle'); return; }
        if (!data.category) { showToast('Vui lòng chọn danh mục!', 'error'); markError('mPwdCategory', true); return; }
        if (!data.username) { showToast('Vui lòng nhập tài khoản!', 'error'); markError('mPwdUser'); return; }
        if (!data.password) { showToast('Vui lòng nhập mật khẩu!', 'error'); markError('mPwdPass'); return; }

        showToast('Đang thêm mật khẩu...');
        try {
            const resp = await fetch('/passwords/save', { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const res = await resp.json();
            if (res.success) {
                showToast('Đã lưu mật khẩu!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(res.message, 'error');
            }
        } catch (err) {
            showToast('Lỗi hệ thống!', 'error');
        }
    }

    // Snippets Logic
    function openAddSnippetModal() { 
        clearErrors();
        document.getElementById('cxModal').classList.add('active'); 
    }
    function closeCxModal() { 
        document.getElementById('cxModal').classList.remove('active'); 
        clearErrors();
    }
    function updateCxStats() {
        const code = document.getElementById('cxCodeArea').value;
        document.getElementById('statLines').textContent = (code.split('\n').length) + ' dòng';
        document.getElementById('statChars').textContent = code.length + ' ký tự';
    }
    async function submitCxForm(e) {
        e.preventDefault();
        clearErrors();
        const formData = new FormData(e.target);
        const code = document.getElementById('cxCodeArea').value;
        const title = document.getElementById('cxTitle').value;
        const lang = document.getElementById('cxLangInput').value;

        if (!title) { showToast('Vui lòng nhập tên code!', 'error'); markError('cxTitle'); return; }
        if (!lang) { showToast('Vui lòng chọn ngôn ngữ!', 'error'); markError('cxLangInput', true); return; }
        if (!code) { showToast('Vui lòng nhập nội dung code!', 'error'); markError('cxCodeArea'); return; }

        formData.append('line_count', code.split('\n').length);
        formData.append('char_count', code.length);
        
        showToast('Đang thêm snippet...');
        try {
            const resp = await fetch('/codex/save', { 
                method: 'POST', 
                body: formData
            });
            const res = await resp.json();
            if (res.success) {
                showToast('Tạo snippet thành công!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(res.message, 'error');
            }
        } catch (err) {
            showToast('Lỗi kết nối!', 'error');
        }
    }

    // Shared UI initialization
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.pj-modal-select').forEach(sel => {
            const inputId = sel.getAttribute('data-input-id');
            const input = document.getElementById(inputId);
            if (!input) return;
            const triggerText = sel.querySelector('.pj-modal-select-trigger span');
            sel.querySelectorAll('.pj-dropdown-item').forEach(item => {
                item.onclick = function() {
                    const val = this.getAttribute('data-value');
                    input.value = val;
                    triggerText.textContent = this.textContent.trim();
                    sel.querySelectorAll('.pj-dropdown-item').forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    sel.querySelector('.pj-dropdown').classList.remove('active');
                    if (inputId === 'cxLangInput') {
                        document.getElementById('cxLangBadge').textContent = val;
                    }
                };
            });
        });
    });

    function togglePasswordVisibility(id, btn) {
        const p = document.getElementById(id);
        const i = btn.querySelector('i');
        if (p.type === 'password') { p.type = 'text'; i.className = 'ph ph-eye-slash'; }
        else { p.type = 'password'; i.className = 'ph ph-eye'; }
    }
</script>

</html>