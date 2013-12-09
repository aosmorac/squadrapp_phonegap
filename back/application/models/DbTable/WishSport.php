<?php

class Model_DbTable_WishSport extends Zend_Db_Table_Abstract {

    protected $_name = "wish_sport";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
}

