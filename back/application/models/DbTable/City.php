<?php

class Model_DbTable_City extends Zend_Db_Table_Abstract {

    protected $_name = "city";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    public function getCities($country, $cit='b'){
        if (trim($cit)=='') $city='b';
        $select = $this->select()
            ->from($this->_name,array('ID', 'Name', 'value'=>'Name'))
            ->where("CountryCode = '{$country}' AND Name LIKE '{$cit}%' AND ID IN (2257)")//ID inicialmente solo Bogota
            ->order('Name ASC')
            ->limit(10, 0);
        return $this->fetchAll($select);
    }
    
    public function getCityByName($city){
        $select = $this->select()
            ->from($this->_name,array('ID', 'Name'))
            ->where("Name LIKE '{$city}' AND ID IN (2257)")//ID inicialmente solo Bogota
            ->order('Name ASC')
            ->limit(10, 0);
        return $this->fetchAll($select);
    }
    
}

