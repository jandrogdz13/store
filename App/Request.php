<?php

class Request{

    private $controller;
    private $method;
    private $args;

    // Request
    public $request;
    public $post;
    public $get;
    public $files;
    public $server;
    public $data =[];

    public function __construct(){

        // Request
        $this->request = $_REQUEST;
        $this->post = $_POST;
        $this->get = $_GET;
        $this->files = $_FILES;
        $this->server = $_SERVER;

        if(isset($_GET['url'])):
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $url = array_filter($url);// elimina todos los valores que no sean validos.

			// Class name
			$controller = strtolower(array_shift($url));
			$this->controller = $controller;
			$this->set('module', $controller);
            $this->set('controller', $controller);
            //$this->controller = strtolower(array_shift($url));

			// Method name
			$method = strtolower(array_shift($url));
			$this->method = $method;
			$this->set('method', $this->method);
            //$this->method = strtolower(array_shift($url));

			// Args
            $this->args = $url;
            $this->set('args', $url);

        endif;

        if(!$this->controller):
			$this->controller = DEFAULT_CONTROLLER;
		endif;

        if(!$this->method) $this->method = "index";

        if(!$this->args) $this->args = [];

    }

    public function set($key, $value){
        $this->data[$key] = $value;
    }

    public function get($key){
        return isset($this->data[$key])? $this->data[$key]: false;
    }

    public function has($key){
    	return isset($this->data[$key]);
	}

	public function empty(){
		return $this->data = [];
	}

    public function getController(){
        return str_replace(".php", "", $this->controller);
    }

    public function getMethod(){
        return $this->method;
    }

    public function getArgs(){
        return $this->args;
    }

}
?>
