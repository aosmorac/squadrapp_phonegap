<?php
/**
 * class BranchController
 * 
 * Clase de inicio por defecto del sistema
 */
class Admin_BranchController extends Zend_Controller_Action
{
	
    private $branch;
	
    public function init(){
        $this->branch = new Ground_Model_DbTable_Branch;
    }
    
    /**
     * Action index
     * 
     * Action de inicio por defecto
     */
    public function indexAction ()
    {

    }

    
   
    public function bookingPricesAction(){
       
    }
    
    
    public function autoCompleteBranchAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $ini = $this->getParam('term');
        $branch = new Ground_Model_Branch();
        $branches = $branch->getBranches(2257, 1);
        //Zend_Debug::dump($branches); die;
        echo json_encode($branches);
    }
    
    
    public function branchFieldsAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $bra_id = $this->getParam('bra_id');
        $branch = new Ground_Model_Branch();
        $fields = $branch->getFields($bra_id);
        Zend_Debug::dump($fields);
    }
    

      

    
}
?>
