<?php
// Define absolute paths
define('ROOT_DIR', __DIR__);
define('APP_DIR', ROOT_DIR . '/app');
define('CORE_DIR', APP_DIR . '/Core');

// Simple Autoloader for Controllers and Core
// Simple Autoloader for App namespace
spl_autoload_register(function($class) {
    // 1. Check for App\ namespace
    $prefix = 'App\\';
    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $file = APP_DIR . '/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    // 2. Fallback for non-namespaced Controllers
    if (file_exists(APP_DIR . '/Controllers/' . $class . '.php')) {
        require_once APP_DIR . '/Controllers/' . $class . '.php';
        return;
    }

    // 3. Fallback for non-namespaced Core classes
    if (file_exists(CORE_DIR . '/' . $class . '.php')) {
        require_once CORE_DIR . '/' . $class . '.php';
        return;
    }
});

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load the Router
require_once 'app/Core/Router.php';

// Bootstrap the application
$router = new Router();
require_once ROOT_DIR . '/routes/web.php';
$router->dispatch();
