<?php

/**
 * This class access the database to do the necessary modifications and insertions in table
 * @author cassiano.vellames <c.vellames@outlook.com>
 * @since 1.0.0
 */
class NoteDAO extends DAO{
    
    private $tableName = "note";

    /**
     * The parent construct initialize the $response variable
     */
    function __construct(){
        parent::__construct();
    }

    /**
     * Singleton implementation
     * @return mixed Return an instance of an DAO
     */
    public static function getInstance() {
        if(!isset(parent::$instance)){
            parent::$instance = new NoteDAO();
        }
        return parent::$instance;
    }
        
    public function deleteById(int $id): int {
        
    }

    public function insert($object): int {
        
    }

    public function select($id = null): \Response {
        
    }

    public function update(\IBean $object): int {
        
    }
}
