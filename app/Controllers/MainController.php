<?php
use App\Models\ProjectModel;
use App\Models\HostingModel;
use App\Models\PasswordModel;
use App\Models\CategoryModel;

class MainController extends BaseController {
    private $projectModel;
    private $hostingModel;
    private $passwordModel;
    private $categoryModel;

    public function __construct() {
        $this->projectModel = new ProjectModel();
        $this->hostingModel = new HostingModel();
        $this->passwordModel = new PasswordModel();
        $this->categoryModel = new CategoryModel();
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
        $data = [
            'hostings' => $this->hostingModel->getAll()
        ];
        $this->view('hostings', $data);
    }

    public function projects() {
        $data = [
            'projects' => $this->projectModel->getAll()
        ];
        $this->view('projects', $data);
    }

    public function reports() {
        $data = [
            'projects' => $this->projectModel->getAll(),
            'hostings' => $this->hostingModel->getAll()
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
        $this->view('codex');
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
