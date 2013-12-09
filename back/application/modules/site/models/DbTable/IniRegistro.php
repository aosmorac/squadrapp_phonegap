<?php

class Site_Model_DbTable_IniRegistro extends Zend_Db_Table_Abstract {

    protected $_name = "ini_registro";

    public function __construct() {
        $this->_setAdapter('SITE');
    }
    
    public function saveRegister($data)
    {
        //Zend_Debug::dump($data);
        return($this->getAdapter()->insert('ini_registro', $data));
    }


}

