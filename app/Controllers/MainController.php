<?php
use App\Models\ProjectModel;
use App\Models\HostingModel;
use App\Models\PasswordModel;
use App\Models\CategoryModel;
use App\Models\SnippetModel;
use App\Models\CodeCategoryModel;

class MainController extends BaseController {
    private $projectModel;
    private $hostingModel;
    private $passwordModel;
    private $categoryModel;
    private $snippetModel;
    private $codeCategoryModel;

    public function __construct() {
        $this->projectModel = new ProjectModel();
        $this->hostingModel = new HostingModel();
        $this->passwordModel = new PasswordModel();
        $this->categoryModel = new CategoryModel();
        $this->snippetModel = new SnippetModel();
        $this->codeCategoryModel = new CodeCategoryModel();
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
        $data = ['passwords' => $this->passwordModel->getAll()];
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
                echo json_encode(['status' => 'success', 'message' => 'Lưu snippet thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu snippet']);
            }
        }
    }

    public function deleteSnippet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            if ($this->snippetModel->delete($_POST['id'])) {
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
        
        // Handle both standard POST and JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $name = $input['name'] ?? ($_POST['name'] ?? null);

        if (!$name) {
            echo json_encode(['success' => false, 'message' => 'Tên danh mục không hợp lệ']);
            return;
        }

        $existing = $this->codeCategoryModel->findByName($name);
        if ($existing) {
            echo json_encode(['success' => true, 'id' => $existing['id'], 'name' => $existing['name'], 'exists' => true]);
            return;
        }

        $id = $this->codeCategoryModel->create($name);
        if ($id) {
            echo json_encode(['success' => true, 'id' => $id, 'name' => $name]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi tạo danh mục']);
        }
    }

    public function logs() {
        $this->view('logs');
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
        } else {
            $success = $this->projectModel->create($input);
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

        $success = $this->projectModel->delete($input['id']);
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
        } else {
            $success = $this->hostingModel->create($input);
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

        $success = $this->hostingModel->delete($input['id']);
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
        } else {
            $success = $this->passwordModel->create($input);
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

        $success = $this->passwordModel->delete($input['id']);
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
        } else {
            $success = $this->categoryModel->create($input);
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

        $success = $this->categoryModel->delete($input['id']);
        echo json_encode(['success' => $success]);
    }
}
