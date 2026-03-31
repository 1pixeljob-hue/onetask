<script>
    // Global Dashboard Logic
    document.addEventListener('DOMContentLoaded', () => {
        // User Profile Dropdown
        const profileTrigger = document.getElementById('userProfileTrigger');
        const profileDropdown = document.getElementById('profileDropdown');
        const dropdownWrapper = document.getElementById('userDropdownWrapper');

        if (profileTrigger && profileDropdown) {
            // Force close on load
            profileDropdown.classList.remove('active');
            
            profileTrigger.onclick = (e) => {
                e.stopPropagation();
                const isActive = profileDropdown.classList.contains('active');
                // Close all other dropdowns if any (optional)
                profileDropdown.classList.toggle('active');
                console.log('Profile dropdown toggled:', !isActive);
            };

            document.addEventListener('click', (e) => {
                if (profileDropdown.classList.contains('active') && !dropdownWrapper.contains(e.target)) {
                    profileDropdown.classList.remove('active');
                    console.log('Profile dropdown closed by outside click');
                }
            });
        }

        // Note: Global Select Logic for .pj-modal-select is now 
        // centralized in shared-data.js to prevent conflicts.

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
