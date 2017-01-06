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

    public function getRequisition($id = null): Response{
        return $this->response;
    }
    
    /**
     * The post requisition add an user to database
     * THe field "Created" and "Modified" are generated in the method
     * @param mixed $postData Data sended from the endpoint
     * @return \Response Object with the Response information
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
            $postData["name"],
            $postData["email"],
            ApplicationSecurity::generatePasswordHash($postData["password"]),
            $actualDateTime,
            $actualDateTime
        );

        DbConnection::getInstance()->beginTransaction();
        $result = UserDAO::getInstance()->insert($userBean);

        // If user are inserted, try insert the auth row
        if($result[DbConnection::ERROR_INFO_CODE_INDEX] == 0){

            // Get the last id of user in the database
            $lastIdUser = DbConnection::getInstance()->lastInsertId();

            $authController = new AuthController();
            $resultAuth = $authController->postRequisition($lastIdUser);

            // If auth are not inserted, abort the operation
            if($resultAuth->getStatus() === ResponseStatus::FAILED_STATUS){
                DbConnection::getInstance()->rollBack();
                $this->response = $resultAuth;
            }

            // If auth are inserted, commit the operation
            DbConnection::getInstance()->commit();
            $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
            $this->response->setMessage("User inserted with success");

            $userBean->setId($lastIdUser);
            $this->response->setData($userBean);

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

    public function putRequisition($putData): Response {
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
            $returnLogin["name"],
            $returnLogin["email"],
            "",
            new DateTime($returnLogin["created"]),
            new DateTime($returnLogin["modified"]),
            $returnLogin["id"]);

        // Generate token of user
        //TODO: Atualizar token do usuário
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