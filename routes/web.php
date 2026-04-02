<?php
// Auth routes
$router->add('/login', 'App\Controllers\AuthController', 'showLogin');
$router->add('/authenticate', 'App\Controllers\AuthController', 'login'); // Handle POST
$router->add('/logout', 'App\Controllers\AuthController', 'logout');

// Default dashboard route
$router->add('/', 'App\Controllers\MainController', 'dashboard');
$router->add('/index', 'App\Controllers\MainController', 'dashboard');

// Modules routing
$router->add('/hostings', 'App\Controllers\MainController', 'hostings');
$router->add('/projects', 'App\Controllers\MainController', 'projects');
$router->add('/reports', 'App\Controllers\MainController', 'reports');
$router->add('/passwords', 'App\Controllers\MainController', 'passwords');
$router->add('/codex', 'App\Controllers\MainController', 'codex');
$router->add('/logs', 'App\Controllers\MainController', 'logs');
$router->add('/settings', 'App\Controllers\MainController', 'settings');

// API/Action Routes
$router->add('/projects/save', 'App\Controllers\MainController', 'saveProject');
$router->add('/projects/delete', 'App\Controllers\MainController', 'deleteProject');
$router->add('/projects/bulk-delete', 'App\Controllers\MainController', 'deleteProjectsBulk');
$router->add('/hostings/save', 'App\Controllers\MainController', 'saveHosting');
$router->add('/hostings/delete', 'App\Controllers\MainController', 'deleteHosting');
$router->add('/hostings/bulk-delete', 'App\Controllers\MainController', 'deleteHostingsBulk');
$router->add('/hostings/renew', 'App\Controllers\MainController', 'renewHosting');
$router->add('/hostings/renewals', 'App\Controllers\MainController', 'getHostingRenewals');
$router->add('/passwords/save', 'App\Controllers\MainController', 'savePassword');
$router->add('/passwords/delete', 'App\Controllers\MainController', 'deletePassword');
$router->add('/passwords/categories/save', 'App\Controllers\MainController', 'saveCategory');
$router->add('/passwords/categories/delete', 'App\Controllers\MainController', 'deleteCategory');

// Codex Snippet Routes
$router->add('/codex/save', 'App\Controllers\MainController', 'saveSnippet');
$router->add('/codex/delete', 'App\Controllers\MainController', 'deleteSnippet');
$router->add('/codex/categories/save', 'App\Controllers\MainController', 'saveCodeCategory');
$router->add('/codex/categories/delete', 'App\Controllers\MainController', 'deleteCodeCategory');

// Log Routes
$router->add('/logs/delete', 'App\Controllers\MainController', 'deleteLog');
$router->add('/logs/restore', 'App\Controllers\MainController', 'restoreLog');
