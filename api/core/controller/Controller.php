<?php

/**
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
     * This method is called when a get requisition is received. If Id is passed this method return the register
     * whit the passed id, if not, return all registers of table.
     * This method must be called when the user wants retrieve the data from database
     * @param null | int $id Id of object
     * @return Response Object with response data
     */
    public abstract function getRequisition($id = null) : Response;

    /**
     * This method is called when a post requisition is received.
     * This method must be called when the user wants insert a new register in database
     * @param $postData mixed Data to insert in database
     * @return Response Object with response data
     */
    public abstract function postRequisition($postData) : Response;

    /**
     * This method is called when a put or patch requisition is received
     * This method must be called when the user wants update a register in database
     * @param $putData mixed Data to update in database
     * @return Response Object with response data
     */
    public abstract function putRequisition($putData) : Response;

    /**
     * This method is called when a delete requisition is received
     * This method must be called when the user wants delete a register in database
     * @param $id int  Id to delete
     * @return Response Object with response data
     */
    public abstract function deleteRequisition($id) : Response;

}