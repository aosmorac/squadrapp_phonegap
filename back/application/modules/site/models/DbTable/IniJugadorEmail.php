<?php

class Site_Model_DbTable_IniJugadorEmail extends Zend_Db_Table_Abstract {

    protected $_name = "ini_jugador_email";

    public function __construct() {
        $this->_setAdapter('SITE');
    }
    
    public function saveRegister ($data)
    {
        return($this->getAdapter()->insert('ini_jugador_email', $data));
    }


}

