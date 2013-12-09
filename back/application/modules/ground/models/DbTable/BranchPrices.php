<?php

class Ground_Model_DbTable_BranchPrices  extends Zend_Db_Table_Abstract {

    protected $_name = "branch_prices";

    public function __construct() {
        $this->_setAdapter('APP');
    }


}

