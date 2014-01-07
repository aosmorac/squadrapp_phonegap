<?php
/**
 * class UserController
 * 
 */
class App_UserController extends Zend_Controller_Action
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

    
    
    /**
     * Action login-fcebook
     * 
     */
	public function loginFacebookAction ()
    {
            header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $vars = $this->_getAllParams();
            $user = json_decode($vars['user']);
            $userSquadrapp = array();
            if (isset($user->login) && $user->login == 1){
                $userInfo = array();
                if (isset($user->facebook_id)){ $userInfo['Facebook_id'] = $user->facebook_id; }
                if (isset($user->name)){ $userInfo['use_name'] = $user->name; }
                if (isset($user->first_name)){ $userInfo['use_first_name'] = $user->first_name; }
                if (isset($user->last_name)){ $userInfo['use_last_name'] = $user->last_name; }
                if (isset($user->facebook_link)){ $userInfo['Facebook_link'] = $user->facebook_link; }
                if (isset($user->facebook_username)){ $userInfo['Facebook_username'] = $user->facebook_username; }
                if (isset($user->hometown_id)){ $userInfo['use_hometown_id'] = $user->hometown_id; }
                if (isset($user->hometown)){ $userInfo['use_hometown_name'] = $user->hometown; }
                if (isset($user->location_id)){ $userInfo['use_location_id'] = $user->location_id; }
                if (isset($user->location) && trim($user->location)!=''){ 
                        $userInfo['use_location_name'] = $user->location; 
                }else{
                    $userInfo['use_location_name'] = 'Bogotá Colombia';
                }
                if (isset($user->gender)){ $userInfo['use_gener'] = $user->gender; }
                if (isset($user->email)){ $userInfo['use_email'] = $user->email; }
                if (isset($user->timezone)){ $userInfo['timezone'] = $user->timezone; }
                if (isset($user->locale)){ $userInfo['use_locale'] = $user->locale; }

                if($u = $this->user->loginFacebookMobile($userInfo)){
                    $userSquadrapp = $u;
                    $userSquadrapp['login'] = 1;
                }else {
                    $userSquadrapp['login'] = 0;
                }
            }            
            echo json_encode($userSquadrapp);
    }

	public function setUserOnlineAction ()
    {
            header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $vars = $this->_getAllParams(); 
			$user = new User_Model_User();
			$user->updateLastActivityMobile($vars['uid']);
	}

	
	
	public function getContactsAction ()
    {
            header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $vars = $this->_getAllParams(); 
			$user = new User_Model_User();
			$friends = $user->getUserFriendsMobile($vars['uid']);
            echo json_encode($friends);
	}

    
}
?>
