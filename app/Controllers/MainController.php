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
use App\Models\CustomerModel;

class MainController extends BaseController {
    private $projectModel;
    private $hostingModel;
    private $passwordModel;
    private $categoryModel;
    private $snippetModel;
    private $codeCategoryModel;
    private $logModel;
    private $customerModel;

    public function __construct() {
        parent::__construct();
        
        $this->projectModel = new ProjectModel();
        $this->hostingModel = new HostingModel();
        $this->passwordModel = new PasswordModel();
        $this->categoryModel = new CategoryModel();
        $this->snippetModel = new SnippetModel();
        $this->codeCategoryModel = new CodeCategoryModel();
        $this->logModel = new LogModel();
        $this->customerModel = new CustomerModel();
    }

    public function dashboard() {
        $currentYear = date('Y');
        $data = [
            'projects' => $this->projectModel->getAll(),
            'hostings' => $this->hostingModel->getAll(),
            'hosting_renewals' => $this->hostingModel->getAllRenewals(),
            'monthlyRevenue' => $this->projectModel->getMonthlyRevenue($currentYear),
            'project_payments' => $this->projectModel->getAllPaidPayments(),
            'recentLogs' => $this->logModel->getAll([], 5, 0),
            'password_categories' => $this->categoryModel->getAll(),
            'snippet_categories' => $this->codeCategoryModel->getAll()
        ];
        $this->view('index', $data);
    }

    public function hostings() {
        $data = [
            'hostings' => $this->hostingModel->getAll(),
            'hosting_renewals' => $this->hostingModel->getAllRenewals()
        ];
        $this->view('hostings', $data);
    }

    public function projects() {
        $data = [
            'projects' => $this->projectModel->getAll(),
            'project_payments' => $this->projectModel->getAllPaidPayments(),
            'hostings' => $this->hostingModel->getAll(),
            'customers' => $this->customerModel->getAll()
        ];
        $this->view('projects', $data);
    }

    public function customers() {
        $data = [
            'customers' => $this->customerModel->getAll(),
        ];
        $this->view('customers', $data);
    }

