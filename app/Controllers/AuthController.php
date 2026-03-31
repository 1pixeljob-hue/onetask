<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController {
    protected $requireAuth = false;
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function showLogin() {
        if (self::checkAuth(false)) {
            header('Location: /');
            exit;
        }
        $this->view('login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login() {
        ob_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identity = $_POST['identity'] ?? '';
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);

            $user = $this->userModel->findByLogin($identity);

            if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                // Đăng nhập thành công
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Xử lý Remember Me (24h)
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $this->userModel->updateRememberToken($user['id'], $token);
                    // Cookie lưu trong 24h
                    setcookie('remember_me', $token, time() + (24 * 60 * 60), '/', '', false, true);
                }

                header('Location: /');
                exit;
            } else {
                $data = ['error' => 'Thông tin đăng nhập không chính xác!'];
                $this->view('login', $data);
            }
        }
    }

    /**
     * Đăng xuất
     */
    public function logout() {
        // Xóa Token trong DB nếu có
        if (isset($_SESSION['user_id'])) {
            $this->userModel->updateRememberToken($_SESSION['user_id'], null);
        }

        // Xóa Session
        session_unset();
        session_destroy();

        // Xóa Cookie
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, '/');
        }

        header('Location: /login');
        exit;
    }

    /**
     * Kiểm tra trạng thái đăng nhập (được gọi từ Controllers khác)
     * @param bool $redirect Nếu true sẽ redirect về login nếu chưa đăng nhập
     */
    public static function checkAuth($redirect = true) {
        if (self::isLoggedIn()) {
            return true;
        }

        // Nếu chưa có session, kiểm tra Cookie (Remember Me)
        if (isset($_COOKIE['remember_me'])) {
            $userModel = new UserModel();
            $user = $userModel->findByToken($_COOKIE['remember_me']);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                return true;
            }
        }

        if ($redirect) {
            header('Location: /login');
            exit;
        }
        
        return false;
    }

    /**
     * Helper: Kiểm tra session có tồn tại không
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
