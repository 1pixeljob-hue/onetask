<?php
class MainController extends BaseController {
    public function dashboard() {
        $this->view('index');
    }
    public function hostings() {
        $this->view('hostings');
    }
    public function projects() {
        $this->view('projects');
    }
    public function reports() {
        $this->view('reports');
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
