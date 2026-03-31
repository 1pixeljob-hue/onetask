<?php
namespace App\Controllers;

use DateTime;
use Exception;
use App\Controllers\AuthController;
use App\Core\BaseController;
use App\Models\ProjectModel;
use App\Models\HostingModel;
use App\Models\PasswordModel;
use App\Models\CategoryModel;
use App\Models\SnippetModel;
use App\Models\CodeCategoryModel;
use App\Models\LogModel;

class MainController extends BaseController {
    private $projectModel;
    private $hostingModel;
    private $passwordModel;
    private $categoryModel;
    private $snippetModel;
    private $codeCategoryModel;
    private $logModel;

    public function __construct() {
        parent::__construct();
        
        $this->projectModel = new ProjectModel();
        $this->hostingModel = new HostingModel();
        $this->passwordModel = new PasswordModel();
        $this->categoryModel = new CategoryModel();
        $this->snippetModel = new SnippetModel();
        $this->codeCategoryModel = new CodeCategoryModel();
        $this->logModel = new LogModel();
    }

    public function dashboard() {
        $currentYear = date('Y');
        $data = [
            'projects' => $this->projectModel->getAll(),
            'hostings' => $this->hostingModel->getAll(),
            'monthlyRevenue' => $this->projectModel->getMonthlyRevenue($currentYear),
            'recentLogs' => $this->logModel->getAll([], 5, 0),
            'password_categories' => $this->categoryModel->getAll(),
            'snippet_categories' => $this->codeCategoryModel->getAll()
        ];
        $this->view('index', $data);
    }

    public function hostings() {
        $data = ['hostings' => $this->hostingModel->getAll()];
        $this->view('hostings', $data);
    }

    public function projects() {
        $data = ['projects' => $this->projectModel->getAll()];
        $this->view('projects', $data);
    }

    public function reports() {
        $data = [
            'projects' => $this->projectModel->getAll(),
            'hostings' => $this->hostingModel->getAll(),
        ];
        $this->view('reports', $data);
    }

    public function passwords() {
        $data = [
            'passwords' => $this->passwordModel->getAll(),
            'categories' => $this->categoryModel->getAll()
        ];
        $this->view('passwords', $data);
    }

    public function codex() {
        $data = [
            'snippets' => $this->snippetModel->getAll(),
            'categories' => $this->codeCategoryModel->getAll()
        ];
        $this->view('codex', $data);
    }

