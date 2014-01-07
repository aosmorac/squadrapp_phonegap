<?php

class User_Model_User {

    private $registroDataTable;

    public function __construct() {
        $this->registroDataTable = new User_Model_DbTable_User();
    }
    
    public function loginFacebookTest($uid){
        $user = $this->registroDataTable->getUserById($uid);
            $user_location = array(
                            "use_loc_street_number"=>''
                            ,"use_loc_route"=>''
                            ,"use_loc_locality"=>''
                            ,"use_loc_city"=>''
                            ,"use_loc_administrative_area_level_2"=>''
                            ,"use_loc_administrative_area_level_1"=>''
                            ,"use_loc_country"=>''
                            ,"use_loc_postal_code"=>''
                            ,"use_loc_formatted_address"=>''
                            ,"use_location_name"=>$user['use_location_name']
                            ,"use_loc_lat"=>''
                            ,"use_loc_lng"=>''
            );
            $_SESSION['user_location'] = $user_location;
            $_SESSION['userid'] = $user['id_user'];
            $auth = Zend_Auth::getInstance();
            $user["friends"] = array();
            $user["friends_ids"] = "'-1'";
            $user["visit"] = 1;
            $auth->getStorage()->write((object) $user);
        }
    
        
    
    public function loginFacebook($infoUser){
        $user = $this->registroDataTable->getUserFacebook($infoUser['Facebook_id']);
        $friends_list = App_Util_Facebook::getFriendList();
        $sesion = new Zend_Session_Namespace();
        $sesion->register = 0;
        $auth = Zend_Auth::getInstance();
        if ($user) {
            $user["friends"] = array();
            $user["friends_ids"] = "'-1'";
            foreach ($friends_list as $f) {
                $user['friends'][$f['id']] = $f['name'];
                $user['friends_ids'] .= ",'{$f['id']}'";
            }
            $user["visit"] = 1;
            $auth->getStorage()->write((object) $user);
            $user_location = array(
                            "use_loc_street_number"=>''
                            ,"use_loc_route"=>''
                            ,"use_loc_locality"=>''
                            ,"use_loc_city"=>''
                            ,"use_loc_administrative_area_level_2"=>''
                            ,"use_loc_administrative_area_level_1"=>''
                            ,"use_loc_country"=>''
                            ,"use_loc_postal_code"=>''
                            ,"use_loc_formatted_address"=>''
                            ,"use_location_name"=>$user['use_location_name']
                            ,"use_loc_lat"=>''
                            ,"use_loc_lng"=>''
            );
            $_SESSION['user_location'] = $user_location;
            $_SESSION['userid'] = $user['id_user'];
            $autochat = self::getMessageAutochat($user['id_user']);
            if (count($autochat)>1){
                $_SESSION['autochat'] = $autochat;
            }
        }else {
            $user = $this->registroDataTable->saveUserFacebook($infoUser);
            $user["friends"] = array();
            $user["friends_ids"] = "'-1'";
            foreach ($friends_list as $f) {
                $user['friends'][$f['id']] = $f['name'];
                $user['friends_ids'] .= ",'{$f['id']}'";
            }
            if ($user) {
                App_Util_Mail::mail('team@squadrapp.com', 
                                  array("{$user["use_name"]}"=>"{$user["use_email"]}"),// EL CAMBIO DE DESTINATARIO ES EN ESTA LINEA.
                                  "Bienvenido a SquadrApp",
                                  '<html>
                                    <head>
                                    <title>Bienvenido(a) a SquadrApp</title>
                                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                                    </head>
                                    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
                                    <table style="margin:0 auto; font-family:Tahoma, Geneva, sans-serif;" width="680" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                    <td height="57" bgcolor="#000000" align="left"><a href="http://squadrapp.com/"><img border="0" src="http://squadrapp.com/emails/bienvenida/logo.png" alt=""></a></td>
                                            </tr>
                                            <tr>
                                                    <td height="140" align="center" style="font-size:30px; color:#444; border-bottom:#CCC 1px solid;">Bienvenido a SquadrApp</td>
                                            </tr>
                                            </tr>
                                            <tr bgcolor="#f3f3f4">
                                                <td bgcolor="#f3f3f4" style="padding:30px 80px 0 80px; color:#666; font-size:20px;  font-weight:lighter;">
                                                    <p><strong>En esta versi&oacute;n Beta podr&aacute;s:</strong></p>
                                                <p>- Encontrar las primeras sedes deportivas en Bogot&aacute; que se han unido a SquadrApp.<br/>&iexcl;Pronto ver&aacute;s m&aacute;s!</p>
                                                <p style="padding-bottom:30px;">- Contactar a la cancha que te gusta, y planear tu pr&oacute;ximo juego con ellos.</p>
                                                <p style="border-bottom:#CCC 1px solid; padding-bottom:30px;">- Encontrar amigos y otros jugadores locales con qui&eacute;n juegar.</p>
                                            </td>
                                            </tr>
                                            <tr bgcolor="#f3f3f4">
                                                    <td bgcolor="#f3f3f4" style="padding:50px 80px 20px 80px; color:#333; font-size:18px; border-bottom:#CCC 1px solid;">
                                            <p style="color:#6396cd; margin-bottom:50px; font-size:22px; font-weight:lighter;" align="justify">Te comunicaremos a medida que agreguemos nuevas funcionalidades que lleven tu vida deportiva a otro nivel</p>
                                            <p style="font-size:12px;" align="center">Tu opini&oacute;n es importante, ingresa a <a style="color:#333;" href="http://squadrapp.com/">www.SquadrApp.com</a> y d&eacute;janos saber tus comentarios.</p>
                                            </td>
                                            </tr>
                                    </table>
                                    </body>
                                    </html>', 
                                  "SquadrApp"      
                                 );//END App_Util_Mail::mail*/
                App_Util_Facebook::publishFeed('Me acabo de unir a SquadrApp');
                $user["visit"] = 0;
                $sesion->register = 1;
                $auth->getStorage()->write((object) $user);
                $user_location = array(
                            "use_loc_street_number"=>''
                            ,"use_loc_route"=>''
                            ,"use_loc_locality"=>''
                            ,"use_loc_city"=>''
                            ,"use_loc_administrative_area_level_2"=>''
                            ,"use_loc_administrative_area_level_1"=>''
                            ,"use_loc_country"=>''
                            ,"use_loc_postal_code"=>''
                            ,"use_loc_formatted_address"=>''
                            ,"use_location_name"=>$user['use_location_name']
                            ,"use_loc_lat"=>''
                            ,"use_loc_lng"=>''
            );
            $_SESSION['user_location'] = $user_location;
            $_SESSION['userid'] = $user['id_user'];
            $autochat = self::getMessageAutochat($user['id_user']);
            if (count($autochat)>1){
                $_SESSION['autochat'] = $autochat;
            }
            }            
        }die;
    }
    

