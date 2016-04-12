<?php
include './mongodb.class.php';

/**
 *
 */
class DB{
    protected $_db;
    protected $_collection;
    function __construct($collectionName){
        $this->_db = new Mongo_DB();
        //print_r($this->_db);
        $this->_collection = $this->_db->selectCollection($collectionName);
    }
    function __destruct(){
        P('Close DB.');
    }
}
