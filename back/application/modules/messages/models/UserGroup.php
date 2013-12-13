<?php
class Messages_Model_UserGroup {
	
	public function __construct() {
		$this->registroDataTable = new Messages_Model_DbTable_UserxGroup();
	}
public function getUsersxGroup($gid, $timezone=-5)
{
	$users=$this->registroDataTable->getUsersxGroup($gid,$timezone);
	if(count($users)>0)
	{
	   return $users;
	}
}
}
