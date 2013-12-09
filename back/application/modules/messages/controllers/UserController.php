<?php
/**
 * class UserController
 * 
 * CMensajes directos sincronizados con el chat
 */
class Messages_UserController extends Zend_Controller_Action
{
	
		/**
	 * @var Zend_Config_Ini $textGlobal
	 * @var Zend_Config_Ini $textModule
	 */
	private $textGlobal;
	private $textModule;
	
    public function init(){
    	$this->textGlobal = App_Util_Language::getTextLanguage();
        $this->textModule = App_Util_Language::getTextLanguage($this->getRequest()->getModuleName()); 
        $this->view->placeholder("title")->set($this->textModule->index->title);
		$this->view->title = "Canchas | ".$this->textModule->index->label;
        $this->view->headTitle($this->view->title);
		$this->_helper->layout->setLayout('chat');
        if (!App_User::isLogged()){
            $this->_redirect('/');
        }
    }
    
    /**
     * Action index
     * 
     * Action de inicio por defecto
     */
    public function indexAction ()
    {
        $vars = $this->_getAllParams();
        $user = array('user_id'=>0, 'user_name'=>'', 'user_status'=>0);
        if (isset($vars['uid']) && $vars['uid']>0){
            $user_model = new User_Model_User();
            $u = $user_model->getUserById($vars['uid']);
            $user['user_id'] = $u['id_user'];
            $user['user_name'] = $u['use_name'];
        }
        $this->view->chatWith = $user;
    }
    
    public function loadTalkersAction(){
        $this->_helper->layout()->disableLayout();
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $talkers = $userChat->getLastTalkersByUser(App_User::getUserId(), $vars['start'], trim($vars['ini']));
        $this->view->talkers = $talkers;
        //Zend_Debug::dump($talkers); die;
    }
    
    public function getLastTalkersAction(){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $talkers = $userChat->getLastTalkersByUser(App_User::getUserId(), $vars['start'], trim($vars['ini']));
        echo json_encode($talkers);
    }
    
    public function loadChatAction(){
        $this->_helper->layout()->disableLayout ();
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $messages = $userChat->getMessagesChat(App_User::getUserId(), $vars['fid'], $vars['start']);
        $this->view->messages = $messages;
    }
    
    public function saveMessageAction($to = 0, $message = ''){
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $userChat->saveMessage(App_User::getUserId(), $vars['to'], trim($vars['msg']));
    }
    
    public function loadLastMessagesAction(){
        $this->_helper->layout()->disableLayout ();
        $vars = $this->_getAllParams();
        $userChat = new Messages_Model_UserChat();
        $messages = $userChat->getLastMessagesChat(App_User::getUserId(), $vars['fid'], $vars['lid']);
        //Zend_Debug::dump($messages); die;
        $this->view->messages = $messages;
        $this->render("loadChat");
    }
    
    public function playerSearchAction ()
        {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $ini = $this->getParam('term');
            $user = new User_Model_User();
            $users = $user->getPlayers(App_User::getUserId(), $ini, 5);
            echo json_encode($users);
        }
    
        public function totalUnreadMessagesAction(){
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $userChat = new Messages_Model_UserChat();
            echo $userChat->totalUnreadMessages(App_User::getUserId());
        }

    
}
?>
