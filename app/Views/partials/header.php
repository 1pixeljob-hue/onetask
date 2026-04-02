<?php
$fullName = $_SESSION['user_name'] ?? 'Admin User';
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
        <button class="btn-icon">
            <i class="ph ph-bell"></i>
            <span class="badge">1</span>
        </button>
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
