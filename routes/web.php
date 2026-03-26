<?php
// Default dashboard route
$router->add('/', 'MainController', 'dashboard');
$router->add('/index', 'MainController', 'dashboard');

// Modules routing
$router->add('/hostings', 'MainController', 'hostings');
$router->add('/projects', 'MainController', 'projects');
$router->add('/reports', 'MainController', 'reports');
$router->add('/passwords', 'MainController', 'passwords');
$router->add('/codex', 'MainController', 'codex');
$router->add('/logs', 'MainController', 'logs');
$router->add('/settings', 'MainController', 'settings');
