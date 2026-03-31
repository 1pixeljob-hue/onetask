<?php
namespace App\Core;

use App\Controllers\AuthController;

class BaseController {
    protected $requireAuth = true;

    public function __construct() {
        if ($this->requireAuth) {
            AuthController::checkAuth();
        }
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
