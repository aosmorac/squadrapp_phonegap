<?php

class Site_Model_IniJugadorEmail {

    private $registroDataTable;

    public function __construct() {
        $this->registroDataTable = new Site_Model_DbTable_IniJugadorEmail();
    }
 
    public function newRegister($data){
        $data = array("jug_ema_email"=>$data['email']);
        return($this->registroDataTable->saveRegister($data));
    }
    
}

