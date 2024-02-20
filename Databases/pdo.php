<?php

class dbPDO {
    public $conn;
    protected $stmt;

	public static $instance;
    public function __construct(){
        // Set DSN
        $dsn = 'mysql:host=' . HOST . ';dbname=' . DB_NAME;
        $options = array (
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
        );

        // Create a new PDO instanace
        try{
            $this->conn = new PDO ($dsn, USER, PASS, $options);
        }		// Catch any errors
        catch ( PDOException $e ){
            $this->error = $e->getMessage();
        }
    }

	public static function get_instance(): self{
		if(isset(self::$instance))
			return self::$instance;

		self::$instance = new self();
		return self::$instance;
	}

	/* Transactions */
	public function beginTransaction(){
		$this->conn->beginTransaction();
	}

	public function commit(){
		$this->conn->commit();
	}

	public function rollBack(){
		$this->conn->rollBack();
	}

    public function prepare($sql, $params = []){
        $this->stmt = $this->conn->prepare($sql);
		if(!empty($params)):
			foreach($params as $key => $value):
				$this->bind(":{$key}", $value);
			endforeach;
		endif;
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value) :
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value) :
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value) :
                    $type = PDO::PARAM_NULL;
                    break;
                default :
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

	public function query($sql, $params = []){
		$this->prepare($sql, $params);
		$this->exec();
	}

    public function exec(){
        return $this->stmt->execute();
    }

    public function num_rows(){
        return $this->stmt->rowCount();
    }

    public function lastInsertId(){
        return $this->num_rows() == 0? 0: $this->conn->lastInsertId();
    }

    public function fetch(){
        $this->exec();
        return $this->num_rows() == 0? []: $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll(){
        $this->exec();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

