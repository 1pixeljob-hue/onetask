<?php
define('APP_DIR', __DIR__ . '/app');
require_once 'app/Core/Database.php';
require_once 'app/Models/ProjectModel.php';
require_once 'app/Models/HostingModel.php';

use App\Models\ProjectModel;
use App\Models\HostingModel;

try {
    $pm = new ProjectModel();
    $projects = $pm->getAll();
    echo "DEBUG_PROJECT_COUNT: " . count($projects) . "\n";
    foreach ($projects as $p) {
        echo "PROJECT: " . $item['name'] . " (ID: " . $item['id'] . ")\n";
    }

    $hm = new HostingModel();
    $hostings = $hm->getAll();
    echo "DEBUG_HOSTING_COUNT: " . count($hostings) . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
