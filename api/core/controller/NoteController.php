<?php

/**
 * This class contain business rule of Note
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class NoteController extends Controller{
    
    /**
     * The parent construct initialize the $response variable
     */
    function __construct(){
        parent::__construct();
    }
    
    public function deleteRequisition($id): Response {
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("oioi");
        return $this->response;
    }

    public function getRequisition($id = null): Response {
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("oioi");
        return $this->response;
    }
    
    /**
     * Add a new note in database
     * @param type $postData Params sended by the endpoint
     * @return Response Return one response with the status of solicitation
     */
    public function postRequisition($postData): Response {
        
        $userBean = new UserBean($postData["user_id"]);
        $noteBean = new NoteBean(
                NULL,
                $userBean,
                $postData["title"],
                $postData["description"],
                new DateTime(),
                new DateTime()
        );
        
        $insertResult = NoteDAO::getInstance()->insert($noteBean);
        
         // If user arent inserted, rollback the transcation
        if($insertResult[DbConnection::ERROR_INFO_CODE_INDEX] != DbConnection::PDO_SUCCESS_RETURN){
            $this->response->setStatus(ResponseStatus::FAILED_STATUS);
            $this->response->setMessage("Error to insert user");
            $this->response->setData(PDOErrorInfo::returnError(
                $insertResult[DbConnection::ERROR_INFO_CODE_INDEX],
                $insertResult[DbConnection::ERROR_INFO_MSG_INDEX]
            ));
        } else {
            
            $noteBean->setId(DbConnection::getInstance()->lastInsertId());
            $token = (new UserController)->renewAuthToken($userBean);
            
            $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
            $this->response->setMessage("Note inserted with success");
            $this->response->setData(array(
               "note"  => $noteBean,
               "token" => $token
            ));
        }
        
        return $this->response;
    }
    
    /**
     * Update a note in database
     * @param type $putData 
     */
    public function putRequisition($putData): Response {
       
        //Verify if note exists
        $resultNote = NoteDAO::getInstance()->selectById($putData["id"]);
        var_dump($resultNote);
        
        // Catch any errors in result
        if(!$resultNote[PDOSelectResult::EXECUTED_INDEX]){
            return $this->response->defaultSelectResultErrorResponse($resultNote);
        }
        
        
        
        
    }

}
