/**
 * shared-data.js — Nguồn dữ liệu duy nhất cho toàn bộ Onetask Dashboard.
 * Tất cả module (Dashboard, Reports, Projects, Hostings) đều đọc từ file này.
 */

// --- Dữ liệu thô từ Database (nếu có) hoặc dùng dữ liệu tĩnh làm fallback ---
const RAW_PROJECTS = (typeof PHP_DATA !== 'undefined' && PHP_DATA.projects) ? PHP_DATA.projects : [
    {
        name: 'Thêm sản phẩm cho web Trái Cây Lâm Thành',
        link: 'https://lamthanhfruit.myshopify.com/admin',
        customer: 'Khánh Linh',
        phone: '0354777188',
        value: 3500000,
        date: '2026-03-10',
        status: 'doing',
        desc: 'Đã thanh toán 50%. Link drive: https://docs.google.com/spreadsheets/d/1x3m5-iiqPx-CJDO6vue004HKsBld5z_mLPtObNHhOYA/edit?pli=1&gid=1832823340#gid=1832823340',
        adminUrl: 'https://lamthanhhimex.mysapo.net/admin',
        adminUser: '0354777188',
        adminPass: 'D@ngminhlong1512'
    },
    {
        name: 'Onelaw Code section tài liệu kèm iframe view',
        link: 'https://onelawvn.com/adminxxxx',
        customer: 'Onelaw',
        phone: 'N/A',
        value: 1500000,
        date: '2026-03-05',
        status: 'testing',
        desc: 'Thêm section tài liệu và iframe view...',
        adminUrl: 'https://onelawvn.com/adminxxxx',
        adminUser: 'admin',
        adminPass: 'password123'
    },
    {
        name: 'Thiết kế web Nam Việt Food Land',
        link: 'https://namviethoodland.com/nam-login',
        customer: 'Anh Nguyễn Sư',
        phone: 'N/A',
        value: 4000000,
        date: '2026-03-03',
        status: 'testing',
        desc: 'Thiết kế website thương mại điện tử cho Nam Việt Food Land',
        adminUrl: 'N/A',
        adminUser: 'N/A',
        adminPass: 'N/A'
    },
    {
        name: 'Hỗ trợ chị Hạnh xử lý web Phú Thành',
        link: 'https://phuthanh.net/phu-admin',
        customer: 'Phú Thành',
        phone: 'N/A',
        value: 1000000,
        date: '2026-03-05',
        status: 'done',
        desc: 'Hỗ trợ xử lý các lỗi trên website Phú Thành',
        adminUrl: 'https://phuthanh.net/phu-admin',
        adminUser: 'admin',
        adminPass: 'admin123'
    },
    {
        name: 'Sayoung - Đăng ký website với Bộ Công Thương',
        link: 'https://dichvucong.moit.gov.vn/Login',
        customer: 'Sayoung',
        phone: 'N/A',
        value: 2500000,
        date: '2026-01-25',
        status: 'done',
        desc: 'Đăng ký website thương mại điện tử với Bộ Công Thương',
        adminUrl: 'N/A',
        adminUser: 'N/A',
        adminPass: 'N/A'
    },
    {
        name: 'Thiết kế Landing cho Onelaw.vn',
        link: 'https://onelaw.vn/adminxxxx',
        customer: 'A Hùng',
        phone: 'N/A',
        value: 1000000,
        date: '2026-01-24',
        status: 'done',
        desc: 'Thiết kế landing page cho Onelaw.vn',
        adminUrl: 'https://onelaw.vn/adminxxxx',
        adminUser: 'admin',
        adminPass: 'admin123'
    },
    {
        name: 'Thiết kế web Pearlcenter',
        link: 'https://pearlcenter.vn/pro-login',
        customer: 'A Hùng',
        phone: 'N/A',
        value: 5000000,
        date: '2026-01-22',
        status: 'done',
        desc: 'Thiết kế website cho PearlCenter',
        adminUrl: 'https://pearlcenter.vn/pro-login',
        adminUser: 'admin',
        adminPass: 'admin123'
    },
    // --- Dữ liệu năm 2025 ---
    {
        name: 'Thiết kế website KLP',
        link: 'https://klp.vn/admin',
        customer: 'KLP',
        phone: 'N/A',
        value: 5000000,
        date: '2025-11-15',
        status: 'done',
        desc: 'Thiết kế website cho KLP',
        adminUrl: 'https://klp.vn/admin',
        adminUser: 'admin',
        adminPass: 'admin123'
    },
    {
        name: 'Thiết kế web Sơn TREX',
        link: 'https://sontrex.vn/admin',
        customer: 'Sơn TREX',
        phone: 'N/A',
        value: 5000000,
        date: '2025-05-10',
        status: 'done',
        desc: 'Thiết kế website cho Sơn TREX',
        adminUrl: 'https://sontrex.vn/admin',
        adminUser: 'admin',
        adminPass: 'admin123'
    },
    {
        name: 'Thiết kế web Sơn KAZUKI',
        link: 'https://sonkazuki.com/admin',
        customer: 'Sơn KAZUKI',
        phone: 'N/A',
        value: 4000000,
        date: '2025-07-20',
        status: 'done',
        desc: 'Thiết kế website cho Sơn KAZUKI',
        adminUrl: 'https://sonkazuki.com/admin',
        adminUser: 'admin',
        adminPass: 'admin123'
    }
];

