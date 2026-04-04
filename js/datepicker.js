/**
 * Modern DatePicker Component
 * Developed for 1Pixel Dashboard
 */

class DatePicker {
    constructor(inputElement, options = {}) {
        const originalInput = typeof inputElement === 'string' ? document.getElementById(inputElement) : inputElement;
        if (!originalInput) return;

        this.originalInput = originalInput;
        this.options = {
            format: 'DD/MM/YYYY',
            valueFormat: 'YYYY-MM-DD',
            yearRange: [2000, 2050],
            onSelect: null,
            onClear: null,
            ...options
        };

        // Create display input (proxy)
        this.input = document.createElement('input');
        this.input.type = 'text';
        this.input.className = this.originalInput.className;
        this.input.placeholder = this.originalInput.placeholder || 'DD/MM/YYYY';
        this.input.readOnly = true; // Prevent typing, use picker
        this.input.setAttribute('autocomplete', 'off');
        
        // Copy some styles/attributes
        this.input.style.cursor = 'pointer';
        
        // Hide original and insert proxy
        this.originalInput.style.display = 'none';
        this.originalInput.parentNode.insertBefore(this.input, this.originalInput.nextSibling);

        this.currentDate = new Date();
        this.selectedDate = null;
        this.isOpen = false;
        this.mode = 'days'; 

        // Parse initial value from original input
        if (this.originalInput.value) {
            const parsed = this.parseDate(this.originalInput.value);
            if (parsed) {
                this.selectedDate = parsed;
                this.currentDate = new Date(parsed);
                this.input.value = this.formatDate(this.selectedDate, this.options.format);
            }
        }

        this.init();
    }

