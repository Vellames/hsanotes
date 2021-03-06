<?php

/**
 * This class contain business rule of User
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class UserController extends Controller {
    
    /**
     * The parent construct initialize the $response variable
     */
    function __construct(){
        parent::__construct();
    }
    
    /**
     * Load an user
     * @param int $id User id to load information
     * @return Response Return the user loaded
     */
    public function getRequisition(int $id): Response{

        // Verify params sent by the endpoint
        $mandatoryFields = array("id");
        parent::verifyMandatoryFields($mandatoryFields, $_GET);

        // Recovery the data of database
        $resultUser = UserDAO::getInstance()->selectById($id);
        
        // Catch any errors in result
        if(!$resultUser[PDOSelectResult::EXECUTED_INDEX]){
            return PDOSelectResult::defaultSelectResultError($resultUser);;
        }

        // Verify if note exists
        if(!$resultUser[PDOSelectResult::RESULT_INDEX]){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("The user don't exists");
            return $this->response;
        }

        // Creating necessary objects
        $userBean = new UserBean(
            $resultUser[PDOSelectResult::RESULT_INDEX]["id"],
            $resultUser[PDOSelectResult::RESULT_INDEX]["name"],
            $resultUser[PDOSelectResult::RESULT_INDEX]["email"],
            "",
            new DateTime($resultUser[PDOSelectResult::RESULT_INDEX]["created"]),
            new DateTime($resultUser[PDOSelectResult::RESULT_INDEX]["modified"])
        );

        // Renew token
        $token = $this->renewAuthToken($userBean);

        // Send the response
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("User listed with success");
        $this->response->setData(array(
            "user" => $userBean,
            "token" => $token
        ));
        
        return $this->response;
    }
    
    /**
     * The post requisition add an user to database
     * THe field "Created" and "Modified" are generated in the method
     * @param mixed $postData Data sended from the endpoint
     * @return Response Object with the Response information
     */
    public function postRequisition(array $postData): Response{

        // Verify params sent by the endpoint
        $mandatoryFields = array("email", "password", "name");
        parent::verifyMandatoryFields($mandatoryFields, $postData);

        // Verify if user email is valid
        if(!$this->verifyUserEmail($postData["email"])){
            http_response_code(400);
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Invalid email");
            return $this->response;
        }

        // Verify if user password is valid
        if(!$this->verifyUserPassword($postData["password"])){
            http_response_code(400);
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("The password must be at least 6 letters");
            return $this->response;
        }

        // Creating a new user
        $actualDateTime = new DateTime();
        $userBean = new UserBean(
            null,
            $postData["name"],
            $postData["email"],
            ApplicationSecurity::generatePasswordHash($postData["password"]),
            $actualDateTime,
            $actualDateTime
        );

        // Starting the transaction
        DbConnection::getInstance()->beginTransaction();

        //Inserting a new user
        $result = UserDAO::getInstance()->insert($userBean);

        // If an error occurred in user insertion
        if($result[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            DbConnection::getInstance()->rollBack();
            return PDOErrorInfo::defaultDMLResultError($result);
        }

        // If user are inserted, try insert the auth row

        // Get the last id of user in the database
        $lastIdUser = DbConnection::getInstance()->lastInsertId();
        $userBean->setId($lastIdUser);

        // Insert a new auth
        $resultAuth = AuthDAO::getInstance()->insert($userBean);

        // If auth are not inserted, abort the operation
        if($resultAuth[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            DbConnection::getInstance()->rollBack();
            return PDOErrorInfo::defaultDMLResultError($resultAuth);
        }

        // If auth are inserted, commit the operation
        DbConnection::getInstance()->commit();

        // Send the response
        http_response_code(201);
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("User inserted with success");
        $this->response->setData(array(
            "user" => $userBean
        ));
        
        return $this->response;
    }
    
    /**
     * The put requisition edit an user in database
     * The field "Modified" is generated in the method
     * @param array $putData Data sended from the endpoint
     * @return Response Object with the Response information
     */
    public function putRequisition(array $putData): Response {

        // Verify params sent by the endpoint
        $mandatoryFields = array("email", "password", "name", "id");
        parent::verifyMandatoryFields($mandatoryFields, $putData);

        // Verify if user email is valid
        if(!$this->verifyUserEmail($putData["email"])){
            http_response_code(400);
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Invalid email");
            return $this->response;
        }

        // Verify if user password is valid
        if(!$this->verifyUserPassword($putData["password"])) {
            http_response_code(400);
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("The password must be at least 6 letters");
            return $this->response;
        }

        // Verify if user exists
        $resultUser = UserDAO::getInstance()->selectById($putData["id"]);
        
        // Catch any errors in result
        if(!$resultUser[PDOSelectResult::EXECUTED_INDEX]){
            return PDOSelectResult::defaultSelectResultError($resultUser);
        }
        
        // Verify if user exists
        if(!$resultUser[PDOSelectResult::RESULT_INDEX]){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("The user don't exists");
            return $this->response;
        }
        
        $userBean = new UserBean(
                $resultUser[PDOSelectResult::RESULT_INDEX]["id"],
                $putData["name"],
                $putData["email"],
                ApplicationSecurity::generatePasswordHash($putData["password"]),
                new DateTime($resultUser[PDOSelectResult::RESULT_INDEX]["created"]),
                new DateTime()
        );
        
        $returnPut = UserDAO::getInstance()->update($userBean);
        if($returnPut[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            return PDOErrorInfo::defaultDMLResultError($returnPut);
        }
        
        // Generate token of user
        $returnRenewAuth = $this->renewAuthToken($userBean);
        
        // Returning the info
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("User edited with success");
        $this->response->setData(array(
            "user" => $userBean,
            "token" => $returnRenewAuth
         ));
            
        return $this->response;
    }

    /**
     * Delete an user of database
     * @param int $id Id of user to be deleted
     * @return Response
     */
    public function deleteRequisition(int $id): Response {

        // Starts the transaction
        DbConnection::getInstance()->beginTransaction();

        // Delete the notes of user
        $resultDeleteNotes = NoteDAO::getInstance()->deleteByUserId($id);
        if($resultDeleteNotes[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            DbConnection::getInstance()->rollBack();
            return PDOErrorInfo::defaultDMLResultError($resultDeleteNotes);
        }

        // Delete the auth register of user
        $resultDeleteAuth = AuthDAO::getInstance()->deleteById($id);
        if($resultDeleteAuth[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            DbConnection::getInstance()->rollBack();
            return PDOErrorInfo::defaultDMLResultError($resultDeleteAuth);
        }

        // Delete the user
        $resultDeleteUser = UserDAO::getInstance()->deleteById($id);
        if($resultDeleteUser[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            DbConnection::getInstance()->rollBack();
            return PDOErrorInfo::defaultDMLResultError($resultDeleteUser);
        }

        // Commit the transaction
        DbConnection::getInstance()->commit();

        // If its all ok, send the response
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("User deleted with success");
        return $this->response;
    }

    /**
     * Login the user in system
     * @param array $endPointParams Params received by endpoint
     * @return Response Return the response object. If login are realized with success, return the user in response->data
     */
    public function login(array $endPointParams) : Response{

        // Verify params sent by the endpoint
        $mandatoryFields = array("email", "password");
        parent::verifyMandatoryFields($mandatoryFields, $endPointParams);

        $returnLogin = UserDAO::getInstance()->login($endPointParams["email"], $endPointParams["password"]);

        // If errorCode exists in the return login is because an error occurred
        if(isset($returnLogin["errorCode"])){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Error in login requisition");
            $this->response->setData($returnLogin);
            return $this->response;
        }

        // If return is empty, the login and/or password are wrong
        if(empty($returnLogin)){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Email or password incorrect");
            return $this->response;
        }

        // Else the login return the user data
        $userBean = new UserBean(
            $returnLogin["id"],
            $returnLogin["name"],
            $returnLogin["email"],
            "",
            new DateTime($returnLogin["created"]),
            new DateTime($returnLogin["modified"])
        );

        // Generate token of user
        $returnRenewAuth = $this->renewAuthToken($userBean);
        
        // Returning the info
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("User logged with success");
        $this->response->setData(array(
            "user" => $userBean,
            "token" => $returnRenewAuth
        ));
        
        return $this->response;
    }

    /**
     * Logoff the user of system
     * @param int $userId Id of user
     * @return Response Response data
     */
    public function logoff(int $userId) : Response {

        $result = AuthDAO::getInstance()->logoff($userId);

        // Catch any errors in result
        if($result[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            return PDOErrorInfo::defaultDMLResultError($result);
        }

        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("Logoff realized with success");

        return $this->response;
    }

    /**
     * Renew the token of user
     * @param UserBean $userBean User to renew token
     * @return string string Return the new token
     */
    public function renewAuthToken(UserBean $userBean){

        // Adding the new time token
        $dateTime = new DateTime();
        $dateTime->add(date_interval_create_from_date_string(App::DEFAULT_NEW_TIME_TOKEN));
        
        $token = ApplicationSecurity::generateAuthHash($userBean->getId());
        $authBean = new AuthBean($userBean, $token, $dateTime);

        // Update Auth register
        $responseAuth = AuthDAO::getInstance()->update($authBean);
        
        // In case of error in the update of Auth
        if($responseAuth[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Error to update token");
            $this->response->setData(PDOErrorInfo::returnError(
                $responseAuth[DbConnection::ERROR_INFO_CODE_INDEX],
                $responseAuth[DbConnection::ERROR_INFO_MSG_INDEX]
            ));
            die(json_encode($this->response, JSON_UNESCAPED_UNICODE));
        }
        
        return $token;
    }

    /**
     * Send an email to the recipient
     * @param array $postData Email to send the message
     * @return Response Return the response data
     */
    public function forgotPassword(array $postData) : Response{

        // Verify params sent by the endpoint
        $mandatoryFields = array("email");
        parent::verifyMandatoryFields($mandatoryFields, $postData);

        $email = $postData["email"];

        // Verify if email exists in database
        $userId = UserDAO::getInstance()->getUserIdByEmail($email);
        if($userId === -1){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("This email not exist in database");
            return $this->response;
        }

        // Get the hash
        $result = UserDAO::getInstance()->generatePasswordRecoveryHash($userId);

        // Verify if the transaction is ok
        if(!isset($result["hash"])){
            PDOErrorInfo::defaultDMLResultError($result);
        }

        // Set up the email
        $emailSubject = "HSA Notes - Recovery Password";
        $emailBody = "<p>Hi, lost your password? I am the HSA Robot and i will help you to resolve this problem :)</p>
                      <p>Put this code in recovery screen in the app: <strong>{$result["hash"]}</strong></p>
                      <p>This code is valid until <strong>{$result["expiration"]}</strong></p>
                      ";

        // Send mail
        if(MailSender::getInstance()->sendEmail(array($email), $emailSubject, $emailBody)) {
            $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
            $this->response->setMessage("An email has been sent to you with a recovery code. Please verify your mailbox");
        } else {
            http_response_code(500);
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("An error has occurred when we tried send your email.");
        }

        return $this->response;
    }

    /**
     * Update the password of user if the hash sent matches with the hash in database
     * @param array $postData Data sent by endpoint
     * @return Response Return the response data
     */
    public function renewPassword(array $postData) : Response{

        // Verify params sent by the endpoint
        $mandatoryFields = array("email", "hash", "password");
        parent::verifyMandatoryFields($mandatoryFields, $postData);

        $password = $postData["password"];
        $hash = $postData["hash"];
        $email = $postData["email"];

        $result = UserDAO::getInstance()->checkPasswordRecoveryHash($email, $hash);
        // If an array is returned, It is because an PDO error occurred
        if(is_array($result)){
            return PDOSelectResult::defaultSelectResultError($result);
        }

        // If a string is returned, Its because a business rule validation occurred
        if(is_string($result)){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage($result);
            return $this->response;
        }

        // Recovery the userId with the email
        $userId = UserDAO::getInstance()->getUserIdByEmail($email);

        // Verify if user password is valid
        if(!$this->verifyUserPassword($password)) {
            http_response_code(400);
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("The password must be at least 6 letters");
            return $this->response;
        }

        // Begin a transaction to update the password and invalidate the recovery hash
        DbConnection::getInstance()->beginTransaction();

        $resultChangePassword = UserDAO::getInstance()->updatePassword($userId, $password);
        if($resultChangePassword[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            DbConnection::getInstance()->rollBack();
            return PDOErrorInfo::defaultDMLResultError($resultChangePassword);
        }

        $resultInvalidateRecoveryHash = UserDAO::getInstance()->invalidatePasswordRecoveryHash($userId);
        if($resultInvalidateRecoveryHash[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            DbConnection::getInstance()->rollBack();
            return PDOErrorInfo::defaultDMLResultError($resultInvalidateRecoveryHash);
        }

        // If all its ok, send the response
        DbConnection::getInstance()->commit();
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("Password changed with success");
        return $this->response;
    }

    /**
     * Check if email is valid
     * @param string $userMail User to be checked
     * @return bool Return if the user has been validated with success
     */
    private function verifyUserEmail(string $userMail) : bool {
        return ( strpos($userMail, '@') && strpos($userMail, ".") );
    }

    /**
     * Check if password have at least 6 chars
     * @param string $password
     * @return bool
     */
    private function verifyUserPassword(string $password) : bool {
        return (strlen($password) >= 6);
    }
}