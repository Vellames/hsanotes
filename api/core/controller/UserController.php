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
     * @param type $postData Data sended from the endpoint
     * @return \Response Object with the Response information
     */
    public function postRequisition($postData): Response{
        $actualDateTime = new DateTime();
        $userBean = new UserBean(
            $postData["name"],
            $postData["email"],
            ApplicationSecurity::generatePasswordHash($postData["password"]),
            $actualDateTime,
            $actualDateTime
        );

        $result = UserDAO::getInstance()->insert($userBean);

        if($result > 0){
            $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
            $this->response->setMessage("User inserted with success");

            $userBean->setId($result);
            $this->response->setData($userBean);
        } else {
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Error to insert user (Error: {$result})");
            $this->response->setData([
               "error" => $result
            ]);
        }
        
        return $this->response;
    }

    public function putRequisition($putData): Response {
        return $this->response;
    }

    public function deleteRequisition($id): Response {
        return $this->response;
    }
}