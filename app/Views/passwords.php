<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager - 1Pixel Dashboard</title>
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
                <a href="/" class="nav-item">
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
                <a href="/passwords" class="nav-item active">
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
                    <h1>Password Manager</h1>
                    <p>Quản lý mật khẩu an toàn</p>
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
                <!-- Toolbar -->
                <div class="toolbar">
                    <div class="search-box">
                        <i class="ph ph-magnifying-glass"></i>
                        <input type="text" placeholder="Tìm kiếm theo tiêu đề, tên đăng nhập, website...">
                    </div>
                    <div class="toolbar-actions">
                        <select class="status-select">
                            <option value="">Tất cả loại</option>
                            <option value="email">Email</option>
                            <option value="account">Tài khoản</option>
                        </select>
                        <button class="btn-primary btn-purple">
                            <i class="ph ph-tag"></i> <!-- Tag/Category icon -->
                            Quản lý Danh Mục
                        </button>
                        <button class="btn-primary btn-add">
                            <i class="ph ph-plus"></i>
                            Thêm Mới
                        </button>
                    </div>
                </div>

                <!-- Password Grid -->
                <div class="pwd-grid">
                    <!-- Card 1 -->
                    <div class="pwd-card border-top-teal">
                        <div class="pwd-header">
                            <h3>Mật khẩu VPS</h3>
                            <div class="pwd-tags">
                                <span class="badge-tag outline-blue"><i class="ph ph-envelope"></i> Email</span>
                                <span class="badge-tag outline-blue"><i class="ph ph-globe"></i> iNet</span>
                            </div>
                        </div>
                        
                        <div class="pwd-body">
                            <div class="pwd-field">
                                <label><i class="ph ph-user"></i> USERNAME</label>
                                <div class="pwd-input-group">
                                    <input type="text" value="root" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pwd-field">
                                <label><i class="ph ph-lock"></i> PASSWORD</label>
                                <div class="pwd-input-group">
                                    <input type="password" value="secretpassword123" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-eye-slash"></i></button>
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pwd-footer">
                            <button class="btn-pwd btn-pwd-edit"><i class="ph ph-pencil-simple"></i> Sửa</button>
                            <button class="btn-pwd btn-pwd-delete"><i class="ph ph-trash"></i> Xóa</button>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="pwd-card border-top-teal">
                        <div class="pwd-header">
                            <h3>Tài khoản Figma Education</h3>
                            <div class="pwd-tags">
                                <span class="badge-tag outline-blue"><i class="ph ph-envelope"></i> Email</span>
                            </div>
                        </div>
                        
                        <div class="pwd-body">
                            <div class="pwd-field">
                                <label><i class="ph ph-user"></i> USERNAME</label>
                                <div class="pwd-input-group">
                                    <input type="text" value="kqdvtzy46@hotmail.com" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pwd-field">
                                <label><i class="ph ph-lock"></i> PASSWORD</label>
                                <div class="pwd-input-group">
                                    <input type="password" value="figma2026pass" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-eye-slash"></i></button>
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="pwd-field">
                                <label>GHI CHÚ</label>
                                <div class="pwd-input-group note-group">
                                    <input type="text" value="Figma make - 2000 credit; Tài khoản 1 năm" readonly class="pwd-input input-yellow">
                                </div>
                            </div>
                        </div>

                        <div class="pwd-footer">
                            <button class="btn-pwd btn-pwd-edit"><i class="ph ph-pencil-simple"></i> Sửa</button>
                            <button class="btn-pwd btn-pwd-delete"><i class="ph ph-trash"></i> Xóa</button>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="pwd-card border-top-teal">
                        <div class="pwd-header">
                            <h3>Tài khoản Figma Education</h3>
                            <div class="pwd-tags">
                                <span class="badge-tag outline-blue"><i class="ph ph-envelope"></i> Email</span>
                            </div>
                            <div class="pwd-link">
                                <a href="http://www.figma.com/maker" target="_blank"><i class="ph ph-link"></i> www.figma.com/maker</a>
                            </div>
                        </div>
                        
                        <div class="pwd-body">
                            <div class="pwd-field">
                                <label><i class="ph ph-user"></i> USERNAME</label>
                                <div class="pwd-input-group">
                                    <input type="text" value="maripepade1721@hotmail.com" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pwd-field">
                                <label><i class="ph ph-lock"></i> PASSWORD</label>
                                <div class="pwd-input-group">
                                    <input type="password" value="figma2026pass" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-eye-slash"></i></button>
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="pwd-field">
                                <label>GHI CHÚ</label>
                                <div class="pwd-input-group note-group">
                                    <input type="text" value="Figma make - 3000 credit; Tài khoản 1 năm" readonly class="pwd-input input-yellow">
                                </div>
                            </div>
                        </div>

                        <div class="pwd-footer">
                            <button class="btn-pwd btn-pwd-edit"><i class="ph ph-pencil-simple"></i> Sửa</button>
                            <button class="btn-pwd btn-pwd-delete"><i class="ph ph-trash"></i> Xóa</button>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="pwd-card border-top-red">
                        <div class="pwd-header">
                            <h3>Tài khoản FB</h3>
                            <div class="pwd-tags">
                                <span class="badge-tag outline-red"><i class="ph ph-user-circle"></i> Tài khoản</span>
                            </div>
                        </div>
                        
                        <div class="pwd-body">
                            <div class="pwd-field">
                                <label><i class="ph ph-user"></i> USERNAME</label>
                                <div class="pwd-input-group">
                                    <input type="text" value="0373987954" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pwd-field">
                                <label><i class="ph ph-lock"></i> PASSWORD</label>
                                <div class="pwd-input-group">
                                    <input type="password" value="facebookpass123" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-eye-slash"></i></button>
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pwd-footer">
                            <button class="btn-pwd btn-pwd-edit"><i class="ph ph-pencil-simple"></i> Sửa</button>
                            <button class="btn-pwd btn-pwd-delete"><i class="ph ph-trash"></i> Xóa</button>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="pwd-card border-top-teal">
                        <div class="pwd-header">
                            <h3>Tài khoản mail mẹ Hoa</h3>
                            <div class="pwd-tags">
                                <span class="badge-tag outline-blue"><i class="ph ph-envelope"></i> Email</span>
                            </div>
                        </div>
                        
                        <div class="pwd-body">
                            <div class="pwd-field">
                                <label><i class="ph ph-user"></i> USERNAME</label>
                                <div class="pwd-input-group">
                                    <input type="text" value="hoadinh.taikhoan@gmail.com" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pwd-field">
                                <label><i class="ph ph-lock"></i> PASSWORD</label>
                                <div class="pwd-input-group">
                                    <input type="password" value="hoamail2026pass" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-eye-slash"></i></button>
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pwd-footer">
                            <button class="btn-pwd btn-pwd-edit"><i class="ph ph-pencil-simple"></i> Sửa</button>
                            <button class="btn-pwd btn-pwd-delete"><i class="ph ph-trash"></i> Xóa</button>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="pwd-card border-top-red">
                        <div class="pwd-header">
                            <h3>Tài khoản VssID của Mẹ Trang</h3>
                            <div class="pwd-tags">
                                <span class="badge-tag outline-red"><i class="ph ph-user-circle"></i> Tài khoản</span>
                            </div>
                        </div>
                        
                        <div class="pwd-body">
                            <div class="pwd-field">
                                <label><i class="ph ph-user"></i> USERNAME</label>
                                <div class="pwd-input-group">
                                    <input type="text" value="2203021628" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pwd-field">
                                <label><i class="ph ph-lock"></i> PASSWORD</label>
                                <div class="pwd-input-group">
                                    <input type="password" value="vssidpass2026" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-eye-slash"></i></button>
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pwd-footer">
                            <button class="btn-pwd btn-pwd-edit"><i class="ph ph-pencil-simple"></i> Sửa</button>
                            <button class="btn-pwd btn-pwd-delete"><i class="ph ph-trash"></i> Xóa</button>
                        </div>
                    </div>

                    <!-- Card 7 -->
                    <div class="pwd-card border-top-teal">
                        <div class="pwd-header">
                            <h3>Tài khoản Figma Education</h3>
                            <div class="pwd-tags">
                                <span class="badge-tag outline-blue"><i class="ph ph-envelope"></i> Email</span>
                            </div>
                            <div class="pwd-link">
                                <a href="http://www.figma.com/maker" target="_blank"><i class="ph ph-link"></i> www.figma.com/maker</a>
                            </div>
                        </div>
                        
                        <div class="pwd-body">
                            <div class="pwd-field">
                                <label><i class="ph ph-user"></i> USERNAME</label>
                                <div class="pwd-input-group">
                                    <input type="text" value="nwhtyvche1516@hotmail.com" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pwd-field">
                                <label><i class="ph ph-lock"></i> PASSWORD</label>
                                <div class="pwd-input-group">
                                    <input type="password" value="figma2026pass" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-eye-slash"></i></button>
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="pwd-field">
                                <label>GHI CHÚ</label>
                                <div class="pwd-input-group note-group">
                                    <input type="text" value="Figma make - 3000 credit; Tài khoản 1 năm" readonly class="pwd-input input-yellow">
                                </div>
                            </div>
                        </div>

                        <div class="pwd-footer">
                            <button class="btn-pwd btn-pwd-edit"><i class="ph ph-pencil-simple"></i> Sửa</button>
                            <button class="btn-pwd btn-pwd-delete"><i class="ph ph-trash"></i> Xóa</button>
                        </div>
                    </div>

                    <!-- Card 8 -->
                    <div class="pwd-card border-top-teal">
                        <div class="pwd-header">
                            <h3>Tài khoản Affiliate iNet</h3>
                            <div class="pwd-tags">
                                <span class="badge-tag outline-purple"><i class="ph ph-globe"></i> iNet</span>
                            </div>
                            <div class="pwd-link">
                                <a href="http://inet.vn/hosting/web-hosting" target="_blank"><i class="ph ph-link"></i> inet.vn/hosting/web-hosting...</a>
                            </div>
                        </div>
                        
                        <div class="pwd-body">
                            <div class="pwd-field">
                                <label><i class="ph ph-user"></i> USERNAME</label>
                                <div class="pwd-input-group">
                                    <input type="text" value="job.quywp@gmail.com" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pwd-field">
                                <label><i class="ph ph-lock"></i> PASSWORD</label>
                                <div class="pwd-input-group">
                                    <input type="password" value="inetpass2026" readonly class="pwd-input">
                                    <div class="pwd-input-actions">
                                        <button class="btn-icon-sm"><i class="ph ph-eye-slash"></i></button>
                                        <button class="btn-icon-sm"><i class="ph ph-copy"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="pwd-field">
                                <label>GHI CHÚ</label>
                                <div class="pwd-input-group note-group">
                                    <input type="text" value="Mã AFF: 790449644" readonly class="pwd-input input-yellow">
                                </div>
                            </div>
                        </div>

                        <div class="pwd-footer">
                            <button class="btn-pwd btn-pwd-edit"><i class="ph ph-pencil-simple"></i> Sửa</button>
                            <button class="btn-pwd btn-pwd-delete"><i class="ph ph-trash"></i> Xóa</button>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>
