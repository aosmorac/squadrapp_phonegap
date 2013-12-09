<?php

class User_Model_DbTable_Userlocation extends Zend_Db_Table_Abstract {

    protected $_name = "userlocation";
    
    protected $_primary = 'use_loc_id';

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    
}

