<?php

/**
 * Connect with a database
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class DbConnection {

    const ERROR_INFO_CODE_INDEX = 0;
    const ERROR_INFO_MSG_INDEX = 2;

    private static $instance;
    private static $config = [
        "sgbd" => "mysql",
        "database" => "localhost",
        "databaseName" => "mydb",
        "user" => "root",
        "password" => "root"
    ];

    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = self::newInstance();
        }
        return self::$instance;
    }

    private static function newInstance(){
        try {
            $db = new PDO(self::$config["sgbd"].
                ':host=' . self::$config["database"] .
                ';dbname='.self::$config["databaseName"],
                self::$config["user"],
                self::$config["password"]);
            $db->exec("set names utf8");
            return $db;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage() . "<br/>");
        }
    }

}