    public function loginFacebookMobile($infoUser){
        $user = $this->registroDataTable->getUserFacebook($infoUser['Facebook_id']);
        if ($user) {
            return $user;
        }else {
            $user = $this->registroDataTable->saveUserFacebook($infoUser);
            if ($user) {
                App_Util_Mail::mail('team@squadrapp.com', 
                                  array("{$user["use_name"]}"=>"{$user["use_email"]}"),// EL CAMBIO DE DESTINATARIO ES EN ESTA LINEA.
                                  "Bienvenido a SquadrApp",
                                  '<html>
                                    <head>
                                    <title>Bienvenido(a) a SquadrApp</title>
                                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                                    </head>
                                    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
                                    <table style="margin:0 auto; font-family:Tahoma, Geneva, sans-serif;" width="680" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                    <td height="57" bgcolor="#000000" align="left"><a href="http://squadrapp.com/"><img border="0" src="http://squadrapp.com/emails/bienvenida/logo.png" alt=""></a></td>
                                            </tr>
                                            <tr>
                                                    <td height="140" align="center" style="font-size:30px; color:#444; border-bottom:#CCC 1px solid;">Bienvenido a SquadrApp</td>
                                            </tr>
                                            </tr>
                                            <tr bgcolor="#f3f3f4">
                                                <td bgcolor="#f3f3f4" style="padding:30px 80px 0 80px; color:#666; font-size:20px;  font-weight:lighter;">
                                                    <p><strong>En esta versi&oacute;n Beta podr&aacute;s:</strong></p>
                                                <p>- Encontrar las primeras sedes deportivas en Bogot&aacute; que se han unido a SquadrApp.<br/>&iexcl;Pronto ver&aacute;s m&aacute;s!</p>
                                                <p style="padding-bottom:30px;">- Contactar a la cancha que te gusta, y planear tu pr&oacute;ximo juego con ellos.</p>
                                                <p style="border-bottom:#CCC 1px solid; padding-bottom:30px;">- Encontrar amigos y otros jugadores locales con qui&eacute;n juegar.</p>
                                            </td>
                                            </tr>
                                            <tr bgcolor="#f3f3f4">
                                                    <td bgcolor="#f3f3f4" style="padding:50px 80px 20px 80px; color:#333; font-size:18px; border-bottom:#CCC 1px solid;">
                                            <p style="color:#6396cd; margin-bottom:50px; font-size:22px; font-weight:lighter;" align="justify">Te comunicaremos a medida que agreguemos nuevas funcionalidades que lleven tu vida deportiva a otro nivel</p>
                                            <p style="font-size:12px;" align="center">Tu opini&oacute;n es importante, ingresa a <a style="color:#333;" href="http://squadrapp.com/">www.SquadrApp.com</a> y d&eacute;janos saber tus comentarios.</p>
                                            </td>
                                            </tr>
                                    </table>
                                    </body>
                                    </html>', 
                                  "SquadrApp"      
                                 );//END App_Util_Mail::mail*/
                //App_Util_Facebook::publishFeed('Me acabo de unir a SquadrApp');
                $user["visit"] = 0;
                return $user;
            }            
        }
   }
    
    
    public function getUsersById($ids, $order = false){
        return $this->registroDataTable->getUsersById($ids, $order);
    }
    
