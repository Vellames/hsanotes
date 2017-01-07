<?php

/**
 * This class access the database to do the necessary modifications and insertions in table
 * @author cassiano.vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class NoteDAO extends DAO {

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
     * @return NoteDAO Return an instance of an DAO
     */
    public static function getInstance() {
        if(!isset(self::$instance)){
            self::$instance = new NoteDAO();
        }
        return self::$instance;
    }

    /**
     * Load note by id
     * @param null | int $id Id of note
     *
     * @return array Return a array with the result information
     */
    public function selectById(int $id): array{
        $sql = "SELECT * FROM {$this->tableName} where id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        return PDOSelectResult::returnArray($stmt->execute(), $stmt);
    }

    /**
     * Load all notes of an user
     * @param int $userId Id of user
     * @param string|null $order Field to order the result
     * @param string|null $orderType Order by asc or desc
     * @param int|null $page Page to load
     * @param int|null $registersByPage Registers by page
     * @return array Return an array with the notes
     */
    public function selectNotesByUser(int $userId, string $order = null, string $orderType = null, int $page = null, int $registersByPage = null) : array {

        //Possible order by notes
        $possibleOrderBy = array("title", "created", "modified");

        // Mount the query
        $sql = "SELECT * FROM {$this->tableName} where user_id = ? ";

        // Add order clause
        if(isset($order)){

            // If user pass an invalid order column type, order by title
            if(!in_array($order, $possibleOrderBy)){
                $order = "title";
            }
            $sql .= " order by " . $order . " " . (strtolower($orderType) == "desc" ? "desc" : "asc");
        }

        //Pagination
        if(isset($page)){

            if(!isset($registersByPage)){
                $registersByPage = App::DEFAULT_REGISTERS_BY_PAGE;
            }

            $offset = ($page - 1) * $registersByPage;

            $sql .= " limit " . $registersByPage . " offset " . $offset;
        }

        // After build query, execute the query
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        return PDOSelectResult::returnArray($stmt->execute(), $stmt, true);
    }

    /**
     * Insert a note in database
     * @param IBean $object Note to insert in database
     *
     * @return array Return an array with the result information
     */
    public function insert(IBean $object): array{
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
