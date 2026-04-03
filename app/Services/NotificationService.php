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
        $this->cleanupDuplicates(); // Dọn dẹp bản ghi trùng lặp (nếu có)
        $this->checkHostingExpirations();
        $this->generateWeeklyReport();
    }

    /**
     * Quét và tạo thông báo hết hạn Hosting
     */
    private function checkHostingExpirations() {
        $hostingModel = new \App\Models\HostingModel();
        $hostings = $hostingModel->getAll();
        $today = new \DateTime();

        // Các mốc thời gian cảnh báo (Sắp xếp tăng dần để break ở mốc nhỏ nhất/cấp bách nhất)
        $milestones = [1, 3, 7, 15, 30];

        foreach ($hostings as $h) {
            if (empty($h['exp_date'])) continue;

            $expDate = new \DateTime($h['exp_date']);
            $interval = $today->diff($expDate);
            $daysDiff = $interval->days;

            // Nếu đã hết hạn (invert == 1)
            if ($interval->invert == 1) {
                $title = "Hosting đã hết hạn";
                if (!$this->notifModel->exists('hosting_warning', $h['id'], $title)) {
                    $this->notifModel->create([
                        'title' => $title,
                        'message' => "Hosting {$h['name']} ({$h['domain']}) đã hết hạn vào ngày " . $expDate->format('d/m/Y') . ".\nVui lòng gia hạn ngay để tránh gián đoạn dịch vụ.",
                        'type' => 'error',
                        'category' => 'hosting_warning',
                        'item_id' => $h['id'],
                        'link' => '/hostings'
                    ]);
                }
                continue;
            }

            // Kiểm tra các mốc cảnh báo (chọn mốc cấp bách nhất hiện có)
            foreach ($milestones as $m) {
                if ($daysDiff <= $m) {
                    $title = "Hosting sắp hết hạn ($m ngày)";
                    if (!$this->notifModel->exists('hosting_warning', $h['id'], $title)) {
                        $this->notifModel->create([
                            'title' => $title,
                            'message' => "Hosting {$h['name']} ({$h['domain']}) sẽ hết hạn trong $daysDiff ngày tới.\nNgày hết hạn: " . $expDate->format('d/m/Y'),
                            'type' => 'warning',
                            'category' => 'hosting_warning',
                            'item_id' => $h['id'],
                            'link' => '/hostings'
                        ]);
                    }
                    // Dừng lại sau mốc đầu tiên (quan trọng nhất) thỏa mãn điều kiện
                    break;
                }
            }
        }
    }

    /**
     * Dọn dẹp các thông báo bị trùng lặp (nếu có lỗi logic cũ)
     */
    private function cleanupDuplicates() {
        try {
            $db = \App\Core\Database::getInstance()->getConnection();
            
            // Xóa các thông báo trùng cùng item_id, category, title (giữ lại id thấp nhất)
            // Sử dụng <=> (NULL-safe equality) trong MySQL
            $sql = "DELETE n1 FROM notifications n1
                    INNER JOIN notifications n2 ON TRIM(n1.title) = TRIM(n2.title) 
                        AND n1.category = n2.category 
                        AND (n1.item_id <=> n2.item_id)
                    WHERE n1.id > n2.id";
            
            $db->exec($sql);
        } catch (\Exception $e) {
            error_log("Cleanup Duplicates Error: " . $e->getMessage());
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
