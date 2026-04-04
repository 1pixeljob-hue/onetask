<?php include APP_DIR . '/Views/partials/account_modal.php'; ?>
<script src="/js/datepicker.js?v=<?php echo time(); ?>"></script>
<script>
    // Global Account Modal Logic
    function openAccountModal(tab = 'info') {
        const modal = document.getElementById('accountModal');
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown) dropdown.classList.remove('active');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            // Switch to requested tab
            const tabBtn = document.querySelector(`.account-tab-btn[onclick*="${tab}"]`);
            if (tabBtn) switchAccountTab(tab, tabBtn);
        }
    }

    function closeAccountModal() {
        const modal = document.getElementById('accountModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function handleAccountModalOverlay(e) {
        if (e.target.id === 'accountModal') closeAccountModal();
    }

    function switchAccountTab(tabId, btn) {
        // Update Buttons
        document.querySelectorAll('.account-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Update Contents
        document.querySelectorAll('.account-tab-content').forEach(c => c.classList.remove('active'));
        document.getElementById('tab-' + tabId).classList.add('active');
    }

    // Global Dashboard Logic
    document.addEventListener('DOMContentLoaded', () => {
        // Change Password Form logic
        const changePassForm = document.getElementById('changePasswordForm');
        if (changePassForm) {
            changePassForm.onsubmit = async (e) => {
                e.preventDefault();
                const btn = e.target.querySelector('button');
                const originalHtml = btn.innerHTML;
                
                // Simple validation
                const newPass = e.target.new_password.value;
                const confirmPass = e.target.confirm_password.value;
                if (newPass !== confirmPass) {
                    alert('Mật khẩu xác nhận không khớp!');
                    return;
                }

                try {
                    btn.disabled = true;
                    btn.innerHTML = '<div class="spinner-small"></div> Đang xử lý...';
                    
                    // Call API (conceptual - implementation depends on backend)
                    // const response = await fetch('/api/change-password', { method: 'POST', body: new FormData(e.target) });
                    // const res = await response.json();
                    
                    setTimeout(() => {
                        alert('Đã cập nhật mật khẩu thành công! (Demo Mode)');
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                        closeAccountModal();
                        e.target.reset();
                    }, 1500);

                } catch (err) {
                    console.error('Password change error:', err);
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    alert('Đã xảy ra lỗi hệ thống.');
                }
            };
        }

        // User Profile Dropdown
        const profileTrigger = document.getElementById('userProfileTrigger');
        const profileDropdown = document.getElementById('profileDropdown');
        const dropdownWrapper = document.getElementById('userDropdownWrapper');

        if (profileTrigger && profileDropdown) {
            // Force close on load
            profileDropdown.classList.remove('active');
            
            profileTrigger.onclick = (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('active');
            };

            document.addEventListener('click', (e) => {
                if (profileDropdown.classList.contains('active') && !dropdownWrapper.contains(e.target)) {
                    profileDropdown.classList.remove('active');
                }
            });
        }

        // Centralized Loader handler
        const loader = document.getElementById('global-loader');
        if (loader) {
            setTimeout(() => {
                loader.classList.add('hide');
            }, 300);
        }
    });

    // Global UI Helper Functions
    function togglePasswordVisibility(id, btn) {
        const p = document.getElementById(id);
        const i = btn.querySelector('i');
        if (p.type === 'password') { p.type = 'text'; i.className = 'ph ph-eye-slash'; }
        else { p.type = 'password'; i.className = 'ph ph-eye'; }
    }
</script>