    public function getUserById($id){
        return $this->registroDataTable->getUserById($id);
    }
    
    public function getUsersFriendsById($ids, $order = false){
        return $this->registroDataTable->getUsersByIdsFids($ids, App_User::getFriendsFacebookIds(),$order);
    }
    
    public function getUsersFriends(){
        return $this->registroDataTable->getUsersByFacebookids(App_User::getFriendsFacebookIds());
    }
    
    /**
     * 
     * @param type $ini
     * @param type $id_sport
     * @param type $location
     * @return type
     */
    public function getLocalPlayers($location=0, $uid=0, $start=0, $ini = '', $id_sport = 1){
        return $this->registroDataTable->getUsersLS($location, $uid, $start, $ini, $id_sport);
    }
    public function getUsersAll($uid=0, $limit=6){
        return $this->registroDataTable->getUsersAll($uid, $limit);
    }
    
    public function getTotalLocalPlayers($location, $uid, $start=0, $ini = '', $id_sport = 1){
        return $this->registroDataTable->getTotalUsersLS($location, $uid, $start, $ini, $id_sport);
    }
    
    public function getLocalFriends($location, $uid, $friendsIds, $start=0, $ini = '', $id_sport = 1){
        return $this->registroDataTable->getUsersFLS($location, $uid, $friendsIds, $start, $ini, $id_sport);
    }
    
    public function getTotalLocalFriends($location, $uid, $friendsIds, $start=0, $ini = '', $id_sport = 1){
        return $this->registroDataTable->getTotalUsersFLS($location, $uid, $friendsIds, $start, $ini, $id_sport);
    }
    
        
    public function getCurrentBooking($user_id, $limit, $sta_id = 0){
        return $this->registroDataTable->getCurrentBooking($user_id, $limit, $sta_id)->toArray();
    }
    
    
    
