<?php

class Ground_Model_DbTable_FieldPrices extends Zend_Db_Table_Abstract {

    protected $_name = "field_prices";
    protected $_primary = 'fie_id';

    public function __construct() {
        $this->_setAdapter('APP');
    }
    

    public function getPriceId($fie_id, $bra_sch_id){
        $where = "fie_id = {$fie_id} AND bra_sch_id = {$bra_sch_id}";
        $select = $this->select()
            ->from(array('FP'=>'field_prices'),array('bra_pri_id'=>'bra_pri_id'))
            ->setIntegrityCheck(false)
            ->join(array('BP' => 'branch_prices'), "BP.id_bra_pri = FP.bra_pri_id "
                 , array('currency'=>'bra_pri_currency', 'value'=>'bra_pri_value'))
            ->where($where);
        if ($row = $this->fetchRow($select))
            return $row->toArray();
        else 
            return array();
    }


}

