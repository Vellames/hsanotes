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

}