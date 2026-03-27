<?php
use App\Models\ProjectModel;
use App\Models\HostingModel;

class MainController extends BaseController {
    private $projectModel;
    private $hostingModel;

    public function __construct() {
        $this->projectModel = new ProjectModel();
        $this->hostingModel = new HostingModel();
    }

    public function dashboard() {
        $data = [
            'projects' => $this->projectModel->getAll(),
            'hostings' => $this->hostingModel->getAll()
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
        $this->view('passwords');
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
}
