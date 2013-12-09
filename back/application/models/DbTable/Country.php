<?php

class Model_DbTable_Country extends Zend_Db_Table_Abstract {

    protected $_name = "country";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
}

