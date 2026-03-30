<?php
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
            'monthlyRevenue' => $this->projectModel->getMonthlyRevenue($currentYear)
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_POST['id'] ?? null,
                'title' => $_POST['title'] ?? '',
                'language' => $_POST['language'] ?? 'Khác',
                'description' => $_POST['description'] ?? '',
                'code' => $_POST['code'] ?? '',
                'line_count' => (int)($_POST['line_count'] ?? 0),
                'char_count' => (int)($_POST['char_count'] ?? 0)
            ];

            if ($this->snippetModel->save($data)) {
                $action = $data['id'] ? 'Cập nhật' : 'Tạo mới';
                $this->logModel->addLog('CodeX', $action, $data['title']);
                echo json_encode(['status' => 'success', 'message' => 'Lưu snippet thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu snippet']);
            }
        }
    }

    public function deleteSnippet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $snippet = $this->snippetModel->find($_POST['id']);
            if ($this->snippetModel->delete($_POST['id'])) {
                $snippetTitle = ($snippet && isset($snippet['title'])) ? $snippet['title'] : 'Snippet #' . $_POST['id'];
                $this->logModel->addLog('CodeX', 'Xoá', $snippetTitle);
                echo json_encode(['status' => 'success', 'message' => 'Xoá snippet thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi xoá snippet']);
            }
        }
    }

    /**
     * API: Lưu Danh mục Code (Thêm mới)
     */
    public function saveCodeCategory() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        $id = $input['id'] ?? ($_POST['id'] ?? null);
        $name = $input['name'] ?? ($_POST['name'] ?? null);

        if (!$name && !$id) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
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
            // Kiểm tra xem đã tồn tại chưa (cho trường hợp thêm nhanh từ dropdown)
            $existing = $this->codeCategoryModel->findByName($name);
            if ($existing) {
                echo json_encode(['success' => true, 'id' => $existing['id'], 'name' => $existing['name'], 'exists' => true]);
                return;
            }

            // Lấy màu từ input, nếu không có thì dùng mặc định
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
            $this->logModel->addLog('CodeX', $action, 'Danh mục: ' . $catName);
            echo json_encode(['success' => true, 'id' => $success_id, 'name' => $catName, 'color' => $cat ? $cat['color'] : '#fef9c3', 'text_color' => $cat ? $cat['text_color'] : '#854d0e']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu danh mục']);
        }
    }

    /**
     * API: Xóa Danh mục Code (CodeX)
     */
    public function deleteCodeCategory() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            return;
        }

        $cat = $this->codeCategoryModel->find($input['id']);
        $success = $this->codeCategoryModel->delete($input['id']);
        if ($success) {
            $catName = ($cat && isset($cat['name'])) ? $cat['name'] : 'ID #' . $input['id'];
            $this->logModel->addLog('CodeX', 'Xoá', 'Danh mục: ' . $catName);
        }
        echo json_encode(['success' => $success]);
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
        $this->view('settings');
    }

    /**
     * API: Lưu Project (Thêm mới hoặc Cập nhật)
     */
    public function saveProject() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['name'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        if (isset($input['id']) && $input['id']) {
            $success = $this->projectModel->update($input['id'], $input);
            $this->logModel->addLog('Project', 'Cập nhật', $input['name']);
        } else {
            $success = $this->projectModel->create($input);
            $this->logModel->addLog('Project', 'Tạo mới', $input['name']);
        }

        echo json_encode(['success' => $success]);
    }

    /**
     * API: Xóa Project
     */
    public function deleteProject() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            return;
        }

        $project = $this->projectModel->find($input['id']);
        $success = $this->projectModel->delete($input['id']);
        if ($success) {
            $projectName = ($project && isset($project['name'])) ? $project['name'] : 'Project #' . $input['id'];
            $this->logModel->addLog('Project', 'Xoá', $projectName);
        }
        echo json_encode(['success' => $success]);
    }

    /**
     * API: Lưu Hosting (Thêm mới hoặc Cập nhật)
     */
    public function saveHosting() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['name'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        if (isset($input['id']) && $input['id']) {
            $success = $this->hostingModel->update($input['id'], $input);
            $this->logModel->addLog('Hosting', 'Cập nhật', $input['name']);
        } else {
            $success = $this->hostingModel->create($input);
            $this->logModel->addLog('Hosting', 'Tạo mới', $input['name']);
        }

        echo json_encode(['success' => $success]);
    }

    /**
     * API: Xóa Hosting
     */
    public function deleteHosting() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            return;
        }

        $hosting = $this->hostingModel->find($input['id']);
        $success = $this->hostingModel->delete($input['id']);
        if ($success) {
            $hostingName = ($hosting && isset($hosting['name'])) ? $hosting['name'] : 'Hosting #' . $input['id'];
            $this->logModel->addLog('Hosting', 'Xoá', $hostingName);
        }
        echo json_encode(['success' => $success]);
    }

    /**
     * API: Xóa nhiều Project cùng lúc
     */
    public function deleteProjectsBulk() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['ids']) || !is_array($input['ids'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        $success = $this->projectModel->deleteBulk($input['ids']);
        echo json_encode(['success' => $success]);
    }

    /**
     * API: Xóa nhiều Hosting cùng lúc
     */
    public function deleteHostingsBulk() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['ids']) || !is_array($input['ids'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        $success = $this->hostingModel->deleteBulk($input['ids']);
        echo json_encode(['success' => $success]);
    }

    /**
     * API: Lưu Mật khẩu (Thêm mới hoặc Cập nhật)
     */
    public function savePassword() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['title'])) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        if (isset($input['id']) && $input['id']) {
            $success = $this->passwordModel->update($input['id'], $input);
            $this->logModel->addLog('Passwords', 'Cập nhật', $input['title']);
        } else {
            $success = $this->passwordModel->create($input);
            $this->logModel->addLog('Passwords', 'Tạo mới', $input['title']);
        }

        echo json_encode(['success' => $success]);
    }

    /**
     * API: Xóa Mật khẩu
     */
    public function deletePassword() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            return;
        }

        $password = $this->passwordModel->find($input['id']);
        $success = $this->passwordModel->delete($input['id']);
        if ($success) {
            $passwordTitle = ($password && isset($password['title'])) ? $password['title'] : 'Mật khẩu #' . $input['id'];
            $this->logModel->addLog('Passwords', 'Xoá', $passwordTitle);
        }
        echo json_encode(['success' => $success]);
    }

    /**
     * API: Lưu Danh mục Mật khẩu (Thêm mới hoặc Cập nhật)
     */
    public function saveCategory() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['name'])) {
            echo json_encode(['success' => false, 'message' => 'Tên danh mục không hợp lệ']);
            return;
        }

        if (isset($input['id']) && $input['id']) {
            $success = $this->categoryModel->update($input['id'], $input);
            $this->logModel->addLog('Passwords', 'Cập nhật', 'Danh mục: ' . $input['name']);
        } else {
            $success = $this->categoryModel->create($input);
            $this->logModel->addLog('Passwords', 'Tạo mới', 'Danh mục: ' . $input['name']);
        }

        echo json_encode(['success' => $success]);
    }

    /**
     * API: Xóa Danh mục Mật khẩu
     */
    public function deleteCategory() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            return;
        }

        $cat = $this->categoryModel->find($input['id']);
        $success = $this->categoryModel->delete($input['id']);
        if ($success) {
            $catName = ($cat && isset($cat['name'])) ? $cat['name'] : 'ID #' . $input['id'];
            $this->logModel->addLog('Passwords', 'Xoá', 'Danh mục: ' . $catName);
        }
        echo json_encode(['success' => $success]);
    }
}
