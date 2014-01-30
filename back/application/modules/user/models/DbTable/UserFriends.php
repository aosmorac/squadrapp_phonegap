<?php
class User_Model_DbTable_UserFriends extends Zend_Db_Table_Abstract {
		
	protected $_name = "user_friends";
	protected $_primary = 'use_fri_id';
	
	public function __construct() {
		$this->_setAdapter('APP');
	}	
	
	public function addContact($uid,$friend)
	{
	 $data=Array('friend1'=>$uid,'friend2'=>$friend);
	 $this->insert($data);
	}
}