const RAW_HOSTINGS = (typeof PHP_DATA !== 'undefined' && PHP_DATA.hostings) ? PHP_DATA.hostings : [
    {
        name: 'Photoeditor 24h',
        domain: 'https://photoeditor24h.com/',
        provider: 'iNet',
        price: 200000,
        regDate: '2025-04-12',
        expDate: '2026-04-12',
        usage: '1 năm'
    },
    {
        name: 'Hosting Sơn TREX',
        domain: 'https://sontrex.vn/',
        provider: 'iNet',
        price: 200000,
        regDate: '2025-05-07',
        expDate: '2026-05-07',
        usage: '1 năm'
    },
    {
        name: 'Sayoung',
        domain: 'https://sayoung.vn/',
        provider: 'iNet',
        price: 200000,
        regDate: '2021-05-20',
        expDate: '2026-05-20',
        usage: '5 năm'
    },
    {
        name: 'BĐS Yên Thủy',
        domain: 'https://vietnamrussia.com.vn/',
        provider: 'iNet',
        price: 200000,
        regDate: '2025-06-14',
        expDate: '2026-06-14',
        usage: '1 năm'
    },
    {
        name: 'Giấy Sao Mai',
        domain: 'http://thesaomaigroup.com/',
        provider: 'iNet',
        price: 200000,
        regDate: '2025-07-11',
        expDate: '2026-07-11',
        usage: '1 năm'
    },
    {
        name: 'Sơn KAZUKI',
        domain: 'https://sonkazuki.com/',
        provider: 'iNet',
        price: 200000,
        regDate: '2025-07-18',
        expDate: '2026-07-18',
        usage: '1 năm'
    },
    {
        name: 'Pomaxx',
        domain: 'https://pomaxx.vn/',
        provider: 'iNet',
        price: 200000,
        regDate: '2024-07-24',
        expDate: '2026-07-24',
        usage: '2 năm'
    },
    {
        name: 'VinaLink',
        domain: 'https://vinalink.com/',
        provider: 'Mắt Bão',
        price: 200000,
        regDate: '2025-08-10',
        expDate: '2026-08-10',
        usage: '1 năm'
    },
    {
        name: 'TechBase',
        domain: 'https://techbase.vn/',
        provider: 'PA Việt Nam',
        price: 200000,
        regDate: '2024-09-15',
        expDate: '2026-09-15',
        usage: '2 năm'
    },
    {
        name: 'GreenWeb',
        domain: 'https://greenweb.io/',
        provider: 'iNet',
        price: 200000,
        regDate: '2025-10-20',
        expDate: '2026-10-20',
        usage: '1 năm'
    },
    {
        name: 'FastDev',
        domain: 'https://fastdev.com.vn/',
        provider: 'Azdigi',
        price: 200000,
        regDate: '2023-11-05',
        expDate: '2026-11-05',
        usage: '3 năm'
    },
    {
        name: 'Old Project',
        domain: 'https://oldproject.com/',
        provider: 'iNet',
        price: 200000,
        regDate: '2025-01-01',
        expDate: '2026-01-01',
        usage: '1 năm'
    }
];

