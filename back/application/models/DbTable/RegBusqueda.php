<?php

class Model_DbTable_RegBusqueda extends Zend_Db_Table_Abstract {

    protected $_name = "ini_busqueda";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
}

