<?php

class Ground_Model_DbTable_BranchLike extends Zend_Db_Table_Abstract {

    protected $_name = "branch_like";
    protected $_primary = 'bra_id';

    public function __construct() {
        $this->_setAdapter('APP');
    }


}