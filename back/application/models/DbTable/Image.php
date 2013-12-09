<?php

class Model_DbTable_Image extends Zend_Db_Table_Abstract {

    protected $_name = "image";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    public function getImages($attr){
        $where = "1=1 ";
        foreach($attr as $k=>$v){
            $where .= "AND {$k}='{$v}' ";
        }        
        $select = $this->select()
            ->from($this->_name,array('id_ima','ima_table','ima_table_id','ima_active','ima_number','url_image','ima_date','ima_alias','id_ima_kin'))
            ->where($where)
            ->order('ima_date DESC');
        return $this->fetchAll($select);
    }
    

    
}

