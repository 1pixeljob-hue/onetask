<?php
// Define absolute paths
define('ROOT_DIR', __DIR__);
define('APP_DIR', ROOT_DIR . '/app');
define('CORE_DIR', APP_DIR . '/Core');

// Simple Autoloader for Controllers and Core
// Simple Autoloader for App namespace
spl_autoload_register(function($class) {
    $prefix = 'App\\';
    $base_dir = APP_DIR . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        // Fallback for non-namespaced core classes if any
        if (file_exists(CORE_DIR . '/' . $class . '.php')) {
            require_once CORE_DIR . '/' . $class . '.php';
        }
    }
});

// Load the Router
require_once CORE_DIR . '/Router.php';

// Bootstrap the application
$router = new Router();
require_once ROOT_DIR . '/routes/web.php';
$router->dispatch();
