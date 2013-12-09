<?php

class Ground_Model_DbTable_BranchSchedule extends Zend_Db_Table_Abstract {

    protected $_name = "branch_schedule";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    
    public function getScheduleByBranch($bra_id, $weekday=NULL){
        $where = "bra_id = {$bra_id}";
        if (isset($weekday) && $weekday >= 0){
            $where .= " AND weekday = {$weekday}";
        }
        $select = $this->select()->where($where);
        return $this->fetchAll($select);
    }


}