// Định dạng lại giá trị số vì PHP json_encode có thể trả về string cho DECIMAL
const PROJECTS = RAW_PROJECTS.map(p => ({ ...p, value: parseFloat(p.value) || 0 }));
const HOSTINGS = RAW_HOSTINGS.map(h => ({ ...h, price: parseFloat(h.price) || 0 }));

// ===================== HELPER FUNCTIONS =====================

/**
 * Định dạng giá trị VND ở dạng rút gọn (VD: 3.5M, 200K)
 */
function formatVNDShort(value) {
    if (value >= 1000000) {
        const m = value / 1000000;
        return (m % 1 === 0 ? m.toFixed(0) : m.toFixed(1)) + 'M VNĐ';
    }
    if (value >= 1000) {
        const k = value / 1000;
        return (k % 1 === 0 ? k.toFixed(0) : k.toFixed(1)) + 'K VNĐ';
    }
    return value.toLocaleString('vi-VN') + ' VNĐ';
}

/**
 * Định dạng giá trị VND đầy đủ (VD: 3.500.000 VNĐ)
 */
function formatVNDFull(value) {
    return value.toLocaleString('vi-VN') + ' VNĐ';
}

/**
 * Lấy năm từ chuỗi date (YYYY-MM-DD)
 */
function getYear(dateStr) {
    if (!dateStr || typeof dateStr !== 'string') return NaN;
    return parseInt(dateStr.split('-')[0]) || NaN;
}

/**
 * Lấy tháng từ chuỗi date (YYYY-MM-DD), 1-indexed
 */
function getMonth(dateStr) {
    if (!dateStr || typeof dateStr !== 'string') return NaN;
    return parseInt(dateStr.split('-')[1]) || NaN;
}

/**
 * Lấy danh sách tất cả các năm có dữ liệu (giảm dần)
 */
function getAllYears() {
    const years = new Set();
    PROJECTS.forEach(p => {
        const y = getYear(p.date);
        if (!isNaN(y)) years.add(y);
    });
    HOSTINGS.forEach(h => {
        const y = getYear(h.expDate);
        if (!isNaN(y)) years.add(y);
    });
    return Array.from(years).sort((a, b) => b - a);
}

/**
 * Thống kê Projects theo năm
 */
function getProjectStats(year) {
    const filtered = PROJECTS.filter(p => getYear(p.date) === year);
    const totalValue = filtered.reduce((sum, p) => sum + p.value, 0);
    const count = filtered.length;
    const doing = filtered.filter(p => p.status === 'doing').length;
    const testing = filtered.filter(p => p.status === 'testing').length;
    const done = filtered.filter(p => p.status === 'done').length;
    return { filtered, totalValue, count, doing, testing, done };
}

/**
 * Thống kê Hostings theo năm (dựa trên năm đăng ký - regDate)
 */
function getHostingStats(year) {
    const filtered = HOSTINGS.filter(h => getYear(h.regDate) === year);
    const totalPrice = filtered.reduce((sum, h) => sum + h.price, 0);
    const count = filtered.length;
    return { filtered, totalPrice, count };
}

/**
 * Thống kê doanh thu theo tháng cho một năm
 */
