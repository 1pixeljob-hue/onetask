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
}
