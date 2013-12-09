<?php

class User_Model_DbTable_AutochatXUser extends Zend_Db_Table_Abstract {

    protected $_name = "auto_chatxuser";
    
    protected $_primary = 'aut_cha_id';

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    
}

