<?php

class App_Util_SafeUrl {
	
    /**#@+
     * Constante para "ensuciar" el hash.
     */
    const TRASH = 'j5*6%^#-y=/;!~3ari+op83|mperez';
    /**#@-*/
	
	/**
	 * Toma un array asociativo de parametros y le incluye una llave para posterior verificación
	 * de que no haya sido manipulada.
	 * @param array $params
         * @param boolean $asString
	 * @return mixed Un array o un string para ser concatenado a una url (attr/dato/_lock/gj754b67)
	 */
	static function encryp(array $params, $asString=false) {
		if (isset($params["_lock"])) {
			throw new Exception("El array de parametros a encriptar, no debe contener un nombre '_lock'.");
		}
		$cadena = self::TRASH;
		
		foreach ($params as $key => $value) {
			$cadena.=$key.$value;
		}
		
		$params["_lock"]=md5($cadena);
		
		if ($asString) {
			reset($params);
			$vector=array();
			foreach ($params as $key => $value) {
				$vector[]=$key."/".$value;
			}
			return implode("/",$vector);
		}
               
		return $params;
	}
	
	static function encryptString($params) {  
		if (strrpos($params,'/_lock/')) {
			throw new Exception("Los parametros a encriptar, no debe contener un nombre '_lock'.");
		}
		$cadena = self::TRASH;
		$cadena.=str_replace("/","",$params);
		return $params."/_lock/".md5($cadena);
	}
	
	/**
	 * Verifica los parametros de un request, para asegurarse que no se hayan manipulado
	 * los datos de la url.
	 * @param Zend_Controller_Request_Abstract $request
	 * @return boolean
	 */
	static function validate(Zend_Controller_Request_Abstract $request) {

		$params = $request->getUserParams();
		unset($params["module"],$params["controller"],$params["action"],$params["error_handler"],$params["format"]);

		if(count($params) == 0) {
			return true;
		}
		
		if (!isset($params["_lock"])) {
			return false;
		}
		$user_key = $params["_lock"];
		unset($params["_lock"]);
		
		$cadena = self::TRASH;
		foreach ($params as $key => $value) {
			$cadena.=$key.$value;
		}
		
		if ($user_key == md5($cadena)) {
			return true;
		}
		return false;
	}
}//fin de la clase