    public function saveSnippet() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'id' => $_POST['id'] ?? null,
                    'title' => $_POST['title'] ?? '',
                    'language' => $_POST['language'] ?? 'Khác',
                    'description' => $_POST['description'] ?? '',
                    'code' => $_POST['code'] ?? '',
                    'line_count' => (int)($_POST['line_count'] ?? 0),
                    'char_count' => (int)($_POST['char_count'] ?? 0)
                ];

                $oldData = null;
                if ($data['id']) {
                    $oldData = $this->snippetModel->find($data['id']);
                }

                if ($this->snippetModel->save($data)) {
                    $action = $data['id'] ? 'Cập nhật' : 'Tạo mới';
                    $this->logModel->addLog('CodeX', $action, $data['title'], $_SESSION['user_name'] ?? 'System', $oldData ? json_encode($oldData) : null);
                    echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Lưu snippet thành công']);
                } else {
                    echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi lưu snippet']);
                }
            } catch (\Exception $e) {
                error_log("Error saving snippet: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
            }
        }
    }

    public function deleteSnippet() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            try {
                $snippet = $this->snippetModel->find($_POST['id']);
                if ($this->snippetModel->delete($_POST['id'])) {
                    $snippetTitle = ($snippet && isset($snippet['title'])) ? $snippet['title'] : 'Snippet #' . $_POST['id'];
                    $this->logModel->addLog('CodeX', 'Xoá', $snippetTitle, $_SESSION['user_name'] ?? 'System', json_encode($snippet));
                    echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Xoá snippet thành công']);
                } else {
                    echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi xoá snippet']);
                }
            } catch (\Exception $e) {
                error_log("Error deleting snippet: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
            }
        }
    }

    /**
     * API: Lưu Danh mục Code (Thêm mới)
     */
    public function saveCodeCategory() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $id = $input['id'] ?? ($_POST['id'] ?? null);
            $name = $input['name'] ?? ($_POST['name'] ?? null);

            if (!$name && !$id) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            if ($id) {
                $success = $this->codeCategoryModel->update($id, [
                    'name' => $name,
                    'color' => $input['color'] ?? '#fef9c3',
                    'text_color' => $input['text_color'] ?? '#854d0e'
                ]);
                $success_id = $id;
            } else {
                $existing = $this->codeCategoryModel->findByName($name);
                if ($existing) {
                    echo json_encode(['status' => 'success', 'success' => true, 'data' => ['id' => $existing['id'], 'name' => $existing['name'], 'exists' => true]]);
                    return;
                }

                $color = $input['color'] ?? ($input['hex'] ?? '#fef9c3');
                $text_color = $input['text_color'] ?? '#854d0e';

                $success_id = $this->codeCategoryModel->create([
                    'name' => $name,
                    'color' => $color,
                    'text_color' => $text_color
                ]);
                $success = $success_id !== false;
            }

            if ($success) {
                $cat = $this->codeCategoryModel->find($success_id);
                $catName = ($cat && isset($cat['name'])) ? $cat['name'] : 'ID #' . $success_id;
                $action = $id ? 'Cập nhật' : 'Tạo mới';
                $this->logModel->addLog('CodeX', $action, 'Danh mục: ' . $catName, $_SESSION['user_name'] ?? 'System');
                echo json_encode([
                    'status' => 'success', 
                    'success' => true,
                    'data' => [
                        'id' => $success_id, 
                        'name' => $catName, 
                        'color' => $cat ? $cat['color'] : '#fef9c3', 
                        'text_color' => $cat ? $cat['text_color'] : '#854d0e'
                    ]
                ]);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi lưu danh mục']);
            }
        } catch (\Exception $e) {
            error_log("Error saving code category: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Xóa Danh mục Code (CodeX)
     */
    public function deleteCodeCategory() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
                return;
            }

            $cat = $this->codeCategoryModel->find($input['id']);
            $success = $this->codeCategoryModel->delete($input['id']);
            if ($success) {
                $catName = ($cat && isset($cat['name'])) ? $cat['name'] : 'ID #' . $input['id'];
                $this->logModel->addLog('CodeX', 'Xoá', 'Danh mục: ' . $catName, $_SESSION['user_name'] ?? 'System', json_encode($cat));
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Xoá danh mục thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi xoá danh mục']);
            }
        } catch (\Exception $e) {
            error_log("Error deleting code category: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    public function logs() {
        $filters = [
            'module' => $_GET['module'] ?? '',
            'action' => $_GET['action'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        $limit = 15;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $logs = $this->logModel->getAll($filters, $limit, $offset);
        $totalLogs = $this->logModel->getCount($filters);
        
        $data = [
            'logs' => $logs,
            'totalLogs' => $totalLogs,
            'currentPage' => $page,
            'totalPages' => ceil($totalLogs / $limit),
            'limit' => $limit,
            'offset' => $offset,
            'filters' => $filters
        ];
        $this->view('logs', $data);
    }

    public function deleteLog() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            return;
        }
        $success = $this->logModel->delete($input['id']);
        echo json_encode(['success' => $success]);
    }

    public function settings() {
        $hostings = $this->hostingModel->getAll();
        $projects = $this->projectModel->getAll();
        
        $active_count = 0;
        foreach ($projects as $pj) {
            if (isset($pj['status']) && $pj['status'] !== 'done') $active_count++;
        }
        
        $expiring_count = 0;
        $today = new DateTime();
        foreach ($hostings as $h) {
            if (!empty($h['exp_date'])) {
                $exp = new DateTime($h['exp_date']);
                $diff = $today->diff($exp);
                // Nếu chưa hết hạn (invert == 0) và còn <= 30 ngày
                if ($diff->invert == 0 && $diff->days <= 30) {
                    $expiring_count++;
                }
            }
        }

        $data = [
            'stats' => [
                'total_hostings' => count($hostings),
                'total_projects' => count($projects),
                'active_items' => $active_count,
                'expiring_soon' => $expiring_count
            ]
        ];
        $this->view('settings', $data);
    }

    /**
     * API: Lưu Project (Thêm mới hoặc Cập nhật)
     */
    public function saveProject() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['name'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            if (isset($input['id']) && $input['id']) {
                $oldData = $this->projectModel->find($input['id']);
                $success = $this->projectModel->update($input['id'], $input);
                if ($success) $this->logModel->addLog('Project', 'Cập nhật', $input['name'], $_SESSION['user_name'] ?? 'System', json_encode($oldData));
            } else {
                $success = $this->projectModel->create($input);
                if ($success) $this->logModel->addLog('Project', 'Tạo mới', $input['name'], $_SESSION['user_name'] ?? 'System');
            }

            if ($success) {
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Lưu dự án thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi lưu dự án']);
            }
        } catch (\Exception $e) {
            error_log("Error saving project: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Xóa Project
     */
    public function deleteProject() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
                return;
            }

            $project = $this->projectModel->find($input['id']);
            $success = $this->projectModel->delete($input['id']);
            if ($success) {
                $projectName = ($project && isset($project['name'])) ? $project['name'] : 'Project #' . $input['id'];
                $this->logModel->addLog('Project', 'Xoá', $projectName, $_SESSION['user_name'] ?? 'System', json_encode($project));
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Xoá dự án thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi xoá dự án']);
            }
        } catch (\Exception $e) {
            error_log("Error deleting project: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Lưu Hosting (Thêm mới hoặc Cập nhật)
     */
    public function saveHosting() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['name'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            if (isset($input['id']) && $input['id']) {
                $oldData = $this->hostingModel->find($input['id']);
                $success = $this->hostingModel->update($input['id'], $input);
                if ($success) $this->logModel->addLog('Hosting', 'Cập nhật', $input['name'], $_SESSION['user_name'] ?? 'System', json_encode($oldData));
            } else {
                $success = $this->hostingModel->create($input);
                if ($success) $this->logModel->addLog('Hosting', 'Tạo mới', $input['name'], $_SESSION['user_name'] ?? 'System');
            }

            if ($success) {
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Lưu hosting thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi lưu hosting']);
            }
        } catch (\Exception $e) {
            error_log("Error saving hosting: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Xóa Hosting
     */
    public function deleteHosting() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
                return;
            }

            $hosting = $this->hostingModel->find($input['id']);
            $success = $this->hostingModel->delete($input['id']);
            if ($success) {
                $hostingName = ($hosting && isset($hosting['name'])) ? $hosting['name'] : 'Hosting #' . $input['id'];
                $this->logModel->addLog('Hosting', 'Xoá', $hostingName, $_SESSION['user_name'] ?? 'System', json_encode($hosting));
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Xoá hosting thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi xoá hosting']);
            }
        } catch (\Exception $e) {
            error_log("Error deleting hosting: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Xóa nhiều Project cùng lúc
     */
    public function deleteProjectsBulk() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['ids']) || !is_array($input['ids'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            $count = count($input['ids']);
            $success = $this->projectModel->deleteBulk($input['ids']);
            if ($success) {
                $this->logModel->addLog('Project', 'Xoá nhiều', "Đã xoá $count dự án", $_SESSION['user_name'] ?? 'System');
                echo json_encode(['status' => 'success', 'success' => true, 'message' => "Đã xoá $count dự án"]);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi xoá hàng loạt']);
            }
        } catch (\Exception $e) {
            error_log("Error bulk deleting projects: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Xóa nhiều Hosting cùng lúc
     */
    public function deleteHostingsBulk() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['ids']) || !is_array($input['ids'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            $count = count($input['ids']);
            $success = $this->hostingModel->deleteBulk($input['ids']);
            if ($success) {
                $this->logModel->addLog('Hosting', 'Xoá nhiều', "Đã xoá $count hosting", $_SESSION['user_name'] ?? 'System');
                echo json_encode(['status' => 'success', 'success' => true, 'message' => "Đã xoá $count hosting"]);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi xoá hàng loạt']);
            }
        } catch (\Exception $e) {
            error_log("Error bulk deleting hostings: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Lưu Mật khẩu (Thêm mới hoặc Cập nhật)
     */
    public function savePassword() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['title'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            if (isset($input['id']) && $input['id']) {
                $oldData = $this->passwordModel->find($input['id']);
                $success = $this->passwordModel->update($input['id'], $input);
                if ($success) $this->logModel->addLog('Passwords', 'Cập nhật', $input['title'], $_SESSION['user_name'] ?? 'System', json_encode($oldData));
            } else {
                $success = $this->passwordModel->create($input);
                if ($success) $this->logModel->addLog('Passwords', 'Tạo mới', $input['title'], $_SESSION['user_name'] ?? 'System');
            }

            if ($success) {
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Lưu mật khẩu thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi lưu mật khẩu']);
            }
        } catch (\Exception $e) {
            error_log("Error saving password: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Xóa Mật khẩu
     */
    public function deletePassword() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
                return;
            }

            $password = $this->passwordModel->find($input['id']);
            $success = $this->passwordModel->delete($input['id']);
            if ($success) {
                $passwordTitle = ($password && isset($password['title'])) ? $password['title'] : 'Mật khẩu #' . $input['id'];
                $this->logModel->addLog('Passwords', 'Xoá', $passwordTitle, $_SESSION['user_name'] ?? 'System', json_encode($password));
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Xoá mật khẩu thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi xoá mật khẩu']);
            }
        } catch (\Exception $e) {
            error_log("Error deleting password: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Lưu Danh mục Mật khẩu (Thêm mới hoặc Cập nhật)
     */
    public function saveCategory() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['name'])) {
                echo json_encode(['status' => 'error', 'message' => 'Tên danh mục không hợp lệ']);
                return;
            }

            if (isset($input['id']) && $input['id']) {
                $success = $this->categoryModel->update($input['id'], $input);
                if ($success) $this->logModel->addLog('Passwords', 'Cập nhật', 'Danh mục: ' . $input['name'], $_SESSION['user_name'] ?? 'System');
            } else {
                $success = $this->categoryModel->create($input);
                if ($success) $this->logModel->addLog('Passwords', 'Tạo mới', 'Danh mục: ' . $input['name'], $_SESSION['user_name'] ?? 'System');
            }

            if ($success) {
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Lưu danh mục thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi lưu danh mục']);
            }
        } catch (\Exception $e) {
            error_log("Error saving category: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Xóa Danh mục Mật khẩu
     */
    public function deleteCategory() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
                return;
            }

            $cat = $this->categoryModel->find($input['id']);
            $success = $this->categoryModel->delete($input['id']);
            if ($success) {
                $catName = ($cat && isset($cat['name'])) ? $cat['name'] : 'ID #' . $input['id'];
                $this->logModel->addLog('Passwords', 'Xoá', 'Danh mục: ' . $catName, $_SESSION['user_name'] ?? 'System', json_encode($cat));
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Xoá danh mục thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi xoá danh mục']);
            }
        } catch (\Exception $e) {
            error_log("Error deleting category: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Khôi phục dữ liệu từ Log (Restore)
     */
    public function restoreLog() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'ID log không hợp lệ']);
                return;
            }

            $log = $this->logModel->find($input['id']);
            if (!$log || empty($log['data'])) {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy dữ liệu để khôi phục']);
                return;
            }

            $data = json_decode($log['data'], true);
            $module = $log['module'];
            $success = false;
            $itemName = $log['item_name'];

            // Thực hiện khôi phục theo từng Module
            switch ($module) {
                case 'Project':
                    $mappedData = $data;
                    if (isset($data['description'])) $mappedData['desc'] = $data['description'];
                    if (isset($data['admin_url'])) $mappedData['adminUrl'] = $data['admin_url'];
                    if (isset($data['admin_user'])) $mappedData['adminUser'] = $data['admin_user'];
                    if (isset($data['admin_pass'])) $mappedData['adminPass'] = $data['admin_pass'];
                    
                    if (isset($data['id']) && $this->projectModel->find($data['id'])) {
                        $success = $this->projectModel->update($data['id'], $mappedData);
                    } else {
                        $success = $this->projectModel->create($mappedData);
                    }
                    break;

                case 'Hosting':
                    $mappedData = $data;
                    if (isset($data['reg_date'])) $mappedData['regDate'] = $data['reg_date'];
                    if (isset($data['exp_date'])) $mappedData['expDate'] = $data['exp_date'];
                    if (isset($data['usage_period'])) $mappedData['usage'] = $data['usage_period'];
                    
                    if (isset($data['id']) && $this->hostingModel->find($data['id'])) {
                        $success = $this->hostingModel->update($data['id'], $mappedData);
                    } else {
                        $success = $this->hostingModel->create($mappedData);
                    }
                    break;

                case 'Passwords':
                    if (strpos($itemName, 'Danh mục:') === 0) {
                        $success = $this->categoryModel->create($data);
                    } else {
                        if (isset($data['id']) && $this->passwordModel->find($data['id'])) {
                            $success = $this->passwordModel->update($data['id'], $data);
                        } else {
                            $success = $this->passwordModel->create($data);
                        }
                    }
                    break;

                case 'CodeX':
                    if (strpos($itemName, 'Danh mục:') === 0) {
                        $success = $this->codeCategoryModel->create($data);
                    } else {
                        $success = $this->snippetModel->save($data);
                    }
                    break;

                default:
                    echo json_encode(['status' => 'error', 'message' => 'Module không được hỗ trợ khôi phục']);
                    return;
            }

            if ($success) {
                $this->logModel->addLog($module, 'Khôi phục', $itemName, $_SESSION['user_name'] ?? 'System');
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Khôi phục dữ liệu thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi ghi đè dữ liệu vào Database']);
            }
        } catch (\Exception $e) {
            error_log("Error restoring from log: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận: ' . $e->getMessage()]);
        }
    }
}
