<?php

namespace App\Services;

use App\Models\NotificationModel;
use App\Models\HostingModel;
use App\Models\ProjectModel;
use DateTime;

class NotificationService {
    private $notifModel;
    private $hostingModel;
    private $projectModel;

    public function __construct() {
        $this->notifModel = new NotificationModel();
        $this->hostingModel = new HostingModel();
        $this->projectModel = new ProjectModel();
    }

    /**
     * Chạy toàn bộ tiến trình quét thông báo
     */
    public function refresh() {
        $this->checkHostingExpirations();
        $this->generateWeeklyReport();
    }

    /**
     * Kiểm tra và tạo thông báo Hosting hết hạn
     */
    public function checkHostingExpirations() {
        $hostings = $this->hostingModel->getAll();
        $today = new DateTime();
        $today->setTime(0, 0, 0);

        foreach ($hostings as $h) {
            if (empty($h['expDate'])) continue;

            $expDate = new DateTime($h['expDate']);
            $expDate->setTime(0, 0, 0);
            
            $diff = $today->diff($expDate);
            $days = $diff->days;
            $isPast = $diff->invert;

            // Các mốc cần thông báo: 30, 15, 7, 1 ngày
            $milestones = [30, 15, 7, 1];
            
            if ($isPast) {
                // Đã hết hạn
                $title = "Hosting đã hết hạn";
                $message = $h['name'] . "\n" . "Hết hạn: " . date('d/m/Y', strtotime($h['expDate']));
                
                if (!$this->notifModel->exists('hosting_expired', $h['id'], $h['name'] . ' expired')) {
                    $this->notifModel->create([
                        'title' => $title,
                        'message' => $message,
                        'type' => 'error',
                        'category' => 'hosting_expired',
                        'item_id' => $h['id'],
                        'link' => '/hostings'
                    ]);
                }
            } else if (in_array($days, $milestones)) {
                // Sắp hết hạn
                $title = "Hosting sắp hết hạn";
                $message = $h['name'] . "\n" . "Hết hạn: " . date('d/m/Y', strtotime($h['expDate']));
                
                if (!$this->notifModel->exists('hosting_warning', $h['id'], $h['name'] . ' warning ' . $days)) {
                    $this->notifModel->create([
                        'title' => $title,
                        'message' => $message,
                        'type' => 'warning',
                        'category' => 'hosting_warning',
                        'item_id' => $h['id'],
                        'link' => '/hostings'
                    ]);
                }
            }
        }
    }

    /**
     * Tạo báo cáo tổng kết tuần vào sáng thứ Hai
     */
    public function generateWeeklyReport() {
        $now = new DateTime();
        $dayOfWeek = $now->format('w'); // 0 (Sun) to 6 (Sat)
        $hour = $now->format('H');

        // Chỉ chạy vào sáng thứ Hai (1: Monday)
        if ($dayOfWeek != 1) return;

        $weekNumber = $now->format('W');
        $year = $now->format('Y');
        $reportTitle = "Báo cáo tổng kết Tuần $weekNumber - $year";

        if ($this->notifModel->exists('weekly_report', $weekNumber, $reportTitle)) {
            return; // Đã tạo báo cáo cho tuần này rồi
        }

        // Tính toán dữ liệu tuần trước (Monday to Sunday)
        $lastMonday = clone $now;
        $lastMonday->modify('-7 days')->setTime(0, 0, 0);
        $lastSunday = clone $now;
        $lastSunday->modify('-1 days')->setTime(23, 59, 59);

        // Lấy danh sách dự án hoàn thành trong tuần qua
        $projects = $this->projectModel->getAll(); // Giả sử model này có dữ liệu ngày hoàn thành hoặc log
        // Ở đây mình ví dụ đơn giản lọc theo ngày 'date' trong project
        $doneCount = 0;
        $expectedRevenue = 0;

        foreach ($projects as $p) {
            $pDate = new DateTime($p['date']);
            if ($pDate >= $lastMonday && $pDate <= $lastSunday && $p['status'] == 'done') {
                $doneCount++;
            }
            // Doanh thu dự kiến từ các dự án đang làm
            if ($p['status'] != 'done' && $p['status'] != 'paused') {
                $expectedRevenue += (float)$p['value'];
            }
        }

        $message = "Trong tuần qua, bạn đã hoàn thành **$doneCount dự án**. \n";
        $message .= "Tổng doanh thu dự kiến từ các dự án đang thực hiện: **" . number_format($expectedRevenue, 0, ',', '.') . " VNĐ**.";

        $this->notifModel->create([
            'title' => $reportTitle,
            'message' => $message,
            'type' => 'info',
            'category' => 'weekly_report',
            'item_id' => $weekNumber,
            'link' => '/reports'
        ]);
    }
}
