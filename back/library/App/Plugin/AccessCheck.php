<?php

class App_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract {

	function __construct() {

	}

	/**
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$moduleName = $request->getModuleName();
		$controllerName = $request->getControllerName();
		$actionName = $request->getActionName();
		$menu = $request->getParam("menu");
		$frontController = Zend_Controller_Front::getInstance();
		
		$frontController->throwExceptions(false);
		//En este caso se deja continuar sin estar autenticado, ya que es el Action que realiza
		//la autenticación por primera vez
		if ("error" == $controllerName) {
			return;
		}

		if ("sia" == $moduleName) {
			return;
		}
		if ("general" == $moduleName && "comment" == $controllerName && "savefile" == $actionName) {
			return;
		}
		if ("general" == $moduleName && "comment" == $controllerName && "json" == $actionName) {
			return;
		}
		if ("general" == $moduleName && "comment" == $controllerName && "forward" == $actionName) {
			return;
		}
		if ("sia" == $moduleName && "articleMaster" == $controllerName) {
			return;
		}
		//permitir cron sin necesidad de logueo
		if ("general" == $moduleName && "cron" == $controllerName) {
			return;
		}
		if ("manager" == $moduleName && "reports" == $controllerName && "view" == $actionName) {
			return;
		}
		if ("users" == $moduleName && "security" == $controllerName && "login" == $actionName) {
			return;
		}
		if ("globalbluenet" == $moduleName && "index" == $controllerName && "test" == $actionName) {
			return;
		}
		if ("settings" == $moduleName && "dataGrid" == $controllerName && "export" == $actionName) {
			return;
		}
		
		if ("general" == $moduleName && "file" == $controllerName) {
			return;
		}
		if ("globalbluenet" == $moduleName && "index" == $controllerName && "login" == $actionName) {
			return;
		}
		if ("globalbluenet" == $moduleName && "index" == $controllerName && "index" == $actionName) {
			return;
		}
		if ("globalbluenet" == $moduleName && "index" == $controllerName && "denegado" == $actionName) {
			return;
		}
		if ("users" == $moduleName && "security" == $controllerName && "forgotpassword" == $actionName) {
			return;
		}
	if ("operation" == $moduleName && "shippingQuota" == $controllerName) {
			return;
		}
		
		if (!("users" == $moduleName && "security" == $controllerName && ("roles" == $actionName || "renewpassword" == $actionName ))) {
			$loggedUser = new Zend_Session_Namespace("loggedUser");
			$loggedUser->PageInitial = "";
			if (!empty($menu)) {
				$loggedUser->MenuActual = $menu;
				$loggedUser->MVC = array( "Module"=>$moduleName,"Controller"=>$controllerName,"action"=>$actionName);
				$loggedUser->PageInitial = $request->getPathInfo();
			}
		}

		//Verificamos si el usuario se encuentra autenticado
		if (!App_User::isLogged()) {
			$request->setModuleName("globalbluenet")->setControllerName("index")->setActionName("login");
			return;
		}
		
		if(App_Perfil::isSpecialModule("{$moduleName}/{$controllerName}/{$actionName}")){
			return;
		}
		
		$user = Zend_Auth::getInstance()->getIdentity();
		//Ahora se valida que la url no haya sido manipulada por el usuario
		if (!App_Util_SafeUrl::validate($request)) {
			//FIXME: No dejar pasar a nadie , MPerez
			$request->setModuleName ( "globalbluenet" )->setControllerName ( "error" )->setActionName ( "ilegal" );
			//Zend_Debug::dump("Por ahora esto es una advertencia de que la URL no está debidamente encriptada. En un futuro cercano no te dejaré pasar asi. Preguntar a MPerez.");
			//return;
		}

		//		if (!$frontController->getDispatcher()->isDispatchable ( $request )) {
		//			$request->setModuleName( "bluenet" )->setControllerName( 'index' )->setActionName( 'index' );
		//		}
		//
		//FIXME a continuacion se debería verificar si tiene permiso para el modulo, controlador, y/o accion

		if (!isset($user)) {
			throw new Exception('Usted debe ingresar para acceder a esta página.');
		}
		//		$auth = Zend_Auth::getInstance ();
	}

	/*
	 public function postDispatch(Zend_Controller_Request_Abstract $request) {

	 $moduleName = $request->getModuleName();
	 $titulo = "-";
	 switch ($moduleName) {
	 case "users":
	 $titulo = "Gestor de usuarios";
	 break;
	 case "default":
	 $titulo = "Bienvenido!";
	 break;
	 }

	 $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
	 $viewRenderer->initView();
	 //Zend_Debug::dump($viewRenderer->view->placeholder("titulo"));
	 if(!count($viewRenderer->view->placeholder("titulo"))) {
	 $viewRenderer->view->placeholder("titulo")->set($titulo);
	 }

	 } */
}

//fin de la clase