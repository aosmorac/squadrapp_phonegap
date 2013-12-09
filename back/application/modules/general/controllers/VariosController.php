<?php
/**
 * class CronController
 * 
 */
class General_VariosController extends Zend_Controller_Action
{
	
        
	
    public function init(){
        
    }
    
    /**
     * Action index
     * 
     * Action de inicio por defecto
     */
    public function indexAction ()
    {

    }
	
    
    
    /**
     * Action Registro
     * Entra al haber registro
     * No realiza funcion
     * 
     */
	public function squadrappRegistroAction ()
    {
            
    }
    
    
    /**
     * Accion para redireccionar a pagina desde app
     */
    public function appFacebookAction(){
        $this->_helper->layout()->disableLayout ();
    }
       

    
}
?>