    init() {
        // Build Container
        this.container = document.createElement('div');
        this.container.className = 'datepicker-container';
        document.body.appendChild(this.container);

        // Event Listeners on the proxy input
        this.input.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggle();
        });

        // Click outside to close
        document.addEventListener('click', (e) => {
            if (this.isOpen && !this.container.contains(e.target) && e.target !== this.input) {
                this.hide();
            }
        });

        // Reposition on scroll/resize
        window.addEventListener('resize', () => { if (this.isOpen) this.reposition(); });
        window.addEventListener('scroll', () => { if (this.isOpen) this.reposition(); }, true);

        // Handle value updates from external code
        // We can use a MutationObserver or just a simple interval/custom event if needed
        // But for now, let's assume external code sets .value on the original input
        this.syncInterval = setInterval(() => {
            if (!this.isOpen && this.originalInput.value !== this.lastOriginalValue) {
                const parsed = this.parseDate(this.originalInput.value);
                if (parsed) {
                    this.selectedDate = parsed;
                    this.input.value = this.formatDate(parsed, this.options.format);
                } else if (!this.originalInput.value) {
                    this.selectedDate = null;
                    this.input.value = '';
                }
                this.lastOriginalValue = this.originalInput.value;
            }
        }, 500);
    }

    render() {
        const monthNames = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        const dayNames = ['Th 2', 'Th 3', 'Th 4', 'Th 5', 'Th 6', 'Th 7', 'CN'];

        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();

        let html = `
            <div class="datepicker-header">
                <button class="datepicker-nav-btn prev-month"><i class="ph ph-caret-left"></i></button>
                <div class="datepicker-current-month">
                    <span class="datepicker-month-year month-label">${monthNames[month]} <i class="ph ph-caret-down"></i></span>
                    <span class="datepicker-month-year year-label">${year} <i class="ph ph-caret-down"></i></span>
                </div>
                <button class="datepicker-nav-btn next-month"><i class="ph ph-caret-right"></i></button>
            </div>
            
            <div class="datepicker-grid">
                ${dayNames.map(d => `<div class="datepicker-day-header">${d}</div>`).join('')}
                ${this.generateDaysHTML()}
            </div>

            <div class="datepicker-footer">
                <button class="datepicker-footer-btn datepicker-btn-clear">Xóa</button>
                <button class="datepicker-footer-btn datepicker-btn-today">Hôm nay</button>
            </div>

            <div class="datepicker-select-overlay month-overlay">
                ${monthNames.map((m, i) => `<div class="datepicker-select-item ${i === month ? 'active' : ''}" data-month="${i}">${m}</div>`).join('')}
            </div>

            <div class="datepicker-select-overlay year-overlay">
                ${this.generateYearsHTML()}
            </div>
        `;

        this.container.innerHTML = html;
        this.attachEvents();
    }

    generateDaysHTML() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        
        // JS getDay(): 0 is Sunday. We want Monday (1) to be first index (0 in our grid).
        // Grid: 0=Mon, 1=Tue, ..., 5=Sat, 6=Sun
        let firstDayIndex = firstDay.getDay() - 1;
        if (firstDayIndex === -1) firstDayIndex = 6; // Sunday

        const prevLastDay = new Date(year, month, 0).getDate();
        const totalDays = lastDay.getDate();

        let daysHTML = '';

        // Previous month days
        for (let i = firstDayIndex; i > 0; i--) {
            daysHTML += `<div class="datepicker-day other-month disabled">${prevLastDay - i + 1}</div>`;
        }

        // Current month days
        const today = new Date();
        const todayKey = `${today.getDate()}-${today.getMonth()}-${today.getFullYear()}`;
        const selectedKey = this.selectedDate ? `${this.selectedDate.getDate()}-${this.selectedDate.getMonth()}-${this.selectedDate.getFullYear()}` : null;

        for (let i = 1; i <= totalDays; i++) {
            const currentKey = `${i}-${month}-${year}`;
            const isToday = currentKey === todayKey;
            const isSelected = currentKey === selectedKey;
            
            daysHTML += `
                <div class="datepicker-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''}" data-day="${i}">
                    ${i}
                </div>`;
        }

        // Next month days
        const remainingCells = 42 - (firstDayIndex + totalDays);
        for (let i = 1; i <= remainingCells; i++) {
            daysHTML += `<div class="datepicker-day other-month disabled">${i}</div>`;
        }

        return daysHTML;
    }

    generateYearsHTML() {
        const currentYear = this.currentDate.getFullYear();
        let years = '';
        for (let y = this.options.yearRange[0]; y <= this.options.yearRange[1]; y++) {
            years += `<div class="datepicker-select-item ${y === currentYear ? 'active' : ''}" data-year="${y}">${y}</div>`;
        }
        return years;
    }

    attachEvents() {
        // Nav
        this.container.querySelector('.prev-month').onclick = (e) => { e.stopPropagation(); this.shiftMonth(-1); };
        this.container.querySelector('.next-month').onclick = (e) => { e.stopPropagation(); this.shiftMonth(1); };

        // Days
        this.container.querySelectorAll('.datepicker-day:not(.other-month)').forEach(dayEl => {
            dayEl.onclick = (e) => {
                e.stopPropagation();
                const day = parseInt(dayEl.dataset.day);
                this.selectDate(new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), day));
            };
        });

        // Overlays
        const monthLabel = this.container.querySelector('.month-label');
        const yearLabel = this.container.querySelector('.year-label');
        const monthOverlay = this.container.querySelector('.month-overlay');
        const yearOverlay = this.container.querySelector('.year-overlay');

        monthLabel.onclick = (e) => {
            e.stopPropagation();
            yearOverlay.classList.remove('show');
            monthOverlay.classList.toggle('show');
        };

        yearLabel.onclick = (e) => {
            e.stopPropagation();
            monthOverlay.classList.remove('show');
            yearOverlay.classList.toggle('show');
            // Scroll to active year
            setTimeout(() => {
                const active = yearOverlay.querySelector('.active');
                if (active) active.scrollIntoView({ block: 'center' });
            }, 50);
        };

        monthOverlay.querySelectorAll('.datepicker-select-item').forEach(item => {
            item.onclick = (e) => {
                e.stopPropagation();
                this.currentDate.setMonth(parseInt(item.dataset.month));
                monthOverlay.classList.remove('show');
                this.render();
            };
        });

        yearOverlay.querySelectorAll('.datepicker-select-item').forEach(item => {
            item.onclick = (e) => {
                e.stopPropagation();
                this.currentDate.setFullYear(parseInt(item.dataset.year));
                yearOverlay.classList.remove('show');
                this.render();
            };
        });

        // Footer
        this.container.querySelector('.datepicker-btn-clear').onclick = (e) => {
            e.stopPropagation();
            this.clear();
        };

        this.container.querySelector('.datepicker-btn-today').onclick = (e) => {
            e.stopPropagation();
            this.selectDate(new Date(), true);
        };
    }

    shiftMonth(dir) {
        this.currentDate.setMonth(this.currentDate.getMonth() + dir);
        this.render();
    }

    selectDate(date, close = true) {
        this.selectedDate = new Date(date);
        
        // Update Proxy (Display)
        this.input.value = this.formatDate(this.selectedDate, this.options.format);
        
        // Update Original (Value)
        const val = this.formatDate(this.selectedDate, this.options.valueFormat);
        this.originalInput.value = val;
        this.lastOriginalValue = val;
        
        // Trigger events
        this.originalInput.dispatchEvent(new Event('change', { bubbles: true }));
        this.originalInput.dispatchEvent(new Event('input', { bubbles: true }));
        this.input.classList.remove('modal-input-error');

        if (this.options.onSelect) this.options.onSelect(this.selectedDate);
        
        if (close) {
            this.hide();
        } else {
            this.render();
        }
    }

    clear() {
        this.selectedDate = null;
        this.input.value = '';
        this.originalInput.value = '';
        this.lastOriginalValue = '';
        if (this.options.onClear) this.options.onClear();
        this.hide();
    }

    formatDate(date, format) {
        const d = String(date.getDate()).padStart(2, '0');
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const y = date.getFullYear();
        
        if (format === 'DD/MM/YYYY') return `${d}/${m}/${y}`;
        if (format === 'YYYY-MM-DD') return `${y}-${m}-${d}`;
        return `${d}/${m}/${y}`;
    }

    parseDate(str) {
        if (!str) return null;
        // Support DD/MM/YYYY or YYYY-MM-DD
        if (str.includes('/')) {
            const [d, m, y] = str.split('/');
            return new Date(y, m - 1, d);
        } else if (str.includes('-')) {
            const [y, m, d] = str.split('-');
            return new Date(y, m - 1, d);
        }
        return null;
    }

    show() {
        // Hide all other datepickers first
        document.querySelectorAll('.datepicker-container').forEach(c => c.classList.remove('show'));
        
        this.render();
        this.reposition();
        this.container.classList.add('show');
        this.isOpen = true;
    }

    hide() {
        this.container.classList.remove('show');
        this.isOpen = false;
    }

    toggle() {
        if (this.isOpen) this.hide();
        else this.show();
    }

    reposition() {
        const rect = this.input.getBoundingClientRect();
        const pickerWidth = 320;
        const pickerHeight = 380;
        const margin = 8;

        let left = rect.left;
        let top = rect.bottom + window.scrollY + margin;

        // Horizontal boundary check
        if (left + pickerWidth > window.innerWidth) {
            left = window.innerWidth - pickerWidth - 20;
        }
        if (left < 10) left = 10;

        // Vertical boundary check
        const spaceBelow = window.innerHeight - rect.bottom;
        if (spaceBelow < pickerHeight && rect.top > pickerHeight) {
            top = rect.top + window.scrollY - pickerHeight - margin;
        }

        this.container.style.left = left + 'px';
        this.container.style.top = top + 'px';
    }
}

// Global initialization helper
function initDatePicker(id, options = {}) {
    return new DatePicker(id, options);
}
