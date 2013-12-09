<?php
/**
 * class UtilityController
 * 
 */
class App_UtilityController extends Zend_Controller_Action
{
	
    
	
    public function init(){
		
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
     * Action updateFacebookFriends
     * 
     */
	public function updateFacebookFriendsAction ()
    {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
			$idUser = trim($this->getParam('idUser'));
			if (isset($idUser) && $idUser > 0) {
				$user = new User_Model_User();
				$user_data = $user->getUserById($idUser);
				$idFacebook = $user_data['Facebook_id'];
				$facebookIds = "'0'";
				$friends = App_Util_Facebook::getFriendList($idFacebook);
				foreach ($friends AS $f) {
					$facebookIds .= ",'{$f['id']}'";
				}
				$user->saveFacebookFriends($idUser, $facebookIds);
				Zend_Debug::dump($friends);
			}else {
				echo 'Error';
			}
			
    }

    
}
?>
