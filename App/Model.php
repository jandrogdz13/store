<?php

class Model{

    public $module;
    private $registry;
    private $modelName;
    private $modelRegistry;
    protected $db;
    protected $schema = [];
    protected $blocks;
    protected $fields;
	protected $join = false;
	protected $where = false;


    public function __construct() {
        $this->registry = Registry::getInstance();
        $this->db = $this->registry->get('db');
        $this->modelName = "model_{$this->module}";
        $this->modelRegistry = $this->registry->get($this->modelName);
    }

    public function setModule($module){
        $this->module = $module;
    }

    public function getModule(){
        return $this->module;
    }

    // schema module
    public function getSchema(){
        $sql = "SHOW TABLES";
        $this->db->prepare($sql);
        $fetchAll = $this->db->fetchAll();
        $tables = $fetchAll? array_column($fetchAll, 'Tables_in_' . DB_NAME): [];

        if(in_array($this->module, $tables)):
            $sql = "
                SELECT
                    COLUMN_NAME,
                    ORDINAL_POSITION,
                    COLUMN_DEFAULT,
                    IS_NULLABLE,
                    DATA_TYPE,
                    CHARACTER_MAXIMUM_LENGTH,
                    NUMERIC_PRECISION,
                    NUMERIC_SCALE,
                    COLUMN_KEY,
                    EXTRA
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = 'user' AND TABLE_SCHEMA = 'mobelinn'
            ";
            $this->db->prepare($sql);
            $this->schema = $this->db->fetchAll();
            changeArrayKeys($this->schema, 'Field');
        endif;
        return $this->schema;
    }

}

