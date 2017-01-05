<?php

/**
 * Class that must be extended by all DAO class in project
 * All IDao class must be a singleton implementation
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
abstract class DAO {

    /**
     * @var Dao Instance of singleton
     */
    protected static $instance;

    /**
     * @var Response Object that contain the response information
     */
    protected $response;

    /**
     * DAO constructor.
     * Initialize the $response object
     */
    function __construct(){
        $this->response = new Response();
    }

    /**
     * Singleton implementation
     * @return mixed Return an instance of an DAO child object
     */
    public abstract static function getInstance();

    /**
     * This method is called when a get requisition is received. If Id is passed this method return the register
     * whit the passed id, if not, return all registers of table
     * @param null | int $id Id of object
     *
     * @return Response Return a response with the result information
     */
    public abstract function select(int $id = null) : Response;
    
    /**
     * This method must insert an IBean in database
     * @param IBean $object Object to insert in database
     *
     * @return int Return a response with the result information
     */
    public abstract function insert($object) : int;
    
    /**
     * This method must update an IBean in database
     * @param IBean $object Object to update in database
     *
     * @return int Return a response with the result information
     */
    public abstract function update(IBean $object) : int;
    
    /**
     * This method must delete an row in database
     * @param int $id Id to delete
     *
     * @return int Return a response with the result information
     */
    public abstract function deleteById(int $id) : int;
}
