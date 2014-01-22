<?php
/**
 * class UserController
 * 
 */
class App_ChatController extends Zend_Controller_Action
{
	
    private $user;
	
    public function init(){
        $this->user = new User_Model_User();
    }
    
    /**
     * Action index
     * 
     * Action de inicio por defecto
     */
    public function indexAction ()
    {
    }


	
	public function getLastTalkersAction(){
        header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $talkers = $userChat->getLastTalkersByUserApp($vars['uid'], $vars['start'], $vars['timezone']);
        echo json_encode($talkers);
    }
	
	public function getNewTalkersAction(){
        header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $talkers = $userChat->getNewTalkersByUserApp($vars['uid'], $vars['nid'], $vars['timezone']);
        echo json_encode($talkers);
    }
	
	public function loadChatAction(){
        header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $messages = $userChat->getMessagesChatApp($vars['uid'], $vars['fid'], $vars['timezone'], $vars['start']);
        echo json_encode($messages);
    }
	
	public function loadChatGroupAction(){
        header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $messages = $userChat->getMessagesChatGroup($vars['uid'], $vars['gid'], $vars['timezone'], $vars['start']);
        echo json_encode($messages);
    }
	
	public function getNewMessagesAction(){
        header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $messages = $userChat->getMessagesChatApp($vars['uid'], $vars['fid'], $vars['timezone'], 0, $vars['nid']);
        echo json_encode($messages);
    }
	
	public function getNewMessagesGroupAction(){
        header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $messages = $userChat->getMessagesChatGroup($vars['uid'], $vars['fid'], $vars['timezone'], 0, $vars['nid']);
        echo json_encode($messages);
    }
	
	public function saveMessageAction(){
		header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
		if ( !isset($vars['isGroup']) ) {
			$vars['isGroup'] = 0;
		}
        $userChat = new Messages_Model_UserChat();
        $userChat->saveMessage($vars['me'], $vars['to'], trim($vars['msg']), $vars['isGroup']);
    }
	
	public function updateReadMessagesAction(){
		header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
		$userChat->updateReadMessages($vars['me'], $vars['to']);
	}
    
public function createGroupAction()
{
	header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);
	$vars = $this->_getAllParams();
	//Zend_Debug::dump($vars, "Controlador"); 
	$group = new Messages_Model_UserChat();
	if (!isset($vars['name'])){
		$vars['name'] = '';
	}
	if (!isset($vars['description'])){
		$vars['description'] = '';
	}
	if (!isset($vars['ownerid'])){
		$vars['ownerid'] = 0;
	}
	$com_group_id=$group->createGroup($vars['name'],$vars['description'],$vars['ownerid']);
    echo json_encode(array("group_id"=>$com_group_id));
}
public function getUserxGroupAction()
{
	header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);
	$vars = $this->_getAllParams();
	//Zend_Debug::dump($vars, "Controlador");
	$usersgroup = new Messages_Model_UserGroup();
	$users=$usersgroup->getUsersxGroup($vars['gid'], $vars['timezone']);
	$userxgroup = array('gid'=>$vars['gid'],'data'=>$users);
	echo json_encode($userxgroup);
 
}
public function  removeChatAction()
{
	header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
	$this->_helper->layout()->disableLayout();
	$this->_helper->viewRenderer->setNoRender(true);
	$vars = $this->_getAllParams();
	//Zend_Debug::dump($vars, "Controlador");
	$remchat = new Messages_Model_UserChat();
	$result=$remchat->removeChat($vars['id_from'], $vars['id_to'],$vars['isgroup']);
	echo json_encode(array('success'=>$result));
}

	
/*
 * 
 */
	public function  newChatAction(){
		header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$vars = $this->_getAllParams();		
		$ids = explode(',', $vars['to']);
		if (count($ids) == 1) {
			$userChat = new Messages_Model_UserChat();
        	$userChat->saveMessage($vars['me'], $ids[0], trim($vars['msg']));
			echo 0;
		}elseif (count($ids) > 1) {
			$group = new Messages_Model_UserChat();
			$group_id = $group->createGroup('', '', $vars['me']);
			$userxgroup_rows = Array();
			$userxgroup_rows[] = Array('user_id'=>$vars['me'], 'com_group_id'=>$group_id);
			foreach($ids as $uid){
				$userxgroup_rows[] = Array('user_id'=>$uid, 'com_group_id'=>$group_id);
			}
			$group->addUserByGroup($userxgroup_rows);
			$group->saveMessage($vars['me'], $group_id, trim($vars['msg']), 1);
			echo $group_id;
		} 
	}
	
	/**
	 * 
	 */
	 public function getGroupInfoAction(){
	 	header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$vars = $this->_getAllParams();		
		if ( isset($vars['gid']) && $vars['gid'] >0 ){
			$gid = $vars['gid'];
			$group = new Messages_Model_UserChat();
			$gInfo = $group->getGroupInfo($gid);
			echo json_encode($gInfo);
		}else{
			echo json_encode(array('error'=>'Error'));
		}
	 }


}
?>
