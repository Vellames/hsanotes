<?php


class UserDAO extends DAO {

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
        if(!isset(parent::$instance)){
            parent::$instance = new UserDAO();
        }
        return parent::$instance;
    }

    public function select(int $id = null): Response{
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setData("foo");
        $this->response->setMessage("foo");
        return $this->response;
    }

    /**
     * Insert an user in database
     * @param IBean $object Object with the data of new user
     * @return int If the user are inserted, return the Id of user.  If not, return the error code * -1;
     */
    public function insert($object): int{

        $sql = "INSERT INTO " . $this->tableName . " (name, email, password, created, modified) VALUES (?,?,?,?,?);";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $object->getName(), PDO::PARAM_STR);
        $stmt->bindValue(2, $object->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(3, $object->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(4, $object->getCreated()->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(5, $object->getModified()->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->execute();
        
        return ($stmt->errorCode() > 0 ? ($stmt->errorCode() * - 1) : DbConnection::getInstance()->lastInsertId());
    }

    public function update(IBean $object): int{
        return 1;
    }

    public function deleteById(int $id): int{
        return 1;
    }

}