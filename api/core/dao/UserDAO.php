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
     * @return UserDAO Return an instance of an DAO
     */
    public static function getInstance() {
        if(!isset(self::$instance)){
            self::$instance = new UserDAO();
        }
        return self::$instance;
    }

    /**
     * Load an user
     * @param int $id Id of object
     *
     * @return array Return a array with the result information
     */
    public function selectById(int $id): array{
         $sql = "SELECT id, name, email, created, modified from {$this->tableName} where id = ?";
         $stmt = DbConnection::getInstance()->prepare($sql);
         $stmt->bindValue(1, $id , PDO::PARAM_INT);
         return PDOSelectResult::returnArray($stmt->execute(), $stmt);
    }

    /**
     * Insert an user in database
     * @param IBean $object Object with the data of new user
     * @return array If the user are inserted, return the Id of user.  If not, return the error code * -1;
     */
    public function insert(IBean $object): array{
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

    /**
     * Update an user in database
     * @param IBean $object to update
     * @return array Return the result of update action
     */
    public function update(IBean $object): array{
        
        $sql = "UPDATE {$this->tableName} SET name = ?, email = ? , password = ? , modified = ? where id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $object->getName(), PDO::PARAM_STR);
        $stmt->bindValue(2, $object->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(3, $object->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(4, $object->getModified()->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(5, $object->getId(), PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->errorInfo();
    }

    /**
     * Delete an user
     * @param int $id User to be deleted
     * @return array Return a PDO erro info array
     */
    public function deleteById(int $id): array{
        $sql = "DELETE FROM {$this->tableName} where id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->errorInfo();
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

    /**
     * @param string $email Email
     * @return int Id of user, if user not exists, return -1
     */
    public function getUserIdByEmail(string $email) : int {
        $sql = "SELECT id FROM {$this->tableName} where email = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() == 1 ? $stmt->fetch(PDO::FETCH_ASSOC)["id"] : -1;
    }

    /**
     * Generate the fields to password recovery solicitation
     * @param int $userId Id of user to recovery
     * @return array Array with a PDO error or hash information
     */
    public function generatePasswordRecoveryHash(int $userId) : array {

        $expiration = new DateTime();
        $expiration->add(date_interval_create_from_date_string(App::DEFAULT__RECOVERY_PASSWORD_TOKEN_EXPIRATION_TIME));

        $hash = ApplicationSecurity::generatePasswordRecoveryHash($userId);

        $sql = "UPDATE {$this->tableName} SET password_recovery_hash = ?, password_recovery_expiration = ? where id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $hash, PDO::PARAM_STR);
        $stmt->bindValue(2, $expiration->format("Y-m-d H:i:s"), PDO::PARAM_STR);
        $stmt->bindValue(3, $userId, PDO::PARAM_INT);

        $successArrayReturn = array(
            "hash" => $hash,
            "expiration" => $expiration->format("Y-m-d H:i:s")
        );

        return $stmt->execute() ? $successArrayReturn :  $stmt->errorInfo();
    }

    /**
     * Verify if the email and hash sent by the endpoint are correct
     * @param string $email Email of user
     * @param string $hash Hash of password recovery
     * @return string|bool|array Return true if hash is ok, else, return an string with the error description. If an error occurred, return the error info array
     */
    public function checkPasswordRecoveryHash(string $email, string $hash){
        $sql = "select id, password_recovery_expiration from {$this->tableName} where email = ? and password_recovery_hash = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->bindValue(2, $hash, PDO::PARAM_STR);
        if(!$stmt->execute()){
            return $stmt->errorInfo();
        }

        // Verify if the hash matches with email
        if($stmt->rowCount() == 0){
            return "Hash and email doe'nt match";
        }

        //Verify the expiration of hash
        $hashExpiration = new DateTime($stmt->fetch(PDO::FETCH_ASSOC)["password_recovery_expiration"]);
        $actualTime = new DateTime();

        if($actualTime->getTimestamp() > $hashExpiration->getTimestamp()){
            return "Password recovery hash expired";
        }

        return true;
    }

    /**
     * Update the password of user
     * @param int $userId Id of user to update the password
     * @param string $newPassword New password
     * @return array PDO error info array
     */
    public function updatePassword(int $userId, string $newPassword) : array{
        $sql = "UPDATE {$this->tableName} SET password = ? where id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, ApplicationSecurity::generatePasswordHash($newPassword), PDO::PARAM_STR);
        $stmt->bindValue(2, $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->errorInfo();
    }

    /**
     * Change the expiration date of the expiration field of recovery password
     * @param int $userId User id
     * @return array PDO error info array
     */
    public function invalidatePasswordRecoveryHash(int $userId) : array{
        $sql = "UPDATE {$this->tableName} SET password_recovery_expiration = '1970-01-01 00:00:00' where id = ?";
        $stmt = DbConnection::getInstance()->prepare($sql);
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->errorInfo();
    }
}