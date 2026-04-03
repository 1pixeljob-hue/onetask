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
            $notifModel = new \App\Models\NotificationModel();
            $notifService = new \App\Services\NotificationService();
            
            // Cập nhật thông báo mới (Logic có thể tối ưu bằng cách chỉ quét 1 lần/ngày)
            $notifService->refresh();
            
            // Thêm dữ liệu bổ sung cho view
            $data['currentUser'] = $this->currentUser;
            $data['notifications'] = $notifModel->getAll(8);
            $data['unreadCount'] = $notifModel->getUnreadCount();
            
            // Giải nén dữ liệu để biến số có sẵn trong view
            extract($data);
            require_once $viewPath;
        } else {
            die("View file not found: " . $viewName);
        }
    }
}

