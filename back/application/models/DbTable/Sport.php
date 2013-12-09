<?php

class Model_DbTable_Sport extends Zend_Db_Table_Abstract {

    protected $_name = "sport";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    public function getSports($spo='f'){
        if (trim($spo)=='') $city='f';
        $select = $this->select()
            ->from($this->_name,array('id_spo', 'spo_name', 'value'=>'spo_name'))
            ->where("spo_name LIKE '{$spo}%'")
            ->order('spo_name ASC')
            ->limit(10, 0);
        return $this->fetchAll($select);
    }
    
    public function getSportByName($sport){
        $select = $this->select()
            ->from($this->_name,array('ID'=>'id_spo', 'Name'=>'spo_name'))
            ->where("spo_name LIKE '{$sport}'")
            ->order('spo_name ASC')
            ->limit(10, 0);
        return $this->fetchAll($select);
    }
    
}

