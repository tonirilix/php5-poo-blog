<?php

class DB {

    private static $instance;
    private $MySQLi;

    private function __construct(array $dbOptions){
        $port = "3306";
        $port = (int)$port;
        $this->MySQLi = @ new mysqli(	
                $dbOptions['db_host'],                    
                $dbOptions['db_user'],
                $dbOptions['db_pass'],
                $dbOptions['db_name'],
                $port);

        if (mysqli_connect_errno()) {
            throw new Exception('No se pudo conectar.');
        }

        $this->MySQLi->set_charset("utf8");
    }

    public static function init(array $dbOptions){
        if(self::$instance instanceof self){
            return false;
        }

        self::$instance = new self($dbOptions);
    }

    public static function getMySQLiObject(){
        return self::$instance->MySQLi;
    }

    public static function query($q){
        return self::$instance->MySQLi->query($q);
    }

    public static function esc($str){
        return self::$instance->MySQLi->real_escape_string(htmlspecialchars($str));
    }
}
?>