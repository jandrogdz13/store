<?php

class Registry{
    private static $instance;
    private $data;

    private function __construct(){}

    public static function getInstance(){
        if(!self::$instance instanceof self) self::$instance = new Registry();
        return self::$instance;
    }

    public function set($key, $value){
        $this->data[$key] = $value;
    }

    public function get($key){
        return isset($this->data[$key])? $this->data[$key]: [];
    }
}
