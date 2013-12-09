<?php

class Site_Model_IniRegistro {

    private $registroDataTable;

    public function __construct() {
        $this->registroDataTable = new Site_Model_DbTable_IniRegistro();
    }
 
    public function newRegister($data){
        $data = array("reg_name"=>$data['cancha']
            , "reg_email"=>$data['email']
            , "reg_phone"=>$data['telefono']
            , "reg_mobile"=>$data['celular']
            , "reg_incharge"=>$data['encargado']
            , "reg_adress"=>$data['direccion']
            , "reg_latitude"=>''
            , "reg_longitude"=>'');
        return($this->registroDataTable->saveRegister($data));
    }
    
}

