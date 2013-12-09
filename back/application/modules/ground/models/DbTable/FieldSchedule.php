<?php

class Ground_Model_DbTable_FieldSchedule  extends Zend_Db_Table_Abstract {

    protected $_name = "field_schedule";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    public function getLastDate($fie_id){
        $select = $this->select()
                ->where("fie_id={$fie_id}")
                ->order(new Zend_Db_Expr("fie_sch_date DESC, fie_sch_hour DESC"))
                ->limit(1);
        return $this->fetchRow($select);
    }
    
    public function getFieldBook($id_fie_sch){
        $select = $this->select() 
            ->from(array('FS' => 'field_schedule')
                         , array(
                             'FS.id_fie_sch', 'FS.fie_id', 'FS.bra_sch_id', 
                             'FS.sta_id', 'FS.fie_sch_currency', 
                             'FS.fie_sch_value', 'FS.fie_sch_date', 
                             'FS.fie_sch_day', 'FS.fie_sch_hour'
                            )
                    )
            ->setIntegrityCheck(false)
            ->join(array('FK' => 'vw_size_material')
                        , "FS.fie_id = FK.id_fie"
                        , array('field_kind'=>'attributes')
                        )
            ->join(array('B' => 'branch')
                        , "B.id_bra = FK.bra_id"
                        , array(
                            'id_bra', 'com_id', 'bra_name', 'bra_area', 
                            'bra_neighborhood', 'bra_address', 'bra_phone', 
                            'bra_email', 'bra_location', 'bra_coordinates', 
                            'bra_alias'
                            )
                        )
            ->join(array('C' => 'company')
                        , "C.id_com = B.com_id"
                        , array('company'=>'com_name')
                        )
            ->where("FS.id_fie_sch = {$id_fie_sch}")
            ->limit(1);
        //Zend_Debug::dump($select.''); die;
        $row = $this->fetchRow($select)->toArray();
        return $row;
    }
    
    public function updateState($id_fie_sch, $sta_id){
        $data = array('sta_id'=>$sta_id);
        $where = "id_fie_sch = {$id_fie_sch}";
        if ($this->update($data, $where)){
            return true;
        }else {
            return false;
        }
    }


}

