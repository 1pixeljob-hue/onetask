<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs - 1Pixel Dashboard</title>
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
                <a href="/logs" class="nav-item active">
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
                    <p>Tổng quan hệ thống</p>
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

                <!-- Page Title in Content -->
                <div class="logs-page-title-block">
                    <h2 class="logs-page-title">Logs</h2>
                    <p class="logs-page-subtitle">Theo dõi các hành động và restore dữ liệu đã xóa</p>
                </div>

                <!-- Toolbar -->
                <div class="logs-toolbar-row">
                    <div class="pj-search-wrap logs-search">
                        <i class="ph ph-magnifying-glass pj-search-icon"></i>
                        <input type="text" class="pj-search-input" id="logsSearchInput" placeholder="Tìm kiếm theo tên item hoặc user..." value="<?= htmlspecialchars($filters['search']) ?>">
                    </div>
                    <select class="logs-select" id="logsModuleSelect" onchange="applyFilters()">
                        <option value="">Tất cả Module</option>
                        <option value="Project" <?= $filters['module'] == 'Project' ? 'selected' : '' ?>>Project</option>
                        <option value="Hosting" <?= $filters['module'] == 'Hosting' ? 'selected' : '' ?>>Hosting</option>
                        <option value="CodeX" <?= $filters['module'] == 'CodeX' ? 'selected' : '' ?>>CodeX</option>
                        <option value="Passwords" <?= $filters['module'] == 'Passwords' ? 'selected' : '' ?>>Passwords</option>
                    </select>
                    <select class="logs-select" id="logsActionSelect" onchange="applyFilters()">
                        <option value="">Tất cả Hành động</option>
                        <option value="Cập nhật" <?= $filters['action'] == 'Cập nhật' ? 'selected' : '' ?>>Cập nhật</option>
                        <option value="Tạo mới" <?= $filters['action'] == 'Tạo mới' ? 'selected' : '' ?>>Tạo mới</option>
                        <option value="Xoá" <?= $filters['action'] == 'Xoá' ? 'selected' : '' ?>>Xoá</option>
                    </select>
                </div>

                <!-- Data Table -->
                <div class="table-container logs-table-container">
                    <table class="data-table logs-table" id="logsTable">
                        <thead>
                            <tr>
                                <th width="40"><input type="checkbox" class="cb-custom"></th>
                                <th>MODULE</th>
                                <th>HÀNH ĐỘNG</th>
                                <th>ITEM</th>
                                <th>USER</th>
                                <th>THỜI GIAN</th>
                                <th class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Không tìm thấy bản ghi log nào.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): 
                                    $icon = 'ph-file-text';
                                    if ($log['module'] == 'Project') $icon = 'ph-folder';
                                    elseif ($log['module'] == 'Hosting') $icon = 'ph-hard-drives';
                                    elseif ($log['module'] == 'CodeX') $icon = 'ph-code';
                                    elseif ($log['module'] == 'Passwords') $icon = 'ph-key';

                                    $badgeClass = 'badge-blue';
                                    if ($log['action'] == 'Tạo mới') $badgeClass = 'badge-green';
                                    elseif ($log['action'] == 'Xoá') $badgeClass = 'badge-red';
                                ?>
                                <tr data-id="<?= $log['id'] ?>">
                                    <td><input type="checkbox" class="cb-custom"></td>
                                    <td><div class="log-module"><i class="ph <?= $icon ?>"></i> <?= htmlspecialchars($log['module']) ?></div></td>
                                    <td><span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($log['action']) ?></span></td>
                                    <td><?= htmlspecialchars($log['item_name']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($log['user_name']) ?></td>
                                    <td class="text-muted"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                                    <td class="text-center">
                                        <div class="log-actions">
                                            <button class="btn-action" title="Xem"><i class="ph ph-eye color-blue"></i></button>
                                            <?php if (($log['action'] == 'Xoá' || $log['action'] == 'Cập nhật') && !empty($log['data'])): ?>
                                                <button class="btn-action" title="<?= $log['action'] == 'Xoá' ? 'Khôi phục' : 'Hoàn tác thay đổi' ?>" onclick="restoreLog(<?= $log['id'] ?>)">
                                                    <i class="ph ph-arrows-counter-clockwise color-green"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="logs-pagination-row">
                    <span class="logs-count">Hiển thị: <?= $offset + 1 ?> - <?= min($offset + $limit, $totalLogs) ?> / <?= $totalLogs ?> logs</span>
                    <div class="logs-pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?= $currentPage - 1 ?>&module=<?= $filters['module'] ?>&action=<?= $filters['action'] ?>&search=<?= urlencode($filters['search']) ?>" class="pg-btn">Trước</a>
                        <?php endif; ?>
                        
                        <?php 
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $currentPage + 2);
                        for ($i = $start; $i <= $end; $i++): 
                        ?>
                            <a href="?page=<?= $i ?>&module=<?= $filters['module'] ?>&action=<?= $filters['action'] ?>&search=<?= urlencode($filters['search']) ?>" class="pg-btn <?= $i == $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?= $currentPage + 1 ?>&module=<?= $filters['module'] ?>&action=<?= $filters['action'] ?>&search=<?= urlencode($filters['search']) ?>" class="pg-btn">Sau</a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>

<script>
function applyFilters() {
    const module = document.getElementById('logsModuleSelect').value;
    const action = document.getElementById('logsActionSelect').value;
    const search = document.getElementById('logsSearchInput').value;
    
    let url = new URL(window.location.href);
    url.searchParams.set('module', module);
    url.searchParams.set('action', action);
    url.searchParams.set('search', search);
    url.searchParams.set('page', 1); // Reset to first page when filtering
    
    window.location.href = url.toString();
}

document.getElementById('logsSearchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});

async function restoreLog(id) {
    if (!confirm('Bạn có chắc chắn muốn khôi phục dữ liệu này?')) return;
    
    try {
        const response = await fetch('/logs/restore', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        if (result.status === 'success' || result.success) {
            alert('Khôi phục thành công!');
            location.reload();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Đã xảy ra lỗi khi kết nối với máy chủ.');
    }
}

async function deleteLog(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa bản ghi log này?')) return;
    
    try {
        const response = await fetch('/logs/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const result = await response.json();
        if (result.success) {
            location.reload();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Đã xảy ra lỗi khi kết nối với máy chủ.');
    }
}
</script>
</body>
</html>
