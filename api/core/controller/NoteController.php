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

    public function postRequisition($postData): Response {
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("oioiee");
        return $this->response;
    }

    public function putRequisition($putData): Response {
        $this->response->setStatus(ResponseStatus::SUCCEEDED_STATUS);
        $this->response->setMessage("oioi");
        return $this->response;
    }

}
