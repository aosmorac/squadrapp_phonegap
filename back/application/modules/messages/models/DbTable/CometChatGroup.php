<?php
class Messages_Model_DbTable_CometChatGroup extends Zend_Db_Table_Abstract {

	protected $_name = "cometchat_group";
	protected $_primary = 'com_group_id';
	
	public function __construct() {
		$this->_setAdapter('APP');
	}
	
	public function createGroup($name, $description, $ownerid)
	{
		//Zend_Debug::dump($name, "DB_Table");
		$data= array('com_group_name'=>$name,'com_group_description'=>$description,'com_group_owner_id'=>$ownerid);
		$id=$this->insert($data);
		return $id;
	}
}
?>