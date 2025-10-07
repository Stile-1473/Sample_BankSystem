<?php
// Creator: ghost1473

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        $url = $this->parseUrl();
        $controllerFile = '../app/controllers/' . ucfirst($url[0]) . 'Controller.php';
        if(isset($url[0]) && file_exists($controllerFile)) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        } else if ($url[0] !== 'home') {
            require_once '../app/views/errors/404.php';
            exit;
        }
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        if(isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        } else if (isset($url[1])) {
            require_once '../app/views/errors/404.php';
            exit;
        }
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function handleException($exception) {
        http_response_code(500);
        require_once '../app/views/errors/500.php';
        exit;
    }

    public function handleError($errno, $errstr, $errfile, $errline) {
        if ($errno === E_USER_ERROR || $errno === E_ERROR) {
            http_response_code(500);
            require_once '../app/views/errors/500.php';
            exit;
        }
        return false;
    }
    public function parseUrl() {
        if(isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['home'];
    }
}
