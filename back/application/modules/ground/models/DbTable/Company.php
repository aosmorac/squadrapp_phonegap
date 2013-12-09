<?php

class Ground_Model_DbTable_Company extends Zend_Db_Table_Abstract {

    protected $_name = "company";

    public function __construct() {
        $this->_setAdapter('APP');
    }


}