    public function getFriendsId($fid, $type = 'Facebook'){
        $ids = "'-1'";
        if ($type == 'Facebook'){
            $friends_list = App_Util_Facebook::getFriendList($fid);
            if (count($friends_list) >= 1){
                foreach ($friends_list as $f) {
                    $ids .= ",'{$f['id']}'";
                }
            }
        }
        return $ids;
    }
    
    
    
    public function getSportsByUser($uid){
        $sports = new User_Model_DbTable_SportsUser();
        return $sports->getUserSports($uid)->toArray();
    }
    
    public function saveSportsByUser ($uid=0, $id_sports=array()) {
        $sports = new User_Model_DbTable_SportsUser();
        $ok = false;
        $sports->deleteSportsByUser($uid);
        foreach ($id_sports as $id_sport) {
            $default = 0;
            if ($id_sport == 1){ $default=1; } 
            $data = array('sport_id' => $id_sport, 'user_id' => $uid, 'default' => $default);
            if ($sports->insert($data)) {
                $ok = true;
            }
        }
        return $ok;
    }
    
    
    public function saveLocationByUser($uid=0, $data=array()){
        if ($uid > 0){
            if (count($data)>0){
                $location_array = $data;
                $data['user_id'] = $uid;
                $location = new User_Model_DbTable_Userlocation();
                $user = new User_Model_DbTable_User();
                $location->insert($data);
                $user->update(
                        array(
                             'use_location_id'=>'', 
                            'use_location_name'=>$data['use_loc_city'].', '.$data['use_loc_country']
                            ), 
                        "id_user = {$uid}"
                        );
                $location_array['use_location_name'] = $data['use_loc_city'].', '.$data['use_loc_country'];
                $_SESSION['user_location'] = $location_array;
            }else{
                return false;
            }
        }else {
            return false;
        }
    }
    
    
     public function changeLocationByUser($uid=0, $data=array()){
        //if ($uid > 0){
            if (count($data)>0){
                $location_array = $data;
                $data['user_id'] = $uid;
                $location_array['use_location_name'] = $data['use_loc_city'].', '.$data['use_loc_country'];
                $_SESSION['user_location'] = $location_array;
            }else{
                return false;
            }
        //}else {
         //   return false;
        //}
    }
    
    public function getPlayers($uid=0, $ini = '', $limit = 5){
        return $this->registroDataTable->getPlayers($uid, $ini, $limit);
    }
    
    
    
    
    public function getMessageAutochat($uid){
        $autochat = new User_Model_UserAutochat();
        return $autochat->getMessageByUser($uid);
    }
    
    public function updateAutochat($uid, $mid){
        $autochat = new User_Model_UserAutochat();
        $autochat->updateSend($uid, $mid);
    }
    
    public function updateLastActivity($seconds = -300){
        $user = new User_Model_DbTable_User();
        $user->getAdapter()->query(new Zend_Db_Expr("UPDATE user SET lastactivity = lastactivity +({$seconds}) WHERE id_user = ".App_User::getUserId()));
    }
	
	
	/**
	 * Guarda los amigos a un usuario dado de acuerdo a los id de facebook dados
	 * Actualizacion masiva realizada para tener datos y probar el chat
	 * Debe crearse metodos mas sofisticados para sincronizar
	 */
	public function saveFacebookFriends ($userId, $facebookIds) {
		$user = new User_Model_DbTable_User();
        $user->getAdapter()->query(new Zend_Db_Expr("INSERT INTO user_friends (friend1, friend2) SELECT {$userId}, id_user FROM user WHERE Facebook_id IN ({$facebookIds})"));
	}
    
	
	/**
	 * Actualiza ultima actividad para mantener en estado conectado
	 * 
	 */
	 public function updateLastActivityMobile($userId){
	 	$user = new User_Model_DbTable_User();
		$user->updateLastActivity($userId);
	 }
	 
	 
	 /**
	 * Actualiza ultima actividad para mantener en estado conectado
	 * 
	 */
	 public function getUserFriendsMobile($userId){
	 	$user = new User_Model_DbTable_User();
		$friends = $user->getUserFriends($userId);
		return $friends;
	 }
	 

}