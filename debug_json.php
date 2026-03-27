<?php
define('APP_DIR', __DIR__ . '/app');
require_once 'app/Core/Database.php';
require_once 'app/Models/ProjectModel.php';
use App\Models\ProjectModel;
try {
    $pm = new ProjectModel();
    $projects = $pm->getAll();
    echo json_encode($projects, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