    public function reports() {
        $data = [
            'projects' => $this->projectModel->getAll(),
            'hostings' => $this->hostingModel->getAll(),
            'hosting_renewals' => $this->hostingModel->getAllRenewals(),
            'project_payments' => $this->projectModel->getAllPaidPayments(),
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
                $oldData = $this->codeCategoryModel->find($id);
                $success = $this->codeCategoryModel->update($id, [
                    'name' => $name,
                    'color' => $input['color'] ?? '#fef9c3',
                    'text_color' => $input['text_color'] ?? '#854d0e'
                ]);
                $success_id = $id;
                if ($success) {
                    $newData = $this->codeCategoryModel->find($id);
                    $this->logModel->addLog('CodeX', 'Cập nhật', 'Danh mục: ' . $name, $_SESSION['user_name'] ?? 'System', json_encode(['old' => $oldData, 'new' => $newData]));
                }
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
                if (!$id) {
                    $this->logModel->addLog('CodeX', 'Tạo mới', 'Danh mục: ' . $catName, $_SESSION['user_name'] ?? 'System');
                }
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

            $projectId = $input['id'] ?? null;
            $milestones = $input['milestones'] ?? [];
            $projectValue = (int)($input['value'] ?? 0);

            // Kiểm tra tổng giá trị các đợt thanh toán không vượt quá giá trị dự án
            $totalMilestones = 0;
            foreach ($milestones as $ms) {
                $totalMilestones += (int)($ms['amount'] ?? 0);
            }

            if ($totalMilestones > $projectValue) {
                echo json_encode([
                    'status' => 'error', 
                    'success' => false, 
                    'message' => 'Tổng giá trị các đợt thanh toán (' . number_format($totalMilestones, 0, ',', '.') . ' VNĐ) không được vượt quá giá trị dự án (' . number_format($projectValue, 0, ',', '.') . ' VNĐ)!'
                ]);
                return;
            }

            if ($projectId) {
                $oldData = $this->projectModel->find($projectId);
                $success = $this->projectModel->update($projectId, $input);
                if ($success) {
                    $this->projectModel->savePayments($projectId, $milestones);
                    $newData = $this->projectModel->find($projectId);
                    $this->logModel->addLog('Project', 'Cập nhật', $input['name'], $_SESSION['user_name'] ?? 'System', json_encode(['old' => $oldData, 'new' => $newData]));
                }
            } else {
                $success = $this->projectModel->create($input);
                if ($success) {
                    $db = \App\Core\Database::getInstance()->getConnection();
                    $projectId = $db->lastInsertId();
                    $this->projectModel->savePayments($projectId, $milestones);
                    $this->logModel->addLog('Project', 'Tạo mới', $input['name'], $_SESSION['user_name'] ?? 'System');
                }
            }

            if ($success) {
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Lưu dự án thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi lưu dự án']);
            }
        } catch (\Exception $e) {
            error_log("Error saving project: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau: ' . $e->getMessage()]);
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
     * API: Lưu Khách hàng (Thêm mới hoặc Cập nhật)
     */
    public function saveCustomer() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['name'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            // Kiểm tra trùng tên khách hàng (case-insensitive)
            $existing = $this->customerModel->findByName($input['name'], $input['id'] ?? null);
            if ($existing) {
                echo json_encode(['status' => 'error', 'message' => 'Tên khách hàng này đã tồn tại trong hệ thống.']);
                return;
            }

            if (isset($input['id']) && $input['id']) {
                $oldData = $this->customerModel->find($input['id']);
                $success = $this->customerModel->update($input['id'], $input);
                if ($success) {
                    $newData = $this->customerModel->find($input['id']);
                    $this->logModel->addLog('Customer', 'Cập nhật', $input['name'], $_SESSION['user_name'] ?? 'System', json_encode(['old' => $oldData, 'new' => $newData]));
                }
            } else {
                $success = $this->customerModel->create($input);
                if ($success) $this->logModel->addLog('Customer', 'Tạo mới', $input['name'], $_SESSION['user_name'] ?? 'System');
            }

            if ($success) {
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Lưu khách hàng thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi lưu khách hàng']);
            }
        } catch (\Exception $e) {
            error_log("Error saving customer: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Xóa Khách hàng
     */
    public function deleteCustomer() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
                return;
            }

            $customer = $this->customerModel->find($input['id']);
            $success = $this->customerModel->delete($input['id']);
            if ($success) {
                $customerName = ($customer && isset($customer['name'])) ? $customer['name'] : 'Customer #' . $input['id'];
                $this->logModel->addLog('Customer', 'Xoá', $customerName, $_SESSION['user_name'] ?? 'System', json_encode($customer));
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Xoá khách hàng thành công']);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi xoá khách hàng']);
            }
        } catch (\Exception $e) {
            error_log("Error deleting customer: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Xóa nhiều Khách hàng cùng lúc
     */
    public function deleteCustomersBulk() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['ids']) || !is_array($input['ids'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            $count = count($input['ids']);
            $success = $this->customerModel->deleteBulk($input['ids']);
            if ($success) {
                $this->logModel->addLog('Customer', 'Xoá nhiều', "Đã xoá $count khách hàng", $_SESSION['user_name'] ?? 'System');
                echo json_encode(['status' => 'success', 'success' => true, 'message' => "Đã xoá $count khách hàng"]);
            } else {
                echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Lỗi khi xoá hàng loạt']);
            }
        } catch (\Exception $e) {
            error_log("Error bulk deleting customers: " . $e->getMessage());
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
                if ($success) {
                    $newData = $this->hostingModel->find($input['id']);
                    $this->logModel->addLog('Hosting', 'Cập nhật', $input['name'], $_SESSION['user_name'] ?? 'System', json_encode(['old' => $oldData, 'new' => $newData]));
                }
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
                if ($success) {
                    $newData = $this->passwordModel->find($input['id']);
                    $this->logModel->addLog('Passwords', 'Cập nhật', $input['title'], $_SESSION['user_name'] ?? 'System', json_encode(['old' => $oldData, 'new' => $newData]));
                }
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
                $oldData = $this->categoryModel->find($input['id']);
                $success = $this->categoryModel->update($input['id'], $input);
                if ($success) {
                    $newData = $this->categoryModel->find($input['id']);
                    $this->logModel->addLog('Passwords', 'Cập nhật', 'Danh mục: ' . $input['name'], $_SESSION['user_name'] ?? 'System', json_encode(['old' => $oldData, 'new' => $newData]));
                }
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
            if (isset($data['old']) && is_array($data['old'])) {
                $data = $data['old'];
            }
            
            $module = $log['module'];
            $success = false;
            $itemName = $log['item_name'];

            // Thực hiện khôi phục theo từng Module
            switch ($module) {
                case 'Project':
                    $mappedData = $data;
                    // Map old snake_case if present
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
                    // Map old snake_case if present
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

    /**
     * API: Gia hạn Hosting
     */
    public function renewHosting() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            $id = $input['id'];
            $hosting = $this->hostingModel->find($id);
            if (!$hosting) {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy hosting']);
                return;
            }

            // 1. Log payment in renewals table
            $renewalData = [
                'amount' => $input['amount'],
                'regDate' => $input['regDate'],
                'expDate' => $input['expDate'],
                'notes' => $input['notes'] ?? 'Gia hạn dịch vụ'
            ];
            $this->hostingModel->addRenewal($id, $renewalData);

            // 2. Update hosting status (New exp date) - Keep original regDate
            $updateData = $hosting;
            $updateData['price'] = $input['amount']; // Update current price if changed
            $updateData['expDate'] = $input['expDate']; 
            if (isset($input['usage'])) $updateData['usage'] = $input['usage'];

            $success = $this->hostingModel->update($id, $updateData);

            if ($success) {
                $this->logModel->addLog('Hosting', 'Gia hạn', $hosting['name'], $_SESSION['user_name'] ?? 'System', json_encode($renewalData));
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Gia hạn thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi cập nhật trạng thái hosting']);
            }
        } catch (\Exception $e) {
            error_log("Error renewing hosting: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    /**
     * API: Lấy lịch sử gia hạn
     */
    public function getHostingRenewals() {
        header('Content-Type: application/json');
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
                return;
            }
            $renewals = $this->hostingModel->getRenewals($id);
            echo json_encode(['status' => 'success', 'success' => true, 'data' => $renewals]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Đánh dấu thông báo đã đọc
     */
    public function markNotifAsRead() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            $notifModel = new \App\Models\NotificationModel();
            $success = $notifModel->markAsRead($input['id']);

            echo json_encode(['status' => 'success', 'success' => $success]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Đánh dấu tất cả thông báo đã đọc
     */
    public function markAllNotifsAsRead() {
        header('Content-Type: application/json');
        try {
            $notifModel = new \App\Models\NotificationModel();
            $success = $notifModel->markAllAsRead();
            echo json_encode(['status' => 'success', 'success' => $success !== false]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Xóa thông báo (Hỗ trợ xóa nhiều)
     */
    public function deleteNotifications() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['ids']) || !is_array($input['ids'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            $notifModel = new \App\Models\NotificationModel();
            $success = $notifModel->deleteBulk($input['ids']);

            echo json_encode(['status' => 'success', 'success' => $success]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Lấy danh sách đợt thanh toán của dự án
     */
    public function getProjectPayments() {
        header('Content-Type: application/json');
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
                return;
            }
            $payments = $this->projectModel->getPayments($id);
            $stats = $this->projectModel->getPaymentStats($id);

            echo json_encode([
                'status' => 'success', 
                'success' => true, 
                'data' => [
                    'payments' => $payments,
                    'stats' => $stats
                ]
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Lưu danh sách đợt thanh toán
     */
    public function saveProjectPayments() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['projectId'])) {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
                return;
            }

            $projectId = $input['projectId'];
            $payments = $input['payments'] ?? [];

            $success = $this->projectModel->savePayments($projectId, $payments);

            if ($success) {
                $project = $this->projectModel->find($projectId);
                $this->logModel->addLog('Project', 'Cập nhật thanh toán', $project['name'], $_SESSION['user_name'] ?? 'System');
                echo json_encode(['status' => 'success', 'success' => true, 'message' => 'Lưu thông tin thanh toán thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu thông tin thanh toán']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Xác nhận thanh toán một đợt
     */
    public function confirmProjectPayment() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input || !isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
                return;
            }

            $result = $this->projectModel->confirmPayment($input['id']);

            if ($result && isset($result['success']) && $result['success']) {
                $message = 'Xác nhận thanh toán thành công';
                
                if ($result['projectCompleted']) {
                    $project = $this->projectModel->find($result['projectId']);
                    $projectName = $project['name'] ?? 'Project #' . $result['projectId'];
                    $this->logModel->addLog('Project', 'Hoàn thành', $projectName, $_SESSION['user_name'] ?? 'System', 'Dự án tự động hoàn thành do các phí đã được thanh toán đầy đủ.');
                    $message .= '. Dự án đã được chuyển sang trạng thái Hoàn thành';
                }

                echo json_encode([
                    'status' => 'success', 
                    'success' => true, 
                    'message' => $message,
                    'projectCompleted' => $result['projectCompleted']
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi xác nhận thanh toán']);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
