<!-- Modal Tài Khoản -->
<div class="modal-overlay" id="accountModal" onclick="handleAccountModalOverlay(event)">
    <div class="modal-box premium-account-modal" style="max-width: 600px;">
        <!-- Modal Header -->
        <div class="modal-header account-modal-header">
            <div class="account-header-content">
                <div class="account-header-icon">
                    <i class="ph-fill ph-key"></i>
                </div>
                <div class="account-header-text">
                    <h3 class="modal-title">Tài Khoản</h3>
                    <p class="modal-subtitle">Quản lý thông tin cá nhân</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeAccountModal()"><i class="ph ph-x"></i></button>
        </div>

        <!-- Modal Tabs -->
        <div class="account-modal-tabs">
            <button class="account-tab-btn active" onclick="switchAccountTab('info', this)">
                <i class="ph ph-user-focus"></i> Thông Tin
            </button>
            <button class="account-tab-btn" onclick="switchAccountTab('password', this)">
                <i class="ph ph-key"></i> Đổi Mật Khẩu
            </button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body scrollable-y" id="accountModalBody">
            <!-- Tab: Thông Tin -->
            <div id="tab-info" class="account-tab-content active">
                <div class="account-profile-section">
                    <div class="account-avatar-wrapper">
                        <div class="account-avatar-large"><?php echo $initials; ?></div>
                    </div>
                    <div class="account-profile-info">
                        <h4 class="profile-display-name"><?php echo strtolower(str_replace(' ', '', $fullName)); ?></h4>
                        <p class="profile-email"><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'quydev@1pixel.vn'); ?></p>
                    </div>
                </div>

                <div class="account-fields-list">
                    <div class="account-field-card">
                        <span class="field-card-label">TÊN ĐĂNG NHẬP</span>
                        <span class="field-card-value"><?php echo strtolower(str_replace(' ', '', $fullName)); ?></span>
                    </div>

                    <div class="account-field-card">
                        <span class="field-card-label">EMAIL</span>
                        <span class="field-card-value"><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'quydev@1pixel.vn'); ?></span>
                    </div>

                    <div class="account-field-card">
                        <span class="field-card-label">VAI TRÒ</span>
                        <div class="field-card-value mt-8">
                            <span class="badge-role-admin">
                                <span class="badge-dot"></span> Administrator
                            </span>
                        </div>
                    </div>
                </div>

                <div class="account-note-box">
                    <div class="note-icon">
                        <i class="ph ph-info"></i>
                    </div>
                    <div class="note-text">
                        <span class="note-title">Lưu ý</span>
                        <p>Để thay đổi thông tin tài khoản, vui lòng liên hệ quản trị viên hệ thống.</p>
                    </div>
                </div>
            </div>

            <!-- Tab: Đổi Mật Khẩu -->
            <div id="tab-password" class="account-tab-content">
                <form id="changePasswordForm" class="account-form">
                    <div class="modal-field mb-20">
                        <label class="modal-label">Mật khẩu hiện tại <span class="req">*</span></label>
                        <div class="input-with-icon">
                            <i class="ph ph-lock"></i>
                            <input type="password" name="current_password" class="modal-input" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="modal-field mb-20">
                        <label class="modal-label">Mật khẩu mới <span class="req">*</span></label>
                        <div class="input-with-icon">
                            <i class="ph ph-shield-check"></i>
                            <input type="password" name="new_password" class="modal-input" placeholder="Nhập mật khẩu mới" required>
                        </div>
                    </div>
                    <div class="modal-field mb-24">
                        <label class="modal-label">Xác nhận mật khẩu mới <span class="req">*</span></label>
                        <div class="input-with-icon">
                            <i class="ph ph-shield-check"></i>
                            <input type="password" name="confirm_password" class="modal-input" placeholder="Nhập lại mật khẩu mới" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit-premium">
                        <i class="ph ph-check-circle"></i> Cập Nhật Mật Khẩu
                    </button>
                </form>
            </div>

        </div>

        <!-- Modal Footer (Hidden by screenshot but useful for spacing) -->
        <div class="modal-footer" style="padding: 10px 24px 20px; border: none; justify-content: center;">
            <button class="modal-btn-cancel" style="width: 100%;" onclick="closeAccountModal()">Đóng</button>
        </div>
    </div>
</div>
