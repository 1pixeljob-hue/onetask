<?php
$activePage = $activePage ?? '';
?>
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
        <a href="/" class="nav-item <?php echo $activePage === 'dashboard' ? 'active' : ''; ?>">
            <i class="ph ph-squares-four"></i>
            <span>Dashboard</span>
        </a>
        <a href="/hostings" class="nav-item <?php echo $activePage === 'hostings' ? 'active' : ''; ?>">
            <i class="ph ph-hard-drives"></i>
            <span>Hostings</span>
        </a>
        <a href="/projects" class="nav-item <?php echo $activePage === 'projects' ? 'active' : ''; ?>">
            <i class="ph ph-folders"></i>
            <span>Projects</span>
        </a>
        <a href="/customers" class="nav-item <?php echo $activePage === 'customers' ? 'active' : ''; ?>">
            <i class="ph ph-user-circle"></i>
            <span>Khách hàng</span>
        </a>
        <a href="/reports" class="nav-item <?php echo $activePage === 'reports' ? 'active' : ''; ?>">
            <i class="ph ph-chart-bar"></i>
            <span>Báo Cáo</span>
        </a>
        <a href="/passwords" class="nav-item <?php echo $activePage === 'passwords' ? 'active' : ''; ?>">
            <i class="ph ph-key"></i>
            <span>Passwords</span>
        </a>
        <a href="/codex" class="nav-item <?php echo $activePage === 'codex' ? 'active' : ''; ?>">
            <i class="ph ph-code"></i>
            <span>CodeX</span>
        </a>
        <a href="/logs" class="nav-item <?php echo $activePage === 'logs' ? 'active' : ''; ?>">
            <i class="ph ph-file-text"></i>
            <span>Logs</span>
        </a>
        <a href="/settings" class="nav-item <?php echo $activePage === 'settings' ? 'active' : ''; ?>">
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
