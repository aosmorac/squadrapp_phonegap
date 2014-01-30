<?php

class User_Model_UserFriends {

    private $registroDataTable;

    
    public function __construct() {
        $this->registroDataTable = new User_Model_DbTable_UserFriends();
    }
	public function addContacts($uid,$friend)
    {
		//Zend_Debug::dump($name, "Modelo");
    	$userfriends = new User_Model_DbTable_UserFriends();
		$userfriends->addContact($uid,$friend);
			
    }
	
}