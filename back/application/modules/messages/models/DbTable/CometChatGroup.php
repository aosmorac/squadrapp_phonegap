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
	
	public function getGroupInfo($gid){
		$where = "G.com_group_id = {$gid} ";
		$select = $this->select()
                ->from(
                        array("G"=>$this->_name) 
                        ,array(
                        	'group_id'=>'com_group_id', 
							'group_name'=>'com_group_name',
							'group_description'=>'com_group_description',  
							'group_owner'=>'com_group_owner_id',
                        )
                    )
                ->setIntegrityCheck(false)
                ->join(
                        array('UG' => 'userxgroup'), "UG.com_group_id = G.com_group_id"
                        ,array()
                    )
				->join(
                        array('U' => 'user'), "U.id_user = UG.user_id"
                        ,array(
							'user_id'=>'id_user', 
							'user_facebook_id'=>'Facebook_id', 
							'user_name'=>'use_name', 
							'user_first_name'=>'use_first_name', 
							'user_last_name'=>'use_last_name', 
							'user_location'=>'use_location_name', 
							'user_gener'=>'use_gener',  
							'user_available'=>'use_available', 
							'user_lastactivity'=>'lastactivity' 
						)
                    )
                ->where($where)
                ->order("U.use_first_name ASC")
            ;
         //Zend_Debug::dump($select.''); die;
         $row = $this->fetchAll($select);
         $gInfo = $row->toArray();
         return $gInfo;
	}
	
}
?>