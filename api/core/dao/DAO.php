<?php

/**
 * Class that must be extended by all DAO class in project
 * All IDao class must be a singleton implementation
 * @author Cassiano Vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
abstract class DAO {

    /**
     * @var Response Object that contain the response information
     */
    protected $response;

    /**
     * DAO constructor.
     * Initialize the $response object
     */
    protected function __construct(){
        $this->response = new Response();
    }

    /**
     * Singleton implementation
     * @return mixed Return an instance of an DAO child object
     */
    public abstract static function getInstance();

    /**
     * This method is called when a get requisition is received. Select the register by id column
     * @param int $id Id of object
     *
     * @return array Return a array with the result information
     */
    public abstract function selectById(int $id) : array;
    
    /**
     * This method must insert an IBean in database
     * @param IBean $object Object to insert in database
     *
     * @return array Return an array with the result information
     */
    public abstract function insert(IBean $object) : array;
    
    /**
     * This method must update an IBean in database
     * @param IBean $object Object to update in database
     *
     * @return array Return a array with the result information
     */
    public abstract function update(IBean $object) : array;
    
    /**
     * This method must delete an row in database
     * @param int $id Id to delete
     *
     * @return array Return a array with the result information
     */
    public abstract function deleteById(int $id) : array;
}