function getMonthlyBreakdown(year) {
    const months = [];
    for (let m = 1; m <= 12; m++) {
        const projectsInMonth = PROJECTS.filter(p => getYear(p.date) === year && getMonth(p.date) === m);
        const hostingsInMonth = HOSTINGS.filter(h => getYear(h.regDate) === year && getMonth(h.regDate) === m);
        
        const projValue = projectsInMonth.reduce((s, p) => s + p.value, 0);
        const hostValue = hostingsInMonth.reduce((s, h) => s + h.price, 0);
        
        months.push({
            month: m,
            projectValue: projValue,
            projectCount: projectsInMonth.length,
            hostingValue: hostValue,
            hostingCount: hostingsInMonth.length,
            total: projValue + hostValue
        });
    }
    return months;
}

/**
 * Thống kê tổng hợp cho Dashboard
 */
function getDashboardStats() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Hosting stats
    const totalHostings = HOSTINGS.length;
    let expiringSoon = 0;
    let activeHostings = 0;
    let expiredHostings = 0;
    const expiringSoonList = [];
    const expiredList = [];

    HOSTINGS.forEach(h => {
        const exp = new Date(h.expDate);
        exp.setHours(0, 0, 0, 0);
        const diffDays = Math.ceil((exp - today) / (1000 * 60 * 60 * 24));
        
        if (diffDays < 0) {
            expiredHostings++;
            expiredList.push({ ...h, diffDays });
        } else if (diffDays <= 30) {
            expiringSoon++;
            expiringSoonList.push({ ...h, diffDays });
        } else {
            activeHostings++;
        }
    });

    // Sort: expiredList by expDate (oldest first), expiringSoonList by diffDays (soonest first)
    expiredList.sort((a, b) => new Date(a.expDate) - new Date(b.expDate));
    expiringSoonList.sort((a, b) => a.diffDays - b.diffDays);

    // Project stats
    const doingProjects = PROJECTS.filter(p => p.status === 'doing');
    const testingProjects = PROJECTS.filter(p => p.status === 'testing');
    const doneProjects = PROJECTS.filter(p => p.status === 'done');
    
    // Total revenue
    const totalProjectRevenue = PROJECTS.reduce((s, p) => s + p.value, 0);
    const totalHostingRevenue = HOSTINGS.reduce((s, h) => s + h.price, 0);
    const totalRevenue = totalProjectRevenue + totalHostingRevenue;

    // Testing projects value
    const testingValue = testingProjects.reduce((s, p) => s + p.value, 0);

    return {
        totalHostings,
        activeHostings,
        expiringSoon,
        expiredHostings,
        expiringSoonList,
        expiredList,
        doingProjects,
        testingProjects,
        doneProjects,
        totalRevenue,
        totalProjectRevenue,
        totalHostingRevenue,
        testingValue
    };
}


/**
 * Tính % tăng trưởng so với năm trước
 */
function getGrowthPercent(currentYear) {
    const currentStats = getProjectStats(currentYear);
    const currentHosting = getHostingStats(currentYear);
    const currentTotal = currentStats.totalValue + currentHosting.totalPrice;

    const prevStats = getProjectStats(currentYear - 1);
    const prevHosting = getHostingStats(currentYear - 1);
    const prevTotal = prevStats.totalValue + prevHosting.totalPrice;

    if (prevTotal === 0) return null;
    return ((currentTotal - prevTotal) / prevTotal * 100).toFixed(1);
}

/**
 * Format ngày từ YYYY-MM-DD sang DD/MM/YYYY
 */
function formatDateVN(dateStr) {
    if (!dateStr) return '';
    const [y, m, d] = dateStr.split('-');
    return `${d}/${m}/${y}`;
}
/**
 * Page Loader & Transition Management
 */
