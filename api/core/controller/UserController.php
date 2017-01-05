<?php

/**
 * Created by PhpStorm.
 * User: vellames
 * Date: 04/01/17
 * Time: 22:56
 */
class UserController extends Controller {

    function __construct(){
        parent::__construct();
    }

    public function getRequisition($id = null): Response{
        $this->response->setMessage("oi");
        $this->response->setStatus("ola");
        return $this->response;
    }

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

    public function putRequisition($putData): Response
    {
        $this->response->setMessage("oi");
        $this->response->setStatus("ola");
        return $this->response;
    }

    public function deleteRequisition($id): Response
    {
        $this->response->setMessage("oi");
        $this->response->setStatus("ola");
        return $this->response;
    }
}