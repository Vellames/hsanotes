<?php

/**
 * This class access the database to do the necessary modifications and insertions in table
 * @author cassiano.vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class NoteDAO extends DAO{

    /**
     * @var Dao Instance of singleton
     */
    private static $instance;

    /**
     * @var string Table name used to do the database manipulations
     */
    private $tableName = "note";

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
            self::$instance = new NoteDAO();
        }
        return self::$instance;
    }

    /**
     * Load all note of an user
     * @param null | int $id Id of note
     *
     * @return array Return a array with the result information
     */
    public function selectById(int $id): array{
        $sql = "SELECT * FROM {$this->tableName} where id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        if(!$stmt->execute()){
            return PDOErrorInfo::returnError($stmt->errorInfo(
                DbConnection::ERROR_INFO_CODE_INDEX),
                DbConnection::ERROR_INFO_MSG_INDEX
            );
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insert a note in database
     * @param mixed $object Note to insert in database
     *
     * @return array Return an array with the result information
     */
    public function insert($object): array{
        $sql = "INSERT INTO {$this->tableName} (user_id, title, description, created, modified) VALUES (?,?,?,?,?)";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $object->getUser()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(2, $object->getTitle(), PDO::PARAM_STR);
        $stmt->bindValue(3, $object->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(4, $object->getCreated()->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(5, $object->getModified()->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->errorInfo();
    }

    /**
     * Update a note in database
     * @param IBean $object Note to update in database
     *
     * @return array Return a array with the result information
     */
    public function update(IBean $object): array{
        $sql = "UPDATE {$this->tableName} SET title = ? , description = ? , modified = ? where id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $object->getTitle(), PDO::PARAM_STR);
        $stmt->bindValue(2, $object->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(3, $object->getModified()->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(4, $object->getId(), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->errorInfo();
    }

    /**
     * Delete a note in database
     * @param int $id Id to delete
     *
     * @return array Return a array with the result information
     */
    public function deleteById(int $id): array{
        $sql = "DELETE FROM {$this->tableName} where id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->errorInfo();
    }
}
