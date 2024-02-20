<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

abstract class Controller{

    protected $registry;
    protected $viewer;
    protected $request;
    protected $db;
    protected $module;
    protected $model;
    protected $pk;
    protected $mailer;

    public function __construct() {
        $this->registry = Registry::getInstance();
        $this->request = $this->registry->get('request');
		$this->db = $this->registry->get('db');
        $this->viewer = new View($this->request);
    }

    abstract public function index();

    protected function mainController(){
		require_once ROOT . "Controllers" . DS . "main.php";
		return new mainController();
	}
	protected function getCss(){
		$cssFiles = [

		];
	}

	protected function getScripts(){
		$scripts = [];
		return $scripts;
	}

}
?>
