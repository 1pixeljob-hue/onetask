<?php
// Define absolute paths
define('ROOT_DIR', __DIR__);
define('APP_DIR', ROOT_DIR . '/app');
define('CORE_DIR', APP_DIR . '/Core');

// Simple Autoloader for Controllers and Core
spl_autoload_register(function($class) {
    if (file_exists(APP_DIR . '/Controllers/' . $class . '.php')) {
        require_once APP_DIR . '/Controllers/' . $class . '.php';
    } elseif (file_exists(CORE_DIR . '/' . $class . '.php')) {
        require_once CORE_DIR . '/' . $class . '.php';
    }
});

// Load the Router
require_once CORE_DIR . '/Router.php';

// Bootstrap the application
$router = new Router();
require_once ROOT_DIR . '/routes/web.php';
$router->dispatch();
