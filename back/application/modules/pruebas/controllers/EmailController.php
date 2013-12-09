<?php
/**
 * class EmailController
 * 
 * 
 */
class Pruebas_EmailController extends Zend_Controller_Action
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
		$this->view->title = "Email Pruebas | ".$this->textModule->index->label;
        $this->view->headTitle($this->view->title);
		$this->_helper->layout->setLayout('application');
    }
    
    /**
     * Action index
     * 
     * Action de inicio por defecto
     */
    public function indexAction ()
    {
	//$this->_helper->layout()->disableLayout ();
        //$this->_helper->viewRenderer->setNoRender(true);
    }
	
    
    
    /**
     * Action getProfile
     * 
     */
	public function sendAction ()
    {
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        App_Util_Mail::mail('team@squadrapp.com', 
                                  array("Abel Moreno"=>"moreno.abel@gmail.com"),// EL CAMBIO DE DESTINATARIO ES EN ESTA LINEA.
                                  "Bienvenido a SquadrApp",
                                  'Prueba', 
                                  "SquadrApp"      
                                 );//END App_Util_Mail::mail*/
    }
    
   

    
}
?>
