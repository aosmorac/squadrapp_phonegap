<?php
/**
 * class UserController
 * 
 * Clase de inicio por defecto del sistema
 */
class User_UserController extends Zend_Controller_Action
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
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            if ($profile = App_Util_Facebook::getProfile()){
                $userInfo = array();
                if (isset($profile['id'])){ $userInfo['Facebook_id'] = $profile['id']; }
                if (isset($profile['name'])){ $userInfo['use_name'] = $profile['name']; }
                if (isset($profile['first_name'])){ $userInfo['use_first_name'] = $profile['first_name']; }
                if (isset($profile['last_name'])){ $userInfo['use_last_name'] = $profile['last_name']; }
                if (isset($profile['link'])){ $userInfo['Facebook_link'] = $profile['link']; }
                if (isset($profile['username'])){ $userInfo['Facebook_username'] = $profile['username']; }
                if (isset($profile['hometown']['id'])){ $userInfo['use_hometown_id'] = $profile['hometown']['id']; }
                if (isset($profile['hometown']['name'])){ $userInfo['use_hometown_name'] = $profile['hometown']['name']; }
                if (isset($profile['location']['id'])){ $userInfo['use_location_id'] = $profile['location']['id']; }
                if (isset($profile['location']['name']) && trim($profile['location']['name'])!=''){ 
                        $userInfo['use_location_name'] = $profile['location']['name']; 
                }else{
                    $userInfo['use_location_name'] = 'Bogotá Colombia';
                }
                if (isset($profile['gender'])){ $userInfo['use_gener'] = $profile['gender']; }
                if (isset($profile['email'])){ $userInfo['use_email'] = $profile['email']; }
                if (isset($profile['locale'])){ $userInfo['use_locale'] = $profile['locale']; }
 
                $this->user->loginFacebook($userInfo);
            }
    }
    
    /**
     * Action login-fcebook-mobile
     * 
     */
	public function loginFacebookMobileAction ()
    {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            header('P3P: CP="NOI ADM DEV COM NAV OUR STP"');
            //Zend_Debug::dump(App_Util_Facebook::getProfile()); die;
            if ($profile = App_Util_Facebook::getProfile()){
                $userInfo = array();
                if (isset($profile['id'])){ $userInfo['Facebook_id'] = $profile['id']; }
                if (isset($profile['name'])){ $userInfo['use_name'] = $profile['name']; }
                if (isset($profile['first_name'])){ $userInfo['use_first_name'] = $profile['first_name']; }
                if (isset($profile['last_name'])){ $userInfo['use_last_name'] = $profile['last_name']; }
                if (isset($profile['link'])){ $userInfo['Facebook_link'] = $profile['link']; }
                if (isset($profile['username'])){ $userInfo['Facebook_username'] = $profile['username']; }
                if (isset($profile['hometown']['id'])){ $userInfo['use_hometown_id'] = $profile['hometown']['id']; }
                if (isset($profile['hometown']['name'])){ $userInfo['use_hometown_name'] = $profile['hometown']['name']; }
                if (isset($profile['location']['id'])){ $userInfo['use_location_id'] = $profile['location']['id']; }
                if (isset($profile['location']['name']) && trim($profile['location']['name'])!=''){ 
                        $userInfo['use_location_name'] = $profile['location']['name']; 
                }else{
                    $userInfo['use_location_name'] = 'Bogotá Colombia';
                }
                if (isset($profile['gender'])){ $userInfo['use_gener'] = $profile['gender']; }
                if (isset($profile['email'])){ $userInfo['use_email'] = $profile['email']; }
                if (isset($profile['locale'])){ $userInfo['use_loacale'] = $profile['locale']; }
                //Zend_Debug::dump($userInfo); die;
                $this->user->loginFacebookMobile($userInfo);
                $this->_redirect('http://squadrapp.com/site/site/message-user');
            }else {
                $this->_redirect('http://squadrapp.com/site/site/mobile-index');
            }die;
    }
      
    
    
    /**
     * Action logout
     * 
     */
	public function logoutAction ()
    {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $user_model = new User_Model_User();
            $user_model->updateLastActivity();
            $auth = Zend_Auth::getInstance ();
            // Borrar información de la session
            $auth->clearIdentity ();
            Zend_Session::forgetMe ();
            Zend_Session::destroy ( true );

            //App_Util_Facebook::getLogout();
            $this->_redirect ('/');
    }
    
    
    /**
     * 
     */
    public function myBookingAction(){
        $this->_helper->layout()->disableLayout();
        $booking = $this->user->getCurrentBooking(App_User::getUserId(), 5);
        //Zend_Debug::dump($booking); die;
        $this->view->booking = $booking;
    }
    
    
    /**
     * 
     */
    public function loginShareAction(){
        $this->_helper->layout()->disableLayout();
        $vars = $this->_getAllParams();
        $friend = $this->user->getUserById($vars['user_id']);
        //Zend_Debug::dump($friend);
        $this->view->friend = $friend;
    }
    
    
    
    
    public function profileAction(){
        $vars = $this->_getAllParams();
        $user_model = new User_Model_User();
        
        if (isset($vars['uid']) && $vars['uid']>0){
            
            $this->_redirect('/user/user/profile');            
            
        }elseif (!App_User::isLogged()){
            $this->_redirect('/');
        }else{
            //  Usuario por id         
            
            //Zend_Debug::dump(App_User::getLocation());
            
            $user = $user_model->getUserById(App_User::getUserId()); //  Instancia
            
            //$friends_ids = $user_model->getFriendsId($user['Facebook_id']);   //  Solo funciona a usuario logueado en facebook
            //$this->view->friends_ids = $friends_ids;
            
            $sports = $user_model->getSportsByUser(App_User::getUserId());
            
            
            if (trim($user['use_location_name']) == '')
                    $user['use_location_name'] = 'Bogotá, Colombia';
            $user['use_location_name'] = str_replace('í', '', $user['use_location_name']);
            $this->view->title = $user['use_name']." - SquadrApp.com";              
            $this->view->user = $user;
        }
    }
    
    
    public function profileNewAction(){
        $vars = $this->_getAllParams();
        $user_model = new User_Model_User();
    	$this->_helper->layout->setLayout('application-new');
        
        $user_model->loginFacebookTest(5);
        
        if (isset($vars['uid']) && $vars['uid']>0){
            
            $this->_redirect('/user/user/profile-new');            
            
        }elseif (!App_User::isLogged()){
            //$this->_redirect('/');
        }else{
            //  Usuario por id         
            
            //Zend_Debug::dump(App_User::getLocation());
            
            $user = $user_model->getUserById(App_User::getUserId()); //  Instancia
            
            //$friends_ids = $user_model->getFriendsId($user['Facebook_id']);   //  Solo funciona a usuario logueado en facebook
            //$this->view->friends_ids = $friends_ids;
            
            $sports = $user_model->getSportsByUser(App_User::getUserId());
            
            
            if (trim($user['use_location_name']) == '')
                    $user['use_location_name'] = 'Bogotá, Colombia';
            $user['use_location_name'] = str_replace('í', '', $user['use_location_name']);
            $this->view->title = $user['use_name']." - SquadrApp.com";              
            $this->view->user = $user;
        }

    }
    
    
    public function getLocalPlayersAction(){
        //Zend_Debug::dump(App_User::getLocation(),'Cotroller');
        $vars = $this->_getAllParams(); // $uid = 0, $ini = '', $id_sport = 1, $locatión = 'Bogotá, Colombia'
        if (!isset($vars['ini'])){ $vars['ini']=''; }
        $this->_helper->layout()->disableLayout();
        $user_model = new User_Model_User();
        $local_players = $user_model->getLocalPlayers(App_User::getLocation(),$vars['uid'], $vars['start'], trim($vars['ini']));
        $totalPlayers = $user_model->getTotalLocalPlayers(App_User::getLocation(),$vars['uid'], $vars['start'], trim($vars['ini']));
            $this->view->totalPlayers = $totalPlayers['total'];
            $this->view->players = $local_players;
        if ($totalPlayers['total']==0 && $vars['start']==0) {
            $this->render("invite"); 
        }
    }
    
    public function getLocalFriendsAction(){
        //Zend_Debug::dump(App_User::getLocation(),'Cotroller');
        $vars = $this->_getAllParams(); // $uid = 0, $ini = '', $id_sport = 1, $locatión = 'Bogotá, Colombia'
        if (!isset($vars['ini'])){ $vars['ini']=''; }
        $this->_helper->layout()->disableLayout();
        $user_model = new User_Model_User();
        $local_players = $user_model->getLocalFriends(App_User::getLocation(),$vars['uid'], App_User::getFriendsIds('all'), $vars['start'], trim($vars['ini']));
        $totalPlayers = $user_model->getTotalLocalFriends(App_User::getLocation(),$vars['uid'], App_User::getFriendsIds('all'), $vars['start'], trim($vars['ini']));
            $this->view->totalPlayers = $totalPlayers['total'];
            $this->view->players = $local_players;
        if ($totalPlayers['total']==0 && $vars['start']==0) {
            $this->render("invite"); 
        }        
    }
    
    public function getPlayersNologinAction(){
        //Zend_Debug::dump(App_User::getLocation(),'Cotroller');        
        $this->_helper->layout()->disableLayout();
        
        $user_model = new User_Model_User();
        $local_players = $user_model->getUsersAll(App_User::getUserId());
        $this->view->players = $local_players;
    }
    
    
    public function listSportsAction(){
        $this->_helper->layout()->disableLayout();
        $vars = $this->_getAllParams();
        $user_model = new User_Model_User();
        $sports = $user_model->getSportsByUser($vars['uid']);
        $this->view->sports = $sports;
    }
    
    
    public function saveUserSportsAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $user_model = new User_Model_User();
        $user_model->saveSportsByUser(App_User::getUserId(), $vars['id_sports']);
    }
    
    
    
    public function searchLocationAction(){
        $this->_helper->layout()->disableLayout();
        $vars = $this->_getAllParams();
    }
    

    public function saveUserLocationAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $data = array(
            'use_loc_street_number' => $vars['use_street_number'],
            'use_loc_route' => $vars['use_route'],
            'use_loc_locality' => $vars['use_locality'],
            'use_loc_city' => $vars['use_city'],
            'use_loc_administrative_area_level_2' => $vars['use_administrative_area_level_2'],
            'use_loc_administrative_area_level_1' => $vars['use_administrative_area_level_1'],
            'use_loc_country' => $vars['use_country'],
            'use_loc_postal_code' => $vars['use_postal_code'],
            'use_loc_formatted_address' => $vars['use_formatted_address'],
            'use_loc_lat' => $vars['use_lat'],
            'use_loc_lng' => $vars['use_lng']
        );
        $user_model = new User_Model_User();
        $user_model->saveLocationByUser(App_User::getUserId(), $data);
    }
    
    public function changeUserLocationAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $data = array(
            'use_loc_street_number' => $vars['use_street_number'],
            'use_loc_route' => $vars['use_route'],
            'use_loc_locality' => $vars['use_locality'],
            'use_loc_city' => $vars['use_city'],
            'use_loc_administrative_area_level_2' => $vars['use_administrative_area_level_2'],
            'use_loc_administrative_area_level_1' => $vars['use_administrative_area_level_1'],
            'use_loc_country' => $vars['use_country'],
            'use_loc_postal_code' => $vars['use_postal_code'],
            'use_loc_formatted_address' => $vars['use_formatted_address'],
            'use_loc_lat' => $vars['use_lat'],
            'use_loc_lng' => $vars['use_lng']
        );
        $user_model = new User_Model_User();
        $user_model->changeLocationByUser(App_User::getUserId(), $data);
        $user_model->saveLocationByUser(App_User::getUserId(), $data);
    }
    
    public function updateAutoChatAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $user_model = new User_Model_User();
        $user_model->updateAutochat(App_User::getUserId(), $vars['mid']);
        unset($_SESSION['autochat']);
    }
    
    
    
    
    
    public function setTimeZoneAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $_SESSION['timezone'] = $vars['hours'];
    }
      

    
}
?>
