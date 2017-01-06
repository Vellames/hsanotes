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
     * @param type $id User id to load information
     * @return Response Return the user loaded
     */
    public function getRequisition($id = null): Response{
        $resultUser = UserDAO::getInstance()->selectById($id);
        
        // Catch any errors in result
        if(!$resultUser[PDOSelectResult::EXECUTED_INDEX]){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Error in user selection");
            $this->response->setData(PDOErrorInfo::returnError(
                $resultUser[PDOSelectResult::RESULT_INDEX][DbConnection::ERROR_INFO_CODE_INDEX],
                $resultUser[PDOSelectResult::RESULT_INDEX][DbConnection::ERROR_INFO_MSG_INDEX]
            ));
            return $this->response;
        }
        
        $userBean = new UserBean(
            $resultUser[PDOSelectResult::RESULT_INDEX]["id"],
            $resultUser[PDOSelectResult::RESULT_INDEX]["name"],
            $resultUser[PDOSelectResult::RESULT_INDEX]["email"],
            "",
            new DateTime($resultUser[PDOSelectResult::RESULT_INDEX]["created"]),
            new DateTime($resultUser[PDOSelectResult::RESULT_INDEX]["modified"])
        );
        
        $token = $this->renewAuthToken($userBean);
        
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
    public function postRequisition($postData): Response{

        if(!$this->verifyUserEmail($postData["email"])){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Invalid email");
            return $this->response;
        }

        if(!$this->verifyUserPassword($postData["password"])){
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

        DbConnection::getInstance()->beginTransaction();
        $result = UserDAO::getInstance()->insert($userBean);

        // If user are inserted, try insert the auth row
        if($result[DbConnection::ERROR_INFO_CODE_INDEX] == DbConnection::PDO_SUCCESS_RETURN){

            // Get the last id of user in the database
            $lastIdUser = DbConnection::getInstance()->lastInsertId();
            
            // Insert a new auth
            $resultAuth = AuthDAO::getInstance()->insert($lastIdUser);

            // If auth are not inserted, abort the operation
            if($resultAuth[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
                DbConnection::getInstance()->rollBack();
                $this->response->setStatus(ResponseStatus::FAILED_STATUS);
                $this->response->setMessage("Error to insert user");
                $this->response->setData(PDOErrorInfo::returnError(
                    $resultAuth[DbConnection::ERROR_INFO_CODE_INDEX],
                    $resultAuth[DbConnection::ERROR_INFO_MSG_INDEX]
                ));
            }

            // If auth are inserted, commit the operation
            DbConnection::getInstance()->commit();
            $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
            $this->response->setMessage("User inserted with success");

            $userBean->setId($lastIdUser);
            $this->response->setData(array(
                "user" => $userBean
            ));

        } else {
            // Case user arent inserted, rollback the operation
            DbConnection::getInstance()->rollBack();
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Error to insert user");
            $this->response->setData(PDOErrorInfo::returnError(
                $result[DbConnection::ERROR_INFO_CODE_INDEX],
                $result[DbConnection::ERROR_INFO_MSG_INDEX]
            ));
        }
        
        return $this->response;
    }
    
    /**
     * The put requisition edit an user in database
     * The field "Modified" is generated in the method
     * @param type $putData Data sended from the endpoint
     * @return \Response
     */
    public function putRequisition($putData): Response {
        
        // Verify if user exists
        $resultUser = UserDAO::getInstance()->selectById($putData["id"]);
        
        // Catch any errors in result
        if(!$resultUser[PDOSelectResult::EXECUTED_INDEX]){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Error in user selection");
            $this->response->setData(PDOErrorInfo::returnError(
                $resultUser[PDOSelectResult::RESULT_INDEX][DbConnection::ERROR_INFO_CODE_INDEX],
                $resultUser[PDOSelectResult::RESULT_INDEX][DbConnection::ERROR_INFO_MSG_INDEX]
            ));
            return $this->response;
        }
        
        // Verify if user exists
        if(!$resultUser[PDOSelectResult::RESULT_INDEX]){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("The user dont exists");
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
        
        // If user arent inserted, rollback the transcation
        if($returnPut[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            DbConnection::getInstance()->rollBack();
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Error to insert user");
            $this->response->setData(PDOErrorInfo::returnError(
                $returnPut[DbConnection::ERROR_INFO_CODE_INDEX],
                $returnPut[DbConnection::ERROR_INFO_MSG_INDEX]
            ));
            return $this->response;
        }
        
        // Generate token of user
        $returnRenewAuth = $this->renewAuthToken($userBean);
        
        // Returning the info
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("User eddited with success");
        $this->response->setData(array(
            "user" => $userBean,
            "token" => $returnRenewAuth
         ));
            
        return $this->response;
    }

    public function deleteRequisition($id): Response {
        return $this->response;
    }

    /**
     * Login the user in system
     * @param array $endPointParams Params received by endpoint
     * @return Response Return the response object. If login are realized with success, return the user in response->data
     */
    public function login(array $endPointParams) : Response{
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
            $this->response->setMessage("Email and/or password incorrect");
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
    
    /**
     * Renew the token of user
     * @param UserBean $userBean User to renew token
     * @return type string Return the new token
     */
    public function renewAuthToken(UserBean $userBean){
        $dateTime = new DateTime();
        $dateTime->add(date_interval_create_from_date_string("10 minutes"));
        
        $token = ApplicationSecurity::generateAuthHash($userBean->getId());
        $authBean = new AuthBean($userBean, $token, $dateTime);
        
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
}