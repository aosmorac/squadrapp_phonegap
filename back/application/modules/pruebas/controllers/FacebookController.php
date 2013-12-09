<?php
/**
 * class FacebookController
 * 
 * Clase de inicio por defecto del sistema
 */
class Pruebas_FacebookController extends Zend_Controller_Action
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
		$this->view->title = "Facebook Pruebas | ".$this->textModule->index->label;
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
	public function getProfileAction ()
    {
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $profile = App_Util_Facebook::getProfile();
        Zend_Debug::dump($profile);
    }
    
    
    public function getFriendsAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        Zend_Debug::dump(App_User::getFriendsFacebookIds());
    }
    
   

    
}
?>
