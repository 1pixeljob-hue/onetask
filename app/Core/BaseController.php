<?php
namespace App\Core;

class BaseController {
    protected $requireAuth = true;

    public function __construct() {
        if ($this->requireAuth) {
            $this->checkAuthentication();
        }
    }

    /**
     * Kiểm tra trạng thái đăng nhập trực tiếp tại BaseController
     */
    protected function checkAuthentication() {
        if (isset($_SESSION['user_id'])) {
            return;
        }

        // Kiểm tra Cookie (Remember Me) nếu session trống
        if (isset($_COOKIE['remember_me'])) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->findByToken($_COOKIE['remember_me']);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                return;
            }
        }

        header('Location: /login');
        exit;
    }

    public function view($viewName, $data = []) {
        $viewPath = APP_DIR . '/Views/' . $viewName . '.php';
        if (file_exists($viewPath)) {
            // Extract data so variables are available in the view
            extract($data);
            require_once $viewPath;
        } else {
            die("View file not found: " . $viewName);
        }
    }
}
