<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo &amp; Thống Kê - 1Pixel Dashboard</title>
    <link rel="stylesheet" href="/css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data injected from PHP Models
        const PHP_DATA = {
            projects: <?php echo json_encode($projects); ?>,
            hostings: <?php echo json_encode($hostings); ?>,
            hosting_renewals: <?php echo json_encode($hosting_renewals); ?>,
            project_payments: <?php echo json_encode($project_payments); ?>
        };
    </script>
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
        <?php 
            $activePage = 'reports';
            include APP_DIR . '/Views/partials/sidebar.php'; 
        ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <?php 
                $pageTitle = 'Báo Cáo & Thống Kê';
                $pageSubtitle = 'Xem báo cáo và thống kê chi tiết';
                include APP_DIR . '/Views/partials/header.php'; 
            ?>

            <div class="content-body">
                <!-- Page Banner -->
                <div class="report-banner">
                    <div class="banner-left">
                        <div class="banner-icon"><i class="ph ph-chart-bar"></i></div>
                        <div>
                            <h2>Báo Cáo &amp; Thống Kê</h2>
                            <p>Phân tích doanh thu và hiệu suất kinh doanh</p>
                        </div>
                    </div>
                    <div class="banner-right">
                        <div class="pj-filter-wrapper">
                            <button class="pj-filter-btn" onclick="toggleYearFilter()">
                                <i class="ph ph-calendar-blank"></i>
                                <span id="yearFilterLabel">Năm 2026</span>
                                <i class="ph ph-caret-down"></i>
                            </button>
                            <div class="pj-dropdown" id="yearFilterDropdown">
                                <!-- Populated by JS -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Report Cards -->
                <div class="report-cards-grid">
                    <!-- Card 1: Tổng Doanh Thu -->
                    <div class="report-card">
                        <div class="rc-header">
                            <div class="rc-icon green"><i class="ph ph-currency-dollar"></i></div>
                            <div class="rc-trend up" id="rcTrend1"><i class="ph ph-arrow-up-right"></i> <span id="rcTrendVal1">0%</span></div>
                        </div>
                        <div class="rc-title">Tổng Doanh Thu</div>
                        <div class="rc-value color-green" id="rcTotalRevenue">0</div>
                        <div class="rc-sub" id="rcTotalSub">Năm 2026</div>
                    </div>

                    <!-- Card 2: Doanh Thu Projects -->
                    <div class="report-card">
                        <div class="rc-header">
                            <div class="rc-icon teal"><i class="ph ph-folder"></i></div>
                            <div class="rc-trend up" id="rcTrend2"><i class="ph ph-arrow-up-right"></i> <span id="rcTrendVal2">0%</span></div>
                        </div>
                        <div class="rc-title">Doanh Thu Projects</div>
                        <div class="rc-value color-teal" id="rcProjectRevenue">0</div>
                        <div class="rc-sub" id="rcProjectSub">0 projects</div>
                        <div class="rc-progress-container">
                            <div class="rc-progress-labels">
                                <span>Tỷ trọng</span>
                                <span id="rcProjectPercent">0%</span>
                            </div>
                            <div class="rc-progress-bar"><div class="rc-progress-fill bg-teal" id="rcProjectBar" style="width: 0%"></div></div>
                        </div>
                    </div>

                    <!-- Card 3: Doanh Thu Hosting -->
                    <div class="report-card">
                        <div class="rc-header">
                            <div class="rc-icon blue"><i class="ph ph-hard-drives"></i></div>
                            <div class="rc-trend" id="rcTrend3"><i class="ph ph-arrow-up-right"></i> <span id="rcTrendVal3">0%</span></div>
                        </div>
                        <div class="rc-title">Doanh Thu Hosting</div>
                        <div class="rc-value color-blue" id="rcHostingRevenue">0</div>
                        <div class="rc-sub" id="rcHostingSub">0 hosting</div>
                        <div class="rc-progress-container">
                            <div class="rc-progress-labels">
                                <span>Tỷ trọng</span>
                                <span id="rcHostingPercent">0%</span>
                            </div>
                            <div class="rc-progress-bar"><div class="rc-progress-fill bg-blue" id="rcHostingBar" style="width: 0%"></div></div>
                        </div>
                    </div>

                    <!-- Card 4: TB Doanh Thu/Đơn -->
                    <div class="report-card">
                        <div class="rc-header">
                            <div class="rc-icon purple"><i class="ph ph-percent"></i></div>
                        </div>
                        <div class="rc-title">TB Doanh Thu/Đơn</div>
                        <div class="rc-value color-purple" id="rcAvgOrder">0</div>
                        <div class="rc-sub" id="rcAvgSub">Trên 0 đơn</div>
                        <div class="rc-breakdown">
                            <div class="rc-b-row">
                                <span class="rc-b-label">TB/Project:</span>
                                <span class="rc-b-val color-teal" id="rcAvgProject">0</span>
                            </div>
                            <div class="rc-b-row">
                                <span class="rc-b-label">TB/Hosting:</span>
                                <span class="rc-b-val color-blue" id="rcAvgHosting">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts 2 Columns -->
                <div class="charts-grid-2">
                    <div class="chart-panel">
                        <div class="chart-header">
                            <div class="ch-icon"><i class="ph ph-eye"></i></div>
                            <div>
                                <h3>Cơ Cấu Doanh Thu</h3>
                                <p>Tỷ trọng theo nguồn</p>
                            </div>
                        </div>
                        <div class="chart-content donut-layout">
                            <div class="donut-chart-wrapper">
                                <canvas id="donutChart"></canvas>
                            </div>
                            <div class="donut-legend" id="donutLegend">
                                <!-- Rendered by JS -->
                            </div>
                        </div>
                    </div>

                    <div class="chart-panel">
                        <div class="chart-header">
                            <div class="ch-icon"><i class="ph ph-chart-bar"></i></div>
                            <div>
                                <h3>So Sánh Theo Năm</h3>
                                <p>Doanh thu qua các năm</p>
                            </div>
                        </div>
                        <div class="chart-content">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Full Width Chart -->
                <div class="chart-panel">
                    <div class="chart-header">
                        <div class="ch-icon"><i class="ph ph-calendar-blank"></i></div>
                        <div>
                            <h3 id="lineChartTitle">Xu Hướng Tháng - 2026</h3>
                            <p>Doanh thu theo từng tháng</p>
                        </div>
                    </div>
                    <div class="chart-content large-chart">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                <!-- Monthly Table -->
                <div class="report-table-panel">
                    <div class="chart-header flex-align">
                        <div class="flex-align">
                            <div class="ch-icon"><i class="ph ph-eye"></i></div>
                            <div>
                                <h3 id="monthlyTableTitle">Chi Tiết Tháng - 2026</h3>
                                <p>Click để xem chi tiết</p>
                            </div>
                        </div>
                    </div>
                    <div class="table-container pt-0">
                        <table class="data-table report-table">
                            <thead>
                                <tr>
                                    <th>THÁNG</th>
                                    <th>PROJECTS</th>
                                    <th>HOSTING</th>
                                    <th>TỔNG DT</th>
                                    <th class="text-center">HÀNH ĐỘNG</th>
                                </tr>
                            </thead>
                            <tbody id="monthlyTableBody">
                                <!-- Rendered by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Yearly Table -->
                <div class="report-table-panel mt-4">
                    <div class="chart-header flex-between">
                        <div class="flex-align">
                            <div class="ch-icon"><i class="ph ph-chart-line-up"></i></div>
                            <div>
                                <h3>Bảng Chi Tiết Theo Năm</h3>
                                <p>Thống kê đầy đủ các chỉ số</p>
                            </div>
                        </div>
                        <button class="btn-add" style="height: 38px; padding: 0 16px; font-size: 13px;"><i class="ph ph-download-simple"></i> Xuất Excel</button>
                    </div>
                    <div class="table-container pt-0">
                        <table class="data-table report-table">
                            <thead>
                                <tr>
                                    <th>NĂM</th>
                                    <th>PROJECTS</th>
                                    <th>HOSTING</th>
                                    <th>TỔNG DT</th>
                                    <th>TB/ĐƠN</th>
                                    <th>TĂNG TRƯỞNG</th>
                                </tr>
                            </thead>
                            <tbody id="yearlyTableBody">
                                <!-- Rendered by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        // Initial year based on data
        let currentYear = getAllYears()[0] || new Date().getFullYear();
        let donutChartInstance = null;
        let barChartInstance = null;
        let lineChartInstance = null;
        const textColor = '#64748b';
        const gridColor = '#e2e8f0';

        // ===== Year Filter Logic =====
        function toggleYearFilter() {
            document.getElementById('yearFilterDropdown').classList.toggle('open');
        }

        function setYearFilter(year, el) {
            currentYear = parseInt(year);
            document.getElementById('yearFilterLabel').textContent = 'Năm ' + year;
            
            document.querySelectorAll('#yearFilterDropdown .pj-dropdown-item').forEach(item => {
                item.classList.remove('active');
            });
            el.classList.add('active');
            document.getElementById('yearFilterDropdown').classList.remove('open');
            
            renderAll();
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('.pj-filter-wrapper')) {
                document.getElementById('yearFilterDropdown').classList.remove('open');
            }
        });

        // ===== Populate Year Filter Dropdown =====
        function populateYearFilter() {
            const dropdown = document.getElementById('yearFilterDropdown');
            const years = getAllYears();
            
            dropdown.innerHTML = '';
            years.forEach(y => {
                const active = (y === currentYear) ? ' active' : '';
                dropdown.innerHTML += `<div class="pj-dropdown-item${active}" onclick="setYearFilter('${y}', this)">Năm ${y}</div>`;
            });

            // Đồng bộ nhãn nút lọc đầu tiên
            document.getElementById('yearFilterLabel').textContent = 'Năm ' + currentYear;
            
            // Cập nhật các tiêu đề có gắn năm
            document.getElementById('rcTotalSub').textContent = 'Năm ' + currentYear;
            document.getElementById('lineChartTitle').textContent = 'Xu Hướng Tháng - ' + currentYear;
            document.getElementById('monthlyTableTitle').textContent = 'Chi Tiết Tháng - ' + currentYear;
        }

        // ===== Render All =====
        function renderAll() {
            renderCards();
            renderDonutChart();
            renderBarChart();
            renderLineChart();
            renderMonthlyTable();
            renderYearlyTable();
        }

        // ===== Render 4 Cards =====
        function renderCards() {
            const ps = getProjectStats(currentYear);
            const hs = getHostingStats(currentYear);
            const total = ps.totalValue + hs.totalPrice;
            const totalOrders = ps.count + hs.count;
            const pPercent = total > 0 ? (ps.totalValue / total * 100).toFixed(1) : 0;
            const hPercent = total > 0 ? (hs.totalPrice / total * 100).toFixed(1) : 0;
            const avgOrder = totalOrders > 0 ? total / totalOrders : 0;
            const avgProject = ps.count > 0 ? ps.totalValue / ps.count : 0;
            const avgHosting = hs.count > 0 ? hs.totalPrice / hs.count : 0;

            // Growth
            const growth = getGrowthPercent(currentYear);

            document.getElementById('rcTotalRevenue').textContent = formatVNDShort(total);
            document.getElementById('rcTotalSub').textContent = 'Năm ' + currentYear;
            
            if (growth !== null) {
                document.getElementById('rcTrendVal1').textContent = growth + '%';
                document.getElementById('rcTrend1').className = 'rc-trend ' + (parseFloat(growth) >= 0 ? 'up' : 'down');
                document.getElementById('rcTrend1').querySelector('i').className = 'ph ' + (parseFloat(growth) >= 0 ? 'ph-arrow-up-right' : 'ph-arrow-down-right');
            } else {
                document.getElementById('rcTrendVal1').textContent = 'N/A';
            }

            // Projects
            document.getElementById('rcProjectRevenue').textContent = formatVNDShort(ps.totalValue);
            document.getElementById('rcProjectSub').textContent = ps.count + ' projects';
            document.getElementById('rcProjectPercent').textContent = pPercent + '%';
            document.getElementById('rcProjectBar').style.width = Math.max(parseFloat(pPercent), 2) + '%';

            // Hosting
            document.getElementById('rcHostingRevenue').textContent = formatVNDShort(hs.totalPrice);
            document.getElementById('rcHostingSub').textContent = hs.count + ' hosting';
            document.getElementById('rcHostingPercent').textContent = hPercent + '%';
            document.getElementById('rcHostingBar').style.width = Math.max(parseFloat(hPercent), 2) + '%';

            // Average
            document.getElementById('rcAvgOrder').textContent = formatVNDShort(avgOrder);
            document.getElementById('rcAvgSub').textContent = 'Trên ' + totalOrders + ' đơn';
            document.getElementById('rcAvgProject').textContent = formatVNDShort(avgProject);
            document.getElementById('rcAvgHosting').textContent = formatVNDShort(avgHosting);
        }

        // ===== Donut Chart =====
        function renderDonutChart() {
            const ps = getProjectStats(currentYear);
            const hs = getHostingStats(currentYear);
            const total = ps.totalValue + hs.totalPrice;
            const pPercent = total > 0 ? (ps.totalValue / total * 100).toFixed(1) : 0;
            const hPercent = total > 0 ? (hs.totalPrice / total * 100).toFixed(1) : 0;

            if (donutChartInstance) donutChartInstance.destroy();
            const ctx = document.getElementById('donutChart').getContext('2d');
            donutChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Projects', 'Hosting'],
                    datasets: [{
                        data: [parseFloat(pPercent), parseFloat(hPercent)],
                        backgroundColor: ['#2ab89c', '#3b82f6'],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    maintainAspectRatio: false
                }
            });

            document.getElementById('donutLegend').innerHTML = `
                <div class="dl-item">
                    <div class="dl-left"><div class="dot color-teal"></div><span>Projects</span></div>
                    <div class="dl-right"><strong>${formatVNDShort(ps.totalValue)}</strong><small>${pPercent}%</small></div>
                </div>
                <div class="dl-item">
                    <div class="dl-left"><div class="dot color-blue"></div><span>Hosting</span></div>
                    <div class="dl-right"><strong>${formatVNDShort(hs.totalPrice)}</strong><small>${hPercent}%</small></div>
                </div>
            `;
        }

        // ===== Bar Chart =====
        function renderBarChart() {
            const years = getAllYears();
            const projectData = years.map(y => getProjectStats(y).totalValue / 1000000);
            const hostingData = years.map(y => getHostingStats(y).totalPrice / 1000000);

            if (barChartInstance) barChartInstance.destroy();
            const ctx = document.getElementById('barChart').getContext('2d');
            barChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years.map(String),
                    datasets: [
                        { label: 'Projects', data: projectData, backgroundColor: '#2ab89c', borderRadius: 4 },
                        { label: 'Hosting', data: hostingData, backgroundColor: '#3b82f6', borderRadius: 4 }
                    ]
                },
                options: {
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } },
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { display: false } },
                        y: { border: { dash: [4, 4] }, grid: { color: gridColor, drawBorder: false }, ticks: { callback: v => v + 'M VNĐ' } }
                    }
                }
            });
        }

        // ===== Line Chart =====
        function renderLineChart() {
            const monthly = getMonthlyBreakdown(currentYear);
            const labels = monthly.map(m => 'Tháng ' + m.month);
            const projData = monthly.map(m => m.projectValue / 1000000);
            const hostData = monthly.map(m => m.hostingValue / 1000000);
            const totalData = monthly.map(m => m.total / 1000000);

            document.getElementById('lineChartTitle').textContent = 'Xu Hướng Tháng - ' + currentYear;

            if (lineChartInstance) lineChartInstance.destroy();
            const ctx = document.getElementById('lineChart').getContext('2d');
            lineChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        { label: 'Projects', data: projData, borderColor: '#2ab89c', backgroundColor: '#2ab89c', tension: 0.4 },
                        { label: 'Hosting', data: hostData, borderColor: '#3b82f6', backgroundColor: '#3b82f6', tension: 0.4 },
                        { label: 'Tổng', data: totalData, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', borderDash: [5,5], fill: true, tension: 0.4 }
                    ]
                },
                options: {
                    plugins: { 
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function (context) {
                                    return ` ${context.dataset.label}: ${formatVNDShort(context.raw * 1000000)}`;
                                }
                            }
                        }
                    },
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { borderDash: [4,4], color: gridColor } },
                        y: { grid: { borderDash: [4,4], color: gridColor }, ticks: { callback: v => v + 'M VNĐ' } }
                    }
                }
            });
        }

        // ===== Monthly Table =====
        function renderMonthlyTable() {
            document.getElementById('monthlyTableTitle').textContent = 'Chi Tiết Tháng - ' + currentYear;
            const monthly = getMonthlyBreakdown(currentYear);
            const tbody = document.getElementById('monthlyTableBody');
            let html = '';
            let totalProjVal = 0, totalHostVal = 0, totalProjCount = 0, totalHostCount = 0;

            const activeMonths = monthly.filter(m => m.total > 0);

            if (activeMonths.length === 0) {
                html = `<tr><td colspan="5" class="text-center py-4 text-muted">Không có dữ liệu doanh thu trong năm ${currentYear}</td></tr>`;
                tbody.innerHTML = html;
                return;
            }

            activeMonths.forEach(m => {
                totalProjVal += m.projectValue;
                totalHostVal += m.hostingValue;
                totalProjCount += m.projectCount;
                totalHostCount += m.hostingCount;

                const projCell = m.projectValue > 0
                    ? `<div class="cell-main color-teal">${formatVNDShort(m.projectValue)}</div><div class="cell-sub">${m.projectCount} projects</div>`
                    : `<div class="cell-sub text-muted">0 VNĐ<br>0 projects</div>`;
                const hostCell = m.hostingValue > 0
                    ? `<div class="cell-main color-blue">${formatVNDShort(m.hostingValue)}</div><div class="cell-sub">${m.hostingCount} hosting</div>`
                    : `<div class="cell-sub text-muted">0 VNĐ<br>0 hosting</div>`;
                html += `<tr>
                    <td class="td-month"><i class="ph ph-calendar-blank"></i> Tháng ${m.month}</td>
                        <td>${projCell}</td>
                        <td>${hostCell}</td>
                        <td><strong class="color-green">${formatVNDShort(m.total)}</strong></td>
                        <td class="text-center"><button class="btn-sm btn-teal" onclick="openMonthDetail(${m.month}, ${currentYear})"><i class="ph ph-eye"></i> Xem Chi Tiết</button></td>
                    </tr>`;
            });

            // Summary row
            const grandTotal = totalProjVal + totalHostVal;
            html += `<tr class="table-summary-row">
                <td>TỔNG NĂM ${currentYear}</td>
                <td><div class="cell-main color-teal">${formatVNDShort(totalProjVal)}</div><div class="cell-sub">${totalProjCount} projects</div></td>
                <td><div class="cell-main color-blue">${formatVNDShort(totalHostVal)}</div><div class="cell-sub">${totalHostCount} hosting</div></td>
                <td><strong class="color-green">${formatVNDShort(grandTotal)}</strong></td>
                <td></td>
            </tr>`;

            tbody.innerHTML = html;
        }

        // ===== Yearly Table =====
        function renderYearlyTable() {
            const years = getAllYears();
            const tbody = document.getElementById('yearlyTableBody');
            let html = '';
            let grandProjVal = 0, grandHostVal = 0, grandProjCount = 0, grandHostCount = 0;

            years.forEach(y => {
                const ps = getProjectStats(y);
                const hs = getHostingStats(y);
                const total = ps.totalValue + hs.totalPrice;
                const orders = ps.count + hs.count;
                const avg = orders > 0 ? total / orders : 0;
                const growth = getGrowthPercent(y);

                grandProjVal += ps.totalValue;
                grandHostVal += hs.totalPrice;
                grandProjCount += ps.count;
                grandHostCount += hs.count;

                const isViewing = y === currentYear;
                const yearBadge = isViewing ? ` <span class="teal-badge">Đang xem</span>` : '';
                
                const growthCell = growth !== null
                    ? `<td class="color-green font-bold"><i class="ph ph-arrow-up-right"></i> ${growth}%</td>`
                    : `<td class="text-muted">N/A</td>`;

                const projCell = ps.totalValue > 0
                    ? `<div class="cell-main color-teal font-bold">${formatVNDShort(ps.totalValue)}</div><div class="cell-sub">${ps.count} projects</div>`
                    : `<div class="cell-sub text-muted">0 VNĐ<br>0 projects</div>`;
                const hostCell = hs.totalPrice > 0
                    ? `<div class="cell-main color-blue font-bold">${formatVNDShort(hs.totalPrice)}</div><div class="cell-sub">${hs.count} hosting</div>`
                    : `<div class="cell-sub text-muted">0 VNĐ<br>0 hosting</div>`;

                html += `<tr>
                    <td class="td-year font-bold"><i class="ph ph-calendar-blank"></i> ${y}${yearBadge}</td>
                    <td>${projCell}</td>
                    <td>${hostCell}</td>
                    <td><strong class="color-green font-bold">${formatVNDShort(total)}</strong></td>
                    <td><strong class="font-bold">${formatVNDShort(avg)}</strong></td>
                    ${growthCell}
                </tr>`;
            });

            // Summary
            const grandTotal = grandProjVal + grandHostVal;
            html += `<tr class="table-summary-row">
                <td class="font-bold">TỔNG CỘNG</td>
                <td><div class="cell-main color-teal font-bold">${formatVNDShort(grandProjVal)}</div><div class="cell-sub">${grandProjCount} projects</div></td>
                <td><div class="cell-main color-blue font-bold">${formatVNDShort(grandHostVal)}</div><div class="cell-sub">${grandHostCount} hosting</div></td>
                <td><strong class="color-green font-bold">${formatVNDShort(grandTotal)}</strong></td>
                <td></td>
                <td class="text-right"><span class="text-muted text-sm">${years.length} năm hoạt động</span></td>
            </tr>`;

            tbody.innerHTML = html;
        }

        populateYearFilter();
        renderAll();

        // ===== Modal Logic =====
        function openMonthDetail(month, year) {
            const modal = document.getElementById('reportDetailModal');
            
            // 1. Lọc các đợt thanh toán (Milestones) trong tháng/năm này
            const paidMilestones = PROJECT_PAYMENTS.filter(p => {
                const date = new Date(p.paid_at);
                return date.getFullYear() === year && (date.getMonth() + 1) === month;
            });

            // 2. Lọc các đợt gia hạn Hosting trong tháng/năm này
            const hostings = HOSTING_RENEWALS.filter(r => {
                const date = new Date(r.reg_date);
                return date.getFullYear() === year && (date.getMonth() + 1) === month;
            });
            
            const totalProj = paidMilestones.reduce((s, p) => s + (Number(p.amount) || 0), 0);
            const totalHost = hostings.reduce((s, h) => s + (Number(h.amount) || 0), 0);
            const totalRevenue = totalProj + totalHost;

            // 3. Update Header
            document.getElementById('rmTitle').textContent = `Chi Tiết Thu Nhập Tháng ${month} / ${year}`;
            
            // 4. Summary Cards
            document.getElementById('rmSummaryCards').innerHTML = `
                <div class="rm-stat-card green">
                    <div class="rm-stat-label">Tổng Doanh Thu</div>
                    <div class="rm-stat-value color-green">${formatVNDFull(totalRevenue)}</div>
                </div>
                <div class="rm-stat-card teal">
                    <div class="rm-stat-label">Đợt Thanh Toán (${paidMilestones.length})</div>
                    <div class="rm-stat-value color-teal">${formatVNDFull(totalProj)}</div>
                </div>
                <div class="rm-stat-card blue">
                    <div class="rm-stat-label">Gia Hạn Hosting (${hostings.length})</div>
                    <div class="rm-stat-value color-blue">${formatVNDFull(totalHost)}</div>
                </div>
            `;

            // 5. Milestone List (Thay cho Project List)
            let projHtml = '';
            if (paidMilestones.length > 0) {
                projHtml = `
                    <div class="rm-section-title"><i class="ph ph-hand-coins"></i> Các Đợt Thanh Toán (${paidMilestones.length})</div>
                    <table class="rm-table">
                        <thead>
                            <tr>
                                <th>Tên Dự Án / Đợt</th>
                                <th>Ghi Chú</th>
                                <th>Ngày Thu</th>
                                <th class="text-right">Số Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${paidMilestones.map(p => {
                                const [datePart, timePart] = p.paid_at.split(' ');
                                return `
                                    <tr>
                                        <td>
                                            <div class="rm-project-name">${p.project_name || 'N/A'}</div>
                                            <div class="cell-sub"><i class="ph ph-tag"></i> ${p.milestone_name}</div>
                                        </td>
                                        <td><div class="rm-project-desc">${p.notes || ''}</div></td>
                                        <td class="rm-date">
                                            ${formatDateVN(datePart)}
                                            <div class="cell-sub">${timePart.substring(0, 5)}</div>
                                        </td>
                                        <td class="rm-value color-teal text-right">${formatVNDFull(parseFloat(p.amount))}</td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                `;
            }

            // 4. Hosting List
            let hostHtml = '';
            if (hostings.length > 0) {
                hostHtml = `
                    <div class="rm-section-title"><i class="ph ph-hard-drives"></i> Hosting (${hostings.length})</div>
                    <table class="rm-table">
                        <thead>
                            <tr>
                                <th>Tên Hosting / Domain</th>
                                <th>Nhà Cung Cấp</th>
                                <th>Ngày ĐK</th>
                                <th class="text-right">Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${hostings.map(h => {
                                const hMaster = RAW_HOSTINGS.find(m => m.id == h.hosting_id);
                                const hName = hMaster ? hMaster.name : 'Hosting #' + h.hosting_id;
                                const hDomain = hMaster ? hMaster.domain : 'N/A';
                                const hProvider = hMaster ? hMaster.provider : 'N/A';
                                return `
                                    <tr>
                                        <td>
                                            <div class="rm-project-name">${hName}</div>
                                            <div class="rm-project-desc">${hDomain}</div>
                                        </td>
                                        <td><div class="rm-c-info"><i class="ph ph-cloud"></i> ${hProvider}</div></td>
                                        <td class="rm-date">${formatDateVN(h.reg_date)}</td>
                                        <td class="rm-value color-blue text-right">${formatVNDFull(h.amount)}</td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                `;
            }

            document.getElementById('rmContent').innerHTML = projHtml + hostHtml;
            
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeReportModal() {
            document.getElementById('reportDetailModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        // Close on escape
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeReportModal();
        });
    </script>

    <!-- Report Modal -->
    <div class="report-modal" id="reportDetailModal">
        <div class="rm-container">
            <div class="rm-header">
                <div class="rm-h-left">
                    <div class="rm-h-icon"><i class="ph ph-calendar-blank"></i></div>
                    <div class="rm-h-info">
                        <h2 id="rmTitle">Chi Tiết Tháng</h2>
                        <p>Danh sách đầy đủ projects và hosting trong tháng</p>
                    </div>
                </div>
                <div class="rm-close" onclick="closeReportModal()"><i class="ph ph-x"></i></div>
            </div>
            <div class="rm-body">
                <div class="rm-summary-cards" id="rmSummaryCards">
                    <!-- Stat cards here -->
                </div>
                <div id="rmContent">
                    <!-- Lists here -->
                </div>
            </div>
        </div>
    </div>

    <?php include APP_DIR . '/Views/partials/footer.php'; ?>
</body>
</html>
