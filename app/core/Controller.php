<?php
// Creator: ghost1473

class Controller {
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }
    public function view($view, $data = []) {
        require_once '../app/views/' . $view . '.php';
    }

    public function error403() {
        http_response_code(403);
        require_once '../app/views/errors/403.php';
        exit;
    }
}
