<?php

class Messages_Model_UserChat {

    private $registroDataTable;

    
    public function __construct() {
        $this->registroDataTable = new Messages_Model_DbTable_Cometchat();
    }
    
    public function getLastTalkersByUser($uid, $start=0, $ini = ''){
        $users = $this->registroDataTable->getLastTalkersByUser($uid, $start, $ini);
        if (count($users)>0){
            return $users;
        }else{
            return array();
        }
    }
	
	public function getLastTalkersByUserApp($uid, $start=0, $timezone=-5, $ini = ''){
        $users = $this->registroDataTable->getLastTalkersByUserApp($uid, $start, $timezone, $ini);
        if (count($users)>0){
            return $users;
        }else{
            return array();
        }
    }
	
	public function getNewTalkersByUserApp($uid, $nid=0, $timezone=-5, $ini = ''){
        $users = $this->registroDataTable->getNewTalkersByUserApp($uid, $nid, $timezone, $ini);
        if (count($users)>0){
            return $users;
        }else{
            return array();
        }
    }
    
    public function getMessagesChat($id_talker1=0, $id_talker2=0, $start=0, $lid=0){
        $messages = $this->registroDataTable->getMessagesChat($id_talker1, $id_talker2, $start, $lid);
        if (count($messages)>0){
            $this->registroDataTable->updateReadMessages($id_talker2, $id_talker1);
            return $messages;
        }else{
            return array();
        }
    }
	
	public function getMessagesChatApp($id_talker1=0, $id_talker2=0, $timezone=-5, $start=0, $lid=0){
        $messages = $this->registroDataTable->getMessagesChatApp($id_talker1, $id_talker2, $timezone, $start, $lid);
        if (count($messages)>0){
            //$this->registroDataTable->updateReadMessages($id_talker2, $id_talker1);
            return $messages;
        }else{
            return array();
        }
    } 
    public function getMessagesChatGroup($uid=0, $gid=0, $timezone=-5, $start=0, $lid=0)
    {
    	$messages = $this->registroDataTable->getMessagesChatGroup($uid, $gid, $timezone, $start, $lid);
		if (count($messages)>0){
            //$this->registroDataTable->updateReadMessages($id_talker2, $id_talker1);
            return $messages;
        }else{
            return array();
        }    	
    }

    public function getLastMessagesChat($id_talker1=0, $id_talker2=0, $lid=0){
        $messages = $this->registroDataTable->getLastMessagesChat($id_talker1, $id_talker2, $lid);
        if (App_User::getUserId() > 0){
            $user = new User_Model_DbTable_User();
            $data = array('lastactivity'=>time());
            $user->update($data, "id_user = ".App_User::getUserId());
        }
        if (count($messages)>0){
            $this->registroDataTable->updateReadMessages($id_talker2, $id_talker1);
            return $messages;
        }else{
            return array();
        }
    }
    
    public function saveMessage($from, $to, $message, $isgroup=0){
        $data = array('from'=>$from, 'to'=>$to, 'message'=>$message, 'read'=>'2', 'sent'=> time(), 'isgroup'=> $isgroup);
        $this->registroDataTable->insert($data);
    }
    
    public function totalUnreadMessages($uid){
        $messages = $this->registroDataTable->totalUnreadMessages($uid);
        if (isset($messages['unread']) && $messages['unread']>0){
            return $messages['unread'];
        }else{
            return 0;
        }
    }
	
	
	public function updateReadMessages($id_from, $id_to){
		$this->registroDataTable->updateReadMessages($id_from, $id_to);
	}
	
	
	public function createGroup($name="",$description="", $ownerid=5)
    {
		//Zend_Debug::dump($name, "Modelo");
    	$group = new Messages_Model_DbTable_CometChatGroup();
    	$id=$group->createGroup($name,$description, $ownerid);
    	return $id;
     }
	
	public function addUserByGroup($rows)
    {
		//Zend_Debug::dump($name, "Modelo");
    	$userxgroup = new Messages_Model_DbTable_UserxGroup();
		$userxgroup->addUserByGroup($rows);
     }
	
     public function removeChat($id_from,$id_to,$isgroup=0)
     {
     	$remchat = new Messages_Model_DbTable_Cometchat();
     	if ($remchat->removeChat($id_from,$id_to,$isgroup)){
     		return 1;
     	}else return 0;	
     	
     }
	 
	 public function getGroupInfo($gid){
	 	$group = new Messages_Model_DbTable_CometChatGroup();
		$gUsers = $group->getGroupInfo($gid);
		$gInfo = array('id'=>0, 'name'=>'', 'description'=>'', 'owner'=>0, 'max_users'=>0);
		if ( count($gUsers) > 0 ){
			$gInfo['max_users'] = count($gUsers);
			foreach ($gUsers AS $g) {
				$gInfo['id'] = $g['group_id'];
				if ( trim($g['group_name']) != ''){
					$gInfo['name'] = $g['group_name'];
				}else{
					if (trim($gInfo['name'])!=''){ $gInfo['name'] .= ', '; }
					$gInfo['name'] .= $g['user_first_name'];
				}
				$gInfo['description'] .= $g['group_description'];
				$gInfo['owner'] = $g['group_owner'];
			}
			$gInfo['users'] = $gUsers;
			return $gInfo;
		}else {
			return array();
		}
	 }
    
    
}