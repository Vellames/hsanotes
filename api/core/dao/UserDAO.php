<?php


class UserDAO extends DAO {

    /**
     * @var Dao Instance of singleton
     */
    private static $instance;

    /**
     * @var string Table name used to do the database manipulations
     */
    private $tableName = "user";

    /**
     * The parent construct initialize the $response variable
     */
    function __construct(){
        parent::__construct();
    }

    /**
     * Singleton implementation
     * @return mixed Return an instance of an DAO
     */
    public static function getInstance() {
        if(!isset(self::$instance)){
            self::$instance = new UserDAO();
        }
        return self::$instance;
    }

    /**
     * This method is called when a get requisition is received. Select the register by id column
     * @param int $id Id of object
     *
     * @return array Return a array with the result information
     */
    public function selectById(int $id): array{

    }

    /**
     * Insert an user in database
     * @param IBean $object Object with the data of new user
     * @return array If the user are inserted, return the Id of user.  If not, return the error code * -1;
     */
    public function insert($object): array{
        $sql = "INSERT INTO " . $this->tableName . " (name, email, password, created, modified) VALUES (?,?,?,?,?);";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $object->getName(), PDO::PARAM_STR);
        $stmt->bindValue(2, $object->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(3, $object->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(4, $object->getCreated()->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(5, $object->getModified()->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->errorInfo();
    }

    public function update(IBean $object): array{

    }

    public function deleteById(int $id): array{

    }

    /**
     * Verify password and email of the user to login in application
     * @param string $email Email of user
     * @param string $password Password of user
     * @return array Return the response information
     */
    public function login(string $email, string $password) : array{
        $sql = "SELECT id, name, email, created, modified from {$this->tableName} where email = ? and password = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->bindValue(2, ApplicationSecurity::generatePasswordHash($password), PDO::PARAM_STR);
        if(!$stmt->execute()){
            return PDOErrorInfo::returnError($stmt->errorInfo(
                DbConnection::ERROR_INFO_CODE_INDEX),
                DbConnection::ERROR_INFO_MSG_INDEX
            );
        }

        return $stmt->rowCount() === 1 ? $stmt->fetch(PDO::FETCH_ASSOC) : array();
    }

}