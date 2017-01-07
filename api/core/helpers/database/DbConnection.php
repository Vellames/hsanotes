<?php

/**
 * Connect with a database
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class DbConnection {

    // Description of index in the PDO->errorInfo() response array
    const ERROR_INFO_CODE_INDEX = 0;
    const ERROR_INFO_MSG_INDEX = 2;

    // Description of PDO->errorInfo()[0] success return
    const PDO_SUCCESS_RETURN = 0;

    /**
     * @var DbConnection instance of DbConnection
     */
    private static $instance;

    /**
     * Singleton constructor
     */
    private function __construct(){}

    /**
     * @var array Database configuration
     */
    private static $config = [
        "sgdb" => "mysql",
        "host" => "localhost",
        "databaseName" => "mydb",
        "user" => "root",
        "password" => "root"
    ];

    /**
     * @return DbConnection|PDO Singleton implementation
     */
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = self::newInstance();
        }
        return self::$instance;
    }

    /**
     * @return PDO Generate a new instance of DbConnection
     */
    private static function newInstance(){
        try {
            $db = new PDO(self::$config["sgdb"].
                ':host=' . self::$config["host"] .
                ';dbname='.self::$config["databaseName"],
                self::$config["user"],
                self::$config["password"]);

            // Forcing UTF8 encoding
            $db->exec("set names utf8");

            // Returning the instance
            return $db;
        } catch (PDOException $e){
            $response = new Response();
            $response->setStatus(ResponseStatus::FAILED_STATUS);
            $response->setMessage("Error in database instance");
            $response->setData(array(
                "errorCode" => $e->getCode(),
                "errorDescription" => $e->getMessage()
            ));
            die(json_encode($response, JSON_UNESCAPED_UNICODE));
        }
    }

}
