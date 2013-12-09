<?php
/**
 * class FieldsController
 * 
 */
class App_FieldsController extends Zend_Controller_Action
{
	
    private $fields;
	
    public function init(){
        $this->fields = new App_Model_Fields;
    }
    
    /**
     * Action index
     * 
     * Action de inicio por defecto
     */
    public function indexAction ()
    {
    }


	
	public function loadFieldsAction(){
        header("Access-Control-Allow-Origin: *");   //  Ajax desde cualquier llamado
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vars = $this->_getAllParams();
        $fields = $this->fields->getPlacesByCity($vars['cid'], $vars['sid']);
        echo json_encode($fields);
    }
    
}
?>
