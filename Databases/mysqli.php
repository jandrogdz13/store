<?php

class dbMysqli {
    public $conn;

    public function __construct() {
        try{
            $this->conn = new mysqli(HOST, USER, PASS, DB_NAME);
            $charset = $this->exec("SET NAMES " . CHAR);
            return $this->conn;
        }catch(Exception $e){
            echo $e->errorMessage();
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        unset($this->conn);
    }

    public function exec($sql) {
        return $this->conn->query($sql);
    }

    public function num_rows($sql) {
        $result = $this->conn->query($sql);
        return $result? $result->num_rows: 0;
    }

    public function fetch($sql) {
        return $result = $this->conn->query($sql)? $result->fetch_object(): [];
    }

    public function fetchAll($sql) {
        if($result = $this->conn->query($sql)):
            $object = [];
            while($rows = $result->fetch_object()):
                $object[] = $rows;
            endwhile;
            return $object;
        else:
            return [];
        endif;
    }
}
?>
