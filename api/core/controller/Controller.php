<?php

/**
 * All controllers must extends of the Controller class. This abstract class contains the basic
 * methods that a controller must be
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
abstract class Controller{

    /**
     * @var Response Object that contain the response information
     */
    protected $response;

    /**
     * Controller constructor.
     * Initialize the $response object
     */
    function __construct(){
        $this->response = new Response();
    }

    /**
     * This method is called when a get requisition is received.
     * Load an IBean from database
     * This method must be called when the user wants retrieve the data from database
     * @param int $id Id of object
     * @return Response Object with response data
     */
    public abstract function getRequisition(int $id) : Response;

    /**
     * This method is called when a post requisition is received.
     * This method must be called when the user wants insert a new register in database
     * @param $postData mixed Data to insert in database
     * @return Response Object with response data
     */
    public abstract function postRequisition(array $postData) : Response;

    /**
     * This method is called when a put or patch requisition is received
     * This method must be called when the user wants update a register in database
     * @param $putData mixed Data to update in database
     * @return Response Object with response data
     */
    public abstract function putRequisition(array $putData) : Response;

    /**
     * This method is called when a delete requisition is received
     * This method must be called when the user wants delete a register in database
     * @param int $id int  Id to delete
     * @return Response Object with response data
     */
    public abstract function deleteRequisition(int $id) : Response;

    /**
     * Verify if all basic params necessary are in endpoint data sent, if any field is missing
     * Show an Response to the endpoint with the missing field
     * @param array $fields Mandatory fields in the endpoint parma
     * @param array $endpointParam Endpoint params array
     */
    protected function verifyMandatoryFields(array $fields, array $endpointParam){
        foreach($fields as $field){
            if(!isset($endpointParam[$field])){
                http_response_code(400);
                $this->response->setStatus(ResponseStatus::FAILED_STATUS);
                $this->response->setMessage("Missing '{$field}' param");
                die(json_encode($this->response, JSON_UNESCAPED_UNICODE));
            }
        }
    }
}