document.addEventListener('DOMContentLoaded', () => {
    const loader = document.getElementById('global-loader');
    
    // 1. Hide loader when everything is ready (including images and data rendering)
    window.addEventListener('load', () => {
        if (loader) {
            setTimeout(() => {
                loader.classList.add('hide');
                document.body.style.overflow = '';
            }, 300); // Small delay for visual comfort
        }
    });

    // 2. Intercept sidebar navigation to show loader immediately
    // This provides instant feedback before the browser starts fetching the next page
    document.querySelectorAll('.nav-item, .logo, .pj-add-btn, .action-btn').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            // Only for internal links (starts with / or is relative, not #)
            if (href && href !== '#' && !href.startsWith('http') && !e.ctrlKey && !e.shiftKey && !e.metaKey) {
                if (loader) {
                    loader.classList.remove('hide');
                    // Ensure it stays visible while navigating
                    loader.style.opacity = '1';
                    loader.style.visibility = 'visible';
                }
            }
        });
    });
    // 3. Clear all search inputs on every module change (including Back/Forward cache)
    const clearSearchInputs = () => {
        const inputs = document.querySelectorAll('.pj-search-input');
        if (inputs.length === 0) return;

        inputs.forEach(input => {
            // Force clear if it contains typical autofill patterns or the specific "quy"
            if (input.value.toLowerCase().includes('quy') || input.value.length > 0) {
                input.value = '';
                input.dispatchEvent(new Event('input'));
            }
        });
    };

    // Run multiple times to catch late browser-side injections
    clearSearchInputs();
    let clearAttempts = 0;
    const clearLoop = setInterval(() => {
        clearSearchInputs();
        if (++clearAttempts > 10) clearInterval(clearLoop);
    }, 200);
    
    // Extra guard on first focus
    document.addEventListener('focusin', (e) => {
        if (e.target.classList.contains('pj-search-input')) {
            if (e.target.dataset.firstFocus !== 'true') {
                if (e.target.value.toLowerCase().includes('quy')) {
                    e.target.value = '';
                    e.target.dispatchEvent(new Event('input'));
                }
                e.target.dataset.firstFocus = 'true';
            }
        }
    });

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            clearSearchInputs();
        }
    });

    // 4. Universal Modal Select (Dropdown) Logic (Synchronized)
    document.addEventListener('click', (e) => {
        // Toggle Dropdown
        const trigger = e.target.closest('.pj-modal-select-trigger');
        if (trigger) {
            const dropdown = trigger.closest('.pj-modal-select');
            const isOpen = dropdown.classList.contains('open');
            
            // Close all other open modal selects first
            document.querySelectorAll('.pj-modal-select.open').forEach(d => d.classList.remove('open'));
            
            if (!isOpen) {
                dropdown.classList.add('open');
            }
            e.stopPropagation();
            return;
        }

        // Handle Option Selection (Supports both old and new synchronized classes)
        const option = e.target.closest('.pj-modal-select-item') || e.target.closest('.pj-dropdown-item');
        if (option && option.closest('.pj-modal-select')) {
            const dropdown = option.closest('.pj-modal-select');
            const trigger = dropdown.querySelector('.pj-modal-select-trigger');
            const hiddenInputId = dropdown.dataset.inputId;
            const value = option.dataset.value;
            
            // Extract label and icon
            const span = option.querySelector('span');
            const labelText = span ? span.textContent : option.textContent.trim();
            const icon = option.querySelector('i');

            // Update Trigger UI
            const triggerLabel = trigger.querySelector('span');
            if (triggerLabel) triggerLabel.textContent = labelText;
            
            const triggerIcon = trigger.querySelector('i:first-child');
            if (triggerIcon && icon) {
                triggerIcon.className = icon.className;
                if (icon.style.color) triggerIcon.style.color = icon.style.color;
            }

            // Update Active State
            dropdown.querySelectorAll('.pj-modal-select-item, .pj-dropdown-item').forEach(i => i.classList.remove('active'));
            option.classList.add('active');

            // Update Hidden Input
            if (hiddenInputId) {
                const input = document.getElementById(hiddenInputId);
                if (input) {
                    input.value = value;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }

            // Custom Callback if defined
            const callbackName = dropdown.dataset.callback;
            if (callbackName && typeof window[callbackName] === 'function') {
                window[callbackName](value, labelText, option);
            }

            dropdown.classList.remove('open');
            e.stopPropagation();
            return;
        }

        // Close on Click Outside
        if (!e.target.closest('.pj-modal-select')) {
            document.querySelectorAll('.pj-modal-select.open').forEach(d => d.classList.remove('open'));
        }
    });
});
