<?php

/**
 * class Bootstrap
 * 
 * Cargar diferentes recursos que se va a necesitar en la aplicaciÃ³n
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    // FIXME: Esto se debe comentar cuando salga a pruebas, mPerez
    /**
     * Inicia el registro de errores para ser observados en el Firebug del navegador firefox
     * @return Zend_Log
     */
    protected function _initLogConfig() {
        $logger = new Zend_Log();
        $writer = new Zend_Log_Writer_Firebug();
        $logger->addWriter($writer);
        Zend_Registry::set('logger', $logger);

        //$logger->log("hola", "error");
        return $logger;
    }

    /**
     * Inicia el autocargado de los modulos
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload() {
        $autoloader = new Zend_Application_Module_Autoloader(
                        array('namespace' => '', 'basePath' => dirname(__FILE__)));
        return $autoloader;
    }
    

    /**
     * Inicia el timezone
     * @return string
     */
    protected function _initTimeZone() {
        date_default_timezone_set("America/Bogota");
        return date_default_timezone_get();
    }

    /**
     * Inicia y registra el cache
     * @return Zend_Cache_Core 
     */
    protected function _initCache() {
        $this->bootstrap("cachemanager");
        $manager = $this->getResource("cachemanager");
        //Zend_Db_Table_Abstract::setDefaultMetadataCache($cache->getCache("database"));
        $dbCache = $manager->getCache("database");
        Zend_Registry::set("cache", $dbCache);
        //Zend_Debug::dump($dbCache);
        return $dbCache;
    }

    /**
     * Inicia el Locale al default de la zona
     * @return Zend_Locale
     */
    protected function _initLocale() {
        $locale = new Zend_Locale('es_CO');
        Zend_Registry::set('Zend_Locale', $locale);
        return $locale;
    }

    /**
     * Carga el application.ini en la variable appConfig
     * @return Zend_Config
     */
    protected function _initAppConfig() {
        $appConfig = new Zend_Config($this->getOptions());
        Zend_Registry::set('appConfig', $appConfig);
        return $appConfig;
    }

    /**
     * Registra las bases de datos del application.ini y define la base por defecto
     */
    public function _initDb() {
        $appConfig = Zend_Registry::get('appConfig');
        foreach ($appConfig->db as $key => $db) {
            $db_factory = Zend_Db::factory($db->adapter, $db->params->toArray());
            $db_factory->setFetchMode(Zend_Db::FETCH_OBJ);
            Zend_Registry::set($key, $db_factory);
            if ($db->isDefaultTableAdapter) {
                Zend_Db_Table::setDefaultAdapter($db_factory);
            }
            // In your bootstrap file

            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled(true);

// Attach the profiler to your db adapter
           $db_factory->setProfiler($profiler);
        }
    }


	protected function _initGlobalConfig() {
		$globalConfig = new Zend_Config_Ini(APPLICATION_PATH.'/configs/global.ini', APPLICATION_ENV);
        Zend_Registry::set('globalConfig', $globalConfig);
        return $globalConfig;
	}
//    protected  function _initJQuery()
//    {
//        $this->bootstrap('view');
//        $view = $this->getResource( 'view' );
//       
//        
//        $config = new Zend_Config_Ini( APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV ); 
//        $view->jQuery()->setUiLocalPath($view->baseUrl($config->resources->Jquery->ui_localpath)); 
//        $view->jQuery()->setLocalPath($view->baseUrl($config->resources->Jquery->localpath));
//        //$view->jQuery()->setStyleSheet($view->baseUrl($config->resources->Jquery->stylesheet));
//    }
        
        protected function _initMail(){
            //$config = array('auth' => 'plain','username' => 'soporte@bluecargogroup.com','password' => 'HELPDESK');
            //$Server = new Zend_Mail_Transport_Smtp('mail.bluecargogroup.com', $config);
            //Zend_Mail::setDefaultTransport($Server);
        }
        
		public function _initErrorHandler()
		{
		    $frontController = Zend_Controller_Front::getInstance();
		    $frontController->throwExceptions(false);
		    $plugin = new Zend_Controller_Plugin_ErrorHandler(
		        array(
		            'module' => 'site',
		            'controller' => 'error',
		            'action' => 'error'
		    ));
		    $frontController->registerPlugin($plugin);
		    return $plugin;
		}
}