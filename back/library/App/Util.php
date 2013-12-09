<?php

/**
 * Clase para obtener variables globales y varios
 * @author Marino Perez
 */
class App_Util {
	
	private static $_globalConfig = null;
	
	public function __construct() {
		throw new Exception ( "La clase App_Util no debe ser instanciada. Utilice sus metodos de manera estatica." );
	}
	
	private static function init() {
		if (null === self::$_globalConfig) {
			self::$_globalConfig = Zend_Registry::get ( "globalConfig" );
		}
	}
	
	/**
	 * retorna valor global
	 * @return integer
	 */
	public static function getGlobalVar($value, $subvalue = NULL) {
		self::init ();
		if (isset ( self::$_globalConfig->$value )) {
			if (is_null ( $subvalue ))
				return self::$_globalConfig->$value->toArray ();
			$list = self::$_globalConfig->$value->toArray ();
			if (isset ( $list [$subvalue] )) {
				return $list [$subvalue];
			}
		}
		return null;
	}
	
	/*
 * Log para firebug
 */
	public static function log($mensaje, $type = "info",$showFB=false) {
		$typeError = Zend_Log::DEBUG;
		switch ($type) {
			case "error" :
				$typeError = Zend_Log::ERR;
				break;
			case "alert" :
				$typeError = Zend_Log::ALERT;
				break;
			case "critic" :
				$typeError = Zend_Log::CRIT;
				break;
			case "debug" :
				$typeError = Zend_Log::DEBUG;
				break;
			case "emergency" :
				$typeError = Zend_Log::EMERG;
				break;
			case "info" :
				$typeError = Zend_Log::INFO;
				break;
			case "notice" :
				$typeError = Zend_Log::NOTICE;
				break;
			case "warning" :
				$typeError = Zend_Log::WARN;
				break;
		}
		if($showFB){
			Zend_Registry::get ( 'logger' )->log ( substr($mensaje,0,200), $typeError );
		}
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$date = new Zend_Date();
		$nameFileLog = "log_".$request->getModuleName() . "_" . $request->getControllerName() . "_" .$date->toString("yyyyM").".log";
 		$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH."/log/{$nameFileLog}");
 		$logger = new Zend_Log($writer);
 		$logger->log ( $mensaje, $typeError );
	}
}

//fin de la clase
