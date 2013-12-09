<?php

class Ground_Model_DbTable_BookingUser  extends Zend_Db_Table_Abstract {

    protected $_name = "booking_user";

    public function __construct() {
        $this->_setAdapter('APP');
    }


}

