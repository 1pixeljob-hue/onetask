<?php
namespace App\Core;

class BaseController {
    protected $requireAuth = true;
    protected $currentUser = null;
    protected $userModel;

    public function __construct() {
        $this->userModel = new \App\Models\UserModel();
        if ($this->requireAuth) {
            $this->checkAuthentication();
        } else {
            // Even if auth not required, try to get user for top bar
            if (isset($_SESSION['user_id'])) {
                $this->currentUser = $this->userModel->find($_SESSION['user_id']);
            }
        }
    }

    /**
     * Kiểm tra trạng thái đăng nhập trực tiếp tại BaseController
     */
    protected function checkAuthentication() {
        if (isset($_SESSION['user_id'])) {
            $this->currentUser = $this->userModel->find($_SESSION['user_id']);
            if (!$this->currentUser) {
                session_unset();
                session_destroy();
                header('Location: /login');
                exit;
            }
            return;
        }

        // Kiểm tra Cookie (Remember Me) nếu session trống
        if (isset($_COOKIE['remember_me'])) {
            $user = $this->userModel->findByToken($_COOKIE['remember_me']);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $this->currentUser = $user;
                return;
            }
        }

        header('Location: /login');
        exit;
    }

    public function view($viewName, $data = []) {
        $viewPath = APP_DIR . '/Views/' . $viewName . '.php';
        if (file_exists($viewPath)) {
            // Khởi tạo các model cần thiết cho phần Header (Thông báo)
            try {
                $notifModel = new \App\Models\NotificationModel();
                $notifService = new \App\Services\NotificationService();
                
                // Cập nhật thông báo mới (Tối ưu: chỉ quét mỗi 30 phút một lần)
                $currentTime = time();
                // Temporarily disabled for cleanup: 
                // if (!isset($_SESSION['last_notif_refresh']) || ($currentTime - $_SESSION['last_notif_refresh'] > 1800)) {
                    $notifService->refresh();
                    $_SESSION['last_notif_refresh'] = $currentTime;
                // }
                
                // Thêm dữ liệu bổ sung cho view (Mặc định mảng rỗng để tránh lỗi foreach)
                $data['notifications'] = $notifModel->getAll(8) ?: [];
                $data['unreadCount'] = (int)($notifModel->getUnreadCount() ?: 0);
            } catch (\Exception $e) {
                // Fallback nếu database chưa sẵn sàng hoặc có lỗi
                error_log("Notification System Error: " . $e->getMessage());
                $data['notifications'] = [];
                $data['unreadCount'] = 0;
            }
            
            $data['currentUser'] = $this->currentUser;
            $data['notifications'] = $data['notifications'] ?? [];
            $data['unreadCount'] = $data['unreadCount'] ?? 0;
            
            // Giải nén dữ liệu để biến số có sẵn trong view
            extract($data);
            require_once $viewPath;
        } else {
            die("View file not found: " . $viewName);
        }
    }
}

