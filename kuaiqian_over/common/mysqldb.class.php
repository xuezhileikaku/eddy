<?php

class mysqldb {

    private static $ins = null;
    private $db = null;

    final private function __construct() {
        require ROOT . '/conf/config.php'; //配置信息，ROOT在init.php文件中定义
        $this->connect($db_info ['db_addr'], $db_info ['db_user'], $db_info ['db_pwd'], $db_info ['db_name']);
    }

    public function __destruct() {
        $this->close();
    }

    public function close() {
        if(!$this->db){
            mysqli_close($this->db);
        }
    }

    public function connect($host, $username, $password, $dbname) {
        $this->db = mysqli_connect($host, $username, $password, $dbname);
        if (mysqli_connect_errno() !== 0) {
            exit('数据库连接失败！');
        }
    }

    public function getAll($sql) {
        $list = array();
        $rs = $this->query($sql);
        if ($rs === false or empty($rs)) {
            return false;
        }
        while ($row = mysqli_fetch_assoc($rs)) {
            $list [] = $row;
        }
        return $list;
    }

    public function getOne($sql) {
        $rs = $this->query($sql);
        if ($rs === false or empty($rs)) {
            return false;
        }
        return mysqli_fetch_assoc($rs);
    }

    public function setCharset($code) {
        return mysqli_query($this->db, 'set names ' . $code);
    }

    public function query($sql) {
        return mysqli_query($this->db, $sql);
    }
    
    public function getAffectedRows(){
    	return mysqli_affected_rows($this->db);
    }

    public static function getIns() {
        if (self::$ins instanceof self) {
            return self::$ins;
        } else {
            self::$ins = new mysqldb();
            return self::$ins;
        }
    }

    final private function __clone() {
        
    }

}

?>