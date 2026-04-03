<?php
$user = $currentUser ?? [
    'name' => $_SESSION['user_name'] ?? 'Admin User',
    'role' => $_SESSION['user_role'] ?? 'Administrator'
];
$fullName = $user['name'];
$nameParts = explode(' ', $fullName);
$initials = count($nameParts) >= 2 
    ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
    : strtoupper(substr($nameParts[0], 0, 2));
?>
<header class="header">
    <div class="header-left">
        <h1><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
        <p><?php echo $pageSubtitle ?? 'Quản lý công việc tập trung'; ?></p>
    </div>
    <div class="header-right">
        <div class="notif-dropdown-wrapper" id="notifDropdownWrapper">
            <button class="btn-icon" id="notifTrigger">
                <i class="ph ph-bell"></i>
                <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                <span class="badge"><?php echo (int)$unreadCount; ?></span>
                <?php endif; ?>
            </button>
            <div class="notification-dropdown" id="notifDropdown">
                <div class="notif-header">
                    <div class="header-left-content">
                        <span class="notif-title">Notifications</span>
                        <span class="notif-subtitle"><?php echo $unreadCount; ?> Unread Alerts</span>
                    </div>
                    <button class="notif-mark-all" id="notifMarkAll">Mark all as read</button>
                </div>
                <div class="notif-content">
                    <?php if (empty($notifications)): ?>
                        <div class="no-notif-msg" style="padding: 30px 20px; text-align: center; color: #94a3b8;">
                            <i class="ph ph-bell-slash" style="font-size: 38px; margin-bottom: 12px; display: block; color: #cbd5e1;"></i>
                            <p style="font-size: 14px; font-weight: 500;">No notifications yet</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): 
                            $iconClass = 'ph-info';
                            $typeClass = 'info';
                            if ($notif['type'] == 'warning') { $iconClass = 'ph-warning'; $typeClass = 'warning'; }
                            if ($notif['type'] == 'error') { $iconClass = 'ph-warning-octagon'; $typeClass = 'error'; }
                            if ($notif['type'] == 'success') { $iconClass = 'ph-check-circle'; $typeClass = 'success'; }

                            // Tính toán thời gian tương đối
                            $timeStr = 'JUST NOW';
                            $diff = time() - strtotime($notif['created_at']);
                            if ($diff > 3600*24) $timeStr = floor($diff/86400) . 'D AGO';
                            else if ($diff > 3600) $timeStr = floor($diff/3600) . 'H AGO';
                            else if ($diff > 60) $timeStr = floor($diff/60) . 'M AGO';
                        ?>
                        <div class="notif-item <?php echo $typeClass; ?> <?php echo $notif['is_read'] ? '' : 'unread'; ?>" 
                             onclick="markNotifAsRead(<?php echo $notif['id']; ?>, '<?php echo $notif['link'] ?? 'javascript:void(0)'; ?>')">
                            
                            <div class="item-icon-box">
                                <i class="ph <?php echo $iconClass; ?>"></i>
                            </div>

                            <div class="item-details">
                                <div class="item-top-row">
                                    <h4 class="item-title"><?php echo htmlspecialchars($notif['title']); ?></h4>
                                    <span class="item-time"><?php echo $timeStr; ?></span>
                                </div>
                                <span class="item-message">
                                    <?php 
                                        $msg = $notif['message'] ?? '';
                                        $lines = explode("\n", $msg);
                                        echo htmlspecialchars($lines[0] ?? ''); 
                                    ?>
                                </span>
                                <?php if (isset($lines[1]) && !empty($lines[1])): ?>
                                <p class="item-meta">
                                    <i class="ph <?php echo (strpos($lines[1], 'Hết hạn') !== false) ? 'ph-calendar' : 'ph-clock'; ?>"></i>
                                    <?php echo htmlspecialchars($lines[1]); ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="notif-footer">
                    <a href="/logs" class="view-all-link">
                        View All Activity <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="user-dropdown-wrapper" id="userDropdownWrapper">
            <div class="user-profile" id="userProfileTrigger">
                <div class="avatar"><?php echo $initials; ?></div>
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($fullName); ?></span>
                    <span class="user-role"><?php echo htmlspecialchars($_SESSION['user_role'] ?? 'Administrator'); ?></span>
                </div>
            </div>

            <!-- Profile Dropdown Menu -->
            <div class="profile-dropdown-menu" id="profileDropdown">
                <div class="dropdown-header">
                    <div class="avatar-large"><?php echo $initials; ?></div>
                    <div class="header-info">
                        <span class="name"><?php echo htmlspecialchars($fullName); ?></span>
                        <span class="username">@<?php echo strtolower(str_replace(' ', '', $fullName)); ?></span>
                    </div>
                </div>
                <div class="dropdown-body">
                    <a href="javascript:void(0)" onclick="openAccountModal('info')" class="dropdown-item">
                        <div class="item-icon-box bg-light-blue">
                            <i class="ph ph-user"></i>
                        </div>
                        <div class="item-text">
                            <span class="title">Thông Tin Tài Khoản</span>
                            <span class="sub">Xem và chỉnh sửa thông tin</span>
                        </div>
                    </a>
                    <a href="javascript:void(0)" onclick="openAccountModal('password')" class="dropdown-item">
                        <div class="item-icon-box bg-light-orange">
                            <i class="ph ph-key"></i>
                        </div>
                        <div class="item-text">
                            <span class="title">Đổi Mật Khẩu</span>
                            <span class="sub">Cập nhật mật khẩu bảo mật</span>
                        </div>
                    </a>

                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="dropdown-item logout-item">
                        <div class="item-icon-box bg-light-red">
                            <i class="ph ph-sign-out"></i>
                        </div>
                        <div class="item-text">
                            <span class="title">Đăng Xuất</span>
                            <span class="sub">Thoát khỏi hệ thống</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Toggle Script for Notifications -->
<script>
(function() {
    console.log('Notification script initialized');
    console.log('Session ID:', '<?php echo session_id(); ?>');
    
    document.addEventListener('DOMContentLoaded', function() {
        const notifTrigger = document.getElementById('notifTrigger');
        const notifDropdown = document.getElementById('notifDropdown');
        const notifWrapper = document.getElementById('notifDropdownWrapper');
        
        console.log('Notification elements:', {
            trigger: !!notifTrigger,
            dropdown: !!notifDropdown,
            wrapper: !!notifWrapper
        });

        if (notifTrigger && notifDropdown) {
            notifTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close other dropdowns
                document.querySelectorAll('.profile-dropdown-menu, .notification-dropdown').forEach(d => {
                    if (d !== notifDropdown) d.classList.remove('active');
                });
                
                notifDropdown.classList.toggle('active');
                console.log('Notification modal toggled. Current state:', notifDropdown.classList.contains('active'));
            });
        }

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (notifDropdown && notifWrapper && !notifWrapper.contains(e.target)) {
                if (notifDropdown.classList.contains('active')) {
                    notifDropdown.classList.remove('active');
                    console.log('Notification modal closed (clicked outside)');
                }
            }
        });
    });
})();
</script>
