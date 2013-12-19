<?php
class Messages_Model_DbTable_UserxGroup extends Zend_Db_Table_Abstract {

	protected $_name = "userxgroup";
	protected $_primary = 'user_id';

	public function __construct() {
		$this->_setAdapter('APP');
	}
	
	public function addUserByGroup($rows){
		foreach($rows as $r){
			$this->insert($r);
		}
	}
	
	public function getUsersxGroup($gid,$timezone=-5)
	{
		$where="UG.com_group_id = {$gid}";
		$select= $this->select()
		->from(
				array('U'=>'user')
				,array('id_user'=>'id_user'
				      ,'Facebook_id'=>'Facebook_id'
				      ,'use_name'=>'use_name'
				      ,'use_first_name'=>'use_first_name'
                      ,'use_last_name'=>'use_last_name'
				      ,'Facebook_link'=>'Facebook_link' 
				      ,'Facebook_username'=>'Facebook_username'
                      ,'use_hometown_id'=>'use_hometown_id'
				      ,'use_hometown_name'=>'use_hometown_name'
				      ,'use_location_id'=>'use_location_id'
                      ,'use_location_name'=>'use_location_name'
				      ,'use_location_coordinates'=>'use_location_coordinates'
                      ,'use_gener'=>'use_gener'
				     ,'use_email'=>'use_email'
				     ,'use_locale'=>'use_locale'
				     ,'use_visit'=>'use_visit'
                      ,'use_date'=>new Zend_Db_Expr("(U.use_date + INTERVAL ".$timezone." HOUR)")
                      ,'lastactivity'=>'lastactivity'
					,'online' => new Zend_Db_Expr("IF(((UNIX_TIMESTAMP()-U.lastactivity) < 400 ), 1, 0)")
					 )
				 ) 
				      ->setIntegrityCheck(false)
                      ->joinInner(
                      		array('UG'=>'userxgroup'),"UG.user_id=U.id_user"
                      		,array('gid'=>'com_group_id')
                      		)
							
                   ->where($where);
				
				//Zend_Debug::dump($select.''); die;
				$row = $this->fetchAll($select);
				$users= $row->toArray();
				return $users;
					
	}
	
}
