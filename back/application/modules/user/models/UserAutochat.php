<?php

class User_Model_UserAutochat {

    private $registroDataTable;

    public function __construct() {
        $this->registroDataTable = new User_Model_DbTable_UserAutochat();
    }
    
    public function getMessageByUser($user_id){
        $message = $this->registroDataTable->getMessageByUser($user_id);
        return $message;
    }
    
    public function updateSend($user_id, $message_id){
        $autochatxuser = new User_Model_DbTable_AutochatXUser();
        $data = array('aut_cha_id'=>$message_id, 'use_id'=>$user_id);
        $autochatxuser->insert($data);
    }
    

}