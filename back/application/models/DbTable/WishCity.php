<?php

class Model_DbTable_WishCity extends Zend_Db_Table_Abstract {

    protected $_name = "wish_city";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
}

