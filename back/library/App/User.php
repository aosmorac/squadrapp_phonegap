<?php

/**
 * Clase para encapsular y abstraer el acceso a datos del usuario autenticado en el sistema.
 * @author Marino Perez
 */
class App_User {

    private static $_loggedUser = null;

    public function __construct() {
        throw new Exception("La clase App_User no debe ser instanciada. Utilice sus metodos de manera estatica.");
    }

    private static function init() {
        if (null === self::$_loggedUser) {
            self::$_loggedUser = new Zend_Session_Namespace("loggedUser");
        }
    }

    /**
     * Indica si existe un usuario autenticado en el sistema
     * @return true|false
     */
    public static function isLogged() {
        return Zend_Auth::getInstance()->hasIdentity();
    }

    /**
     * Obtiene el id del usuario autenticado en el sistema
     * @return integer o null si no hay un usuario autenticado
     */
    public static function getUserId() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity()->id_user;
        }
        return 0;
    }
    
    /**
     * Obtiene el Facebook id del usuario autenticado en el sistema
     * @return integer o null si no hay un usuario autenticado
     */
    public static function getUserFacebookId() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity()->Facebook_id;
        }
        return 0;
    }

    /**
     * Obtiene el Facebook nombreusuario del usuario autenticado en el sistema
     * @return string o null si no hay un usuario autenticado
     */
    public static function getUserFacebookName() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity()->Facebook_username;
        }
        return '';
    }

    /**
     * Obtiene los nombres del usuario autenticado en el sistema
     * @return string o null si no hay un usuario autenticado
     */
    public static function getName() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity()->use_first_name;
        }
        return '';
    }

    /**
     * Obtiene los apellidos del usuario autenticado en el sistema
     * @return string o null si no hay un usuario autenticado
     */
    public static function getLastName() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity()->use_last_name;
        }
        return '';
    }

    /**
     * Obtiene el nombre completo (nombres y apellidos) del usuario autenticado en el sistema
     * @return string o null si no hay un usuario autenticado
     */
    public static function getFullName() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity()->use_name;
        }else{
            return 'Un Amigo';
        }
    }
    
    
    public static function getFacebookFriends() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity()->friends;
        }
        return '';
    }
    
    public static function getFriendsFacebookIds() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity()->friends_ids;
        }
        return '';
    }
    
    public static function getFriendsIds($category = 'all'){
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $user_model = new User_Model_DbTable_User();
            $users = $user_model->getUsersByFacebookids(self::getFriendsFacebookIds());
            $ids = '-1';
            foreach ($users as $u){
                $ids .= ",{$u['id_user']}";
            }
            return $ids;
        }
        return '';
    }


    
    public static function getCity() {
        if (isset($_SESSION['user_location'])) {
            $location = $_SESSION['user_location'];
            //Zend_Debug::dump($location);
            return $location['use_location_name'];
        }
        return false;
    }
    
    public static function getLocation($attr = ''){
        if (isset($_SESSION['user_location'])) {
            $location = $_SESSION['user_location'];
            if ($attr==''){
                return $location;
            }else{
                return array($attr=>$location[$attr]);
            }
        }
        return false;
    }
    
    public static function isLocated(){
        if (isset($_SESSION['user_location'])) {
            return true;
        }
        return false;
    }
    
    
    public static function getCityByIp(){
        /*$ip=$_SERVER['REMOTE_ADDR'];
        $addr_details = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));
        $city = stripslashes(ucfirst($addr_details['geoplugin_city']));
        //$countrycode = stripslashes(ucfirst($addr_details[geoplugin_countryCode]));
        $country = stripslashes(ucfirst($addr_details['geoplugin_countryName']));*/
        //if ($city == ''){
            $city = 'Bogotá'; $country = 'Colombia';
        //}
        return $city.', '.$country;
    }

    
    public static function getAutoChat(){
        if (isset($_SESSION['autochat'])) {
            $autochat = $_SESSION['autochat'];
            //Zend_Debug::dump($location);
            if (count($autochat)>1){ return $autochat; }
            else { return false; }
        }
        return false;
    }
    
    
    public static function getTimeZone(){
        if (isset($_SESSION['timezone'])) {
            return $_SESSION['timezone']; 
        }
        return false;
    }

    

    /**
     * Obtiene un atributo del usuario 
     * autenticado
     * @return string o null si no hay un usuario autenticado
     */
    public static function getAttrib($attribute) {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return Zend_Auth::getInstance()->getIdentity()->$attribute;
        }
        return '';
    }

    public static function getLanguage($lang=false) {
        return 'sp';
    }
    

}

//fin de la clase
