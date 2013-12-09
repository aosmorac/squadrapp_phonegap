<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "functions.php";
include "ControllerBase.php";
include "ModelBase.php";

class FrontController
{
	static function main()
	{
 
        mb_internal_encoding("UTF-8");
		require 'lib/Config.php'; //de configuracion
		require 'lib/SPDO.php'; //PDO con singleton
		require 'lib/View.php'; //Mini motor de plantillas
              
		require 'config.php'; //Archivo con configuraciones.
 
    
        /* Language */
        require 'lib/lang/'.$config->get('lang').'.php';
        

        $PATH = dirname(__FILE__);

		if(gett('p') != -1) $controllerName = gett('p')  . 'Controller';
		else  $controllerName = "showController";
 
		if(gett('m') != -1) $actionName = gett('m');
		else $actionName = "table";
                	
		$controllerPath = $config->get('controllersFolder') . $controllerName . '.php';

        $fingerprint = md5($_SERVER['HTTP_USER_AGENT'].$config->get('base_title'));
    	if (!isset($_SESSION['initiated']) or !$_SESSION['initiated'] or !isset($_SESSION['HTTP_USER_AGENT']) or  $_SESSION['HTTP_USER_AGENT'] != $fingerprint ){
			
			require($config->get('controllersFolder') .'loginController.php');
    		$controller = new loginController();
    		if ($controllerName != 'loginController'){
    		$controller->index();
			} else { 
    		$controller->login();
				
			}
    	if (isset($_SESSION['HTTP_USER_AGENT']) and $_SESSION['HTTP_USER_AGENT'] != $fingerprint) session_destroy();
    	} else {
            
    		if(is_file($controllerPath)) require $controllerPath;
    		else  die('El controlador '.$controllerPath.' no existe - 404 not found');
    		      
    		if (is_callable(array($controllerName, $actionName)) == false){
    			trigger_error ($controllerName . '->' . $actionName . '` no existe', E_USER_NOTICE);
    			return false;
    		}
    
        
        	
    		$controller = new $controllerName();
    		$controller->$actionName();
		}
	}
    
 
   

}
?>