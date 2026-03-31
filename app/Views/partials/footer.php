<script>
    // Global Dashboard Logic
    document.addEventListener('DOMContentLoaded', () => {
        // User Profile Dropdown
        const profileTrigger = document.getElementById('userProfileTrigger');
        const profileDropdown = document.getElementById('profileDropdown');
        const dropdownWrapper = document.getElementById('userDropdownWrapper');

        if (profileTrigger && profileDropdown) {
            profileTrigger.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('active');
            });

            document.addEventListener('click', (e) => {
                if (dropdownWrapper && !dropdownWrapper.contains(e.target)) {
                    profileDropdown.classList.remove('active');
                }
            });
        }

        // Global Select Logic
        document.querySelectorAll('.pj-modal-select').forEach(sel => {
            const inputId = sel.getAttribute('data-input-id');
            const input = document.getElementById(inputId);
            if (!input) return;
            const triggerText = sel.querySelector('.pj-modal-select-trigger span');
            sel.querySelectorAll('.pj-dropdown-item').forEach(item => {
                item.onclick = function () {
                    const val = this.getAttribute('data-value');
                    input.value = val;
                    triggerText.textContent = this.textContent.trim();
                    sel.querySelectorAll('.pj-dropdown-item').forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    sel.querySelector('.pj-dropdown').classList.remove('active');
                };
            });
        });

        // Global Loader handler
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
