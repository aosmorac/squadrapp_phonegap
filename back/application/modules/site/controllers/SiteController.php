<?php
/**
 * class IndexController
 * 
 * Clase de inicio por defecto del sistema
 */
class Site_SiteController extends Zend_Controller_Action
{
	
	/**
	 * @var Zend_Config_Ini $textGlobal
	 * @var Zend_Config_Ini $textModule
	 */
	private $textGlobal;
	private $textModule;
	
    public function init(){
    	$this->textGlobal = App_Util_Language::getTextLanguage();
        $this->textModule = App_Util_Language::getTextLanguage($this->getRequest()->getModuleName()); 
        $this->view->placeholder("title")->set($this->textModule->index->title);
        $this->_helper->layout->setLayout('mobile_home');
    }
    
    /**
     * Action index
     * 
     * Action de inicio por defecto
     */
    public function indexAction ()
    {
        $this->view->title = "Home | ".$this->textModule->index->label;
        $this->view->headTitle($this->view->title);
    	$this->view->headLink()->setStylesheet($this->view->baseUrl("/css/squadrapp/home.css"));

    }
	
	public function mobileIndexAction ()
    {
        $this->view->title = "Home | ".$this->textModule->index->label;
        $this->view->headTitle($this->view->title);
    	$this->view->headLink()->setStylesheet($this->view->baseUrl("/css/squadrapp/mobile_home.css"));

    }
    
    public function messageUserAction(){
        $this->view->title = "Bienvenido | ".$this->textModule->index->label;
        $this->view->headTitle($this->view->title);
    }
    
    /**
     * Action nuevaCanchaAction
     * 
     * Guarda información de nueva cancha
     */
    public function registroEmailAction ()
    {
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $variables = $this->_getAllParams();
        $registro = new Site_Model_IniJugadorEmail();
        if ($registro->newRegister($variables)){
            echo 'ok';
        }else {
            echo 'error';
        }

    }
    
    
    /**
     * Action registrarCanchas
     * 
     * Registro de canchas
     */
    public function registroCanchaAction ()
    {
        $this->view->title = "Registra tu Cancha | ".$this->textModule->index->label;
        $this->view->headTitle($this->view->title);
    	$this->_helper->layout->setLayout('application');

    }
	public function mobileRegistroCanchaAction ()
    {
        $this->view->title = "Registra tu Cancha | ".$this->textModule->index->label;
        $this->view->headTitle($this->view->title);
	$this->view->headLink()->setStylesheet($this->view->baseUrl("/css/squadrapp/mobile_registro_cancha.css"));

    }
    
    public function messageFieldAction(){
        $this->view->title = "Bienvenido | ".$this->textModule->index->label;
        $this->view->headTitle($this->view->title);
    }
    
     /**
     * Action nuevaCanchaAction
     * 
     * Guarda información de nueva cancha
     */
    public function nuevaCanchaAction ()
    {
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $registro = new Site_Model_IniRegistro();
        if ($registro->newRegister($_REQUEST)){
            echo 'ok';
        }else {
            echo 'error';
        }

    }

    
}
?>
