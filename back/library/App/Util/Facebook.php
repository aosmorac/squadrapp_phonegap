<?php

include_once '../library/Facebook/facebook.php';

class App_Util_Facebook {
	
    private static $config = array(
        	'appId' => '518581128197028',
		'secret' => 'e6b0435fa890007961b0e1975a1dc267',
                'cookie' => true
              );		
   
   //private static $access_token = 'CAACEdEose0cBAIZCtmTNcKfspCaMY4Ti2LMZAslcRFr36lk1BoDk7PAZAPkZBfPrDXfJ7ZCJniQvfGUlYJMrC63qNpDB4cnkd2JfrMX9r0Dp2pFYZB8bv9WK0LLtLkdqC8MQYXgoTHFOt09Qgl02G758EvP8plmZCgZD';

        
    /**
     * 
     * @return type
     */    
    public static function getUserId(){
        $facebook = new Facebook(self::$config);
        return $facebook->getUser();
    }
        

   /**
    * 
    * @param type $who
    * @param type $method
    * @return type
    */
    public static function getProfile($who = '/me', $method = 'GET'){
        $facebook = new Facebook(self::$config);
        $user_id = $facebook->getUser();
	if($user_id) {
            try {
                $user_profile = $facebook->api($who,$method);
                return $user_profile;
            } catch(FacebookApiException $e) {
            $login_url = $facebook->getLoginUrl();
            return array('error'=>'Exepcion', 'url_login'=>$login_url);
            }   
	} else {
            /*$login_url = $facebook->getLoginUrl();
            return array('error'=>'Sin Login', 'url_login'=>$login_url);*/
            return false;
	}
    }
    
    
    public static function getLoginUrl($redirect_uri = 'http://squadrapp.com', $scope = 'email,user_likes,publish_actions'){
        $facebook = new Facebook(self::$config);
        $params = array(
            'domain' => 'http://squadrapp.com',
            'scope' => $scope,
            'redirect_uri' => $redirect_uri
        );
        $loginUrl = $facebook->getLoginUrl($params);
        return $loginUrl;
    }
    
    
    
    /**
     * 
     * @param type $url
     * @return type
     */
    public static function getLogout($url = '/'){
        $facebook = new Facebook(self::$config);
        $params = array( 'next' => $url );
        return $facebook->getLogoutUrl($params);
    }
            
    
    
    /**
     * 
     * @param type $message
     * @param type $picture
     * @param type $link
     * @param type $name
     * @param type $caption
     * @param type $description
     */
    public static function publishFeed($message = 'Mensaje SquadrApp', $picture = 'http://squadrapp.com/images/facebook/logo.jpg', $link = 'http://squadrapp.com/', $name='SquadrApp.com', $caption='Tu vida deportiva a otro nivel', $description='Únete tu también. Encuentra una cancha y convoca a tus amigos para jugar.'){
        $facebook = new Facebook(self::$config);
        $req =  array(
            'message' => $message,
            'picture' => $picture,
            'link' => $link,
            'name' => $name,
            'caption' => $caption,
            'description' => $description
        );
        $res = $facebook->api('/me/feed', 'POST', $req);
    }
    
    
	/**
	 * 
	 * @param String $fid
	 */
    public static function getFriendList($fid='0'){
        $facebook = new Facebook(self::$config);
        if ($fid=='0')
            $user_id = self::getUserId();
        else 
            $user_id = $fid;
        $friends = $facebook->api("/{$user_id}/friends", 'GET');
        return $friends['data'];
    }
                 
	
	
}
