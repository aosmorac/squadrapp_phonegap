<?php

class Ground_Model_DbTable_FieldStatus extends Zend_Db_Table_Abstract {

    protected $_name = "field_status";

    public function __construct() {
        $this->_setAdapter('APP');
    }


}

