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
                <?php if ($unreadCount > 0): ?>
                <span class="badge"><?php echo $unreadCount; ?></span>
                <?php endif; ?>
            </button>
            <div class="notification-dropdown" id="notifDropdown">
                <div class="notif-header">
                    <div class="header-left-content">
                        <div class="notif-title-icon">
                            <i class="ph ph-warning-circle"></i>
                        </div>
                        <span class="notif-title">Thông Báo</span>
                        <?php if ($unreadCount > 0): ?>
                        <span class="notif-count-badge"><?php echo $unreadCount; ?></span>
                        <?php endif; ?>
                    </div>
                    <button class="notif-close-btn" id="notifCloseBtn">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
                <div class="notif-content">
                    <?php if (empty($notifications)): ?>
                        <div class="no-notif-msg" style="padding: 20px; text-align: center; color: var(--text-muted);">
                            <i class="ph ph-bell-slash" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                            Chưa có thông báo nào.
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): 
                            $iconClass = 'ph-info';
                            $typeClass = 'info';
                            if ($notif['type'] == 'warning') { $iconClass = 'ph-warning'; $typeClass = 'warning'; }
                            if ($notif['type'] == 'error') { $iconClass = 'ph-warning-octagon'; $typeClass = 'error'; }
                            if ($notif['type'] == 'success') { $iconClass = 'ph-check-circle'; $typeClass = 'success'; }
                        ?>
                        <div class="notif-item <?php echo $typeClass; ?> <?php echo $notif['is_read'] ? '' : 'unread'; ?>" 
                             onclick="markNotifAsRead(<?php echo $notif['id']; ?>, '<?php echo $notif['link'] ?? 'javascript:void(0)'; ?>')">
                            <div class="item-icon-box">
                                <i class="ph <?php echo $iconClass; ?>"></i>
                            </div>
                            <div class="item-details">
                                <h4 class="item-title"><?php echo htmlspecialchars($notif['title']); ?></h4>
                                <?php 
                                    $lines = explode("\n", $notif['message']);
                                    $name = $lines[0];
                                    $meta = isset($lines[1]) ? $lines[1] : '';
                                ?>
                                <span class="item-name"><?php echo htmlspecialchars($name); ?></span>
                                <?php if ($meta): ?>
                                <p class="item-meta">
                                    <?php if (strpos($meta, 'Hết hạn') !== false): ?>
                                        <i class="ph ph-calendar"></i>
                                    <?php else: ?>
                                        <i class="ph ph-clock"></i>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($meta); ